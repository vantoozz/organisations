<?php

namespace App\DataProviders\Organisations;

use Doctrine\DBAL\Connection;

/**
 * Class DatabaseOrganisationsDataProvider
 * @package App\DataProviders\Organisations
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
}
