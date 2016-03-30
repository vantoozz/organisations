<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Exceptions\NotFoundException;
use App\Organisation;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Class DatabaseOrganisationsDataProvider
 * @package App\DataProviders\OrganisationsCollection
 */
class DatabaseOrganisationsDataProvider implements OrganisationsDataProviderInterface
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * DatabaseOrganisationsRepository constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param array $titles
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetchIdsByTitles(array $titles)
    {
        $ids = [];

        $result = $this->db->executeQuery(
            'SELECT `id`, `title` FROM `organisations` WHERE `title` IN (?)',
            [$titles],
            [Connection::PARAM_STR_ARRAY]
        )->fetchAll(\PDO::FETCH_ASSOC);

        $titlesToStore = array_flip($titles);
        foreach ($result as $row) {
            $ids[$row['title']] = (int)$row['id'];
            unset($titlesToStore[$row['title']]);
        }

        if (0 < count($titlesToStore)) {
            $ids = array_merge($ids, $this->storeTitles(array_keys($titlesToStore)));
        }

        return $ids;
    }

    /**
     * @param array $titles
     * @return array
     */
    protected function storeTitles($titles)
    {
        $ids = [];

        foreach ($titles as $title) {
            $this->db->insert('organisations', ['title' => $title]);
            $ids[$title] = (int)$this->db->lastInsertId();
        }
        return $ids;
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getOrganisationRelations($id, $limit, $offset)
    {
        return $this->db->executeQuery(
            '
              SELECT o.title `to`, r.relation FROM(
                SELECT r1.parent_id AS id , "parent" AS relation FROM relations r1 WHERE r1.organisation_id = :id
                UNION 
                SELECT r2.organisation_id AS id, "daughter" AS relation FROM relations r2 WHERE r2.parent_id = :id
                UNION 
                SELECT r3.organisation_id AS id, "sister" AS relation FROM relations r3 WHERE r3.parent_id IN (
                  SELECT parent_id  FROM relations WHERE organisation_id = :id
                ) AND r3.organisation_id <> :id
              ) r
              LEFT JOIN organisations o ON o.id = r.id
              ORDER BY title
              LIMIT :lmt
              OFFSET :ofst
            ;',
            ['id' => $id, 'lmt' => $limit, 'ofst' => $offset],
            [\PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT]
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getOrganisationRelationsCount($id)
    {
        $count = $this->db->executeQuery(
            '
          SELECT sum(cnt) FROM(
            SELECT count(*) cnt  FROM relations r1 WHERE r1.organisation_id = :id OR r1.parent_id = :id 
            UNION ALL
            SELECT count(DISTINCT organisation_id) cnt FROM relations r2 WHERE r2.parent_id IN (
              SELECT parent_id  FROM relations WHERE organisation_id = :id
            ) AND r2.organisation_id <> :id
          ) t;',
            ['id' => $id],
            [\PDO::PARAM_INT]
        )->fetchColumn(0);

        return (int)$count;
    }

    /**
     * @param string $title
     * @return int
     * @throws NotFoundException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getOrganisationId($title)
    {
        $id = $this->db->executeQuery(
            'SELECT `id` FROM `organisations` WHERE `title` = :title;',
            [$title],
            [\PDO::PARAM_STR]
        )->fetchColumn(0);

        if (false === $id) {
            throw new NotFoundException('No such organisation: ' . $title);
        }

        return (int)$id;
    }

    /**
     * @throws DBALException
     */
    public function deleteAll()
    {
        $this->db->exec('DELETE FROM `relations`');
        $this->db->exec('DELETE FROM `organisations`');
    }

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     * @throws DBALException
     */
    public function storeRelations(OrganisationsCollection $organisations, array $ids)
    {
        foreach ($organisations as $organisation) {
            $this->storeOrganisationRelations($organisation, $ids);
        }
    }

    /**
     * @param Organisation $organisation
     * @param array $ids
     * @throws DBALException
     */
    private function storeOrganisationRelations(Organisation $organisation, array $ids)
    {
        if (empty($ids[$organisation->getTitle()])) {
            return;
        }
        $organisationId = $ids[$organisation->getTitle()];

        $result = $this->db->executeQuery(
            'SELECT `parent_id` FROM relations WHERE `organisation_id` = :organisation_id;',
            ['organisation_id' => $organisationId],
            [\PDO::PARAM_INT]
        )->fetchAll(\PDO::FETCH_ASSOC);

        $stored = [];
        foreach ($result as $row) {
            $stored[] = (int)$row['parent_id'];
        }

        foreach ($organisation->getParents() as $parent) {
            if (empty($ids[$parent->getTitle()])) {
                continue;
            }
            $parentId = $ids[$parent->getTitle()];
            if (in_array($parentId, $stored, true)) {
                continue;
            }
            $this->db->insert('relations', ['organisation_id' => $organisationId, 'parent_id' => $parentId]);
            $stored[] = $parentId;
        }
    }
}
