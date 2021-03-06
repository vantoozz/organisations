<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Organisation;
use Doctrine\DBAL\DBALException;

/**
 * Class MysqlOrganisationsDataProvider
 * @package App\DataProviders\OrganisationsCollection
 */
class MysqlOrganisationsDataProvider extends DatabaseOrganisationsDataProvider
{
    /**
     * @throws DBALException
     */
    public function deleteAll()
    {
        $checkKeysStatus = $this->connection
                                ->executeQuery('SHOW SESSION VARIABLES LIKE "foreign_key_checks";')
                                ->fetchColumn(1);
        $this->connection->exec('SET foreign_key_checks=0;');
        $this->connection->exec('TRUNCATE `relations`;');
        $this->connection->exec('TRUNCATE `organisations`;');
        $this->connection->executeQuery('SET foreign_key_checks=:status;', [$checkKeysStatus], [\PDO::PARAM_STR]);
    }

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     * @throws DBALException
     */
    public function storeRelations(OrganisationsCollection $organisations, array $ids)
    {
        $values = [];
        foreach ($organisations as $organisation) {
            if (empty($ids[$organisation->getTitle()])) {
                continue;
            }
            $organisationId = $ids[$organisation->getTitle()];
            foreach ($organisation->getParents() as $parent) {
                /** @var Organisation $parent */
                if (empty($ids[$parent->getTitle()])) {
                    continue;
                }
                $parentId = $ids[$parent->getTitle()];
                $values[] = '(' . (int)$organisationId . ', ' . (int)$parentId . ')';
            }
        }

        if (0 === count($values)) {
            return;
        }

        $query = '
            INSERT INTO `relations` (`organisation_id`, `parent_id`) 
            VALUES ' . implode(', ', $values) . ' 
            ON DUPLICATE KEY UPDATE parent_id = parent_id
            ;';

        $this->connection->exec($query);
    }

    /**
     * @param array $titles
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function storeTitles($titles)
    {
        $values = implode(', ', array_fill(0, count($titles), '(?)'));
        $query = 'INSERT INTO organisations (`title`) VALUES ' . $values . ' ;';
        $statement = $this->connection->prepare($query);

        $param = 0;
        foreach ($titles as $title) {
            $statement->bindValue(++$param, $title);
        }
        $statement->execute();

        return $this->fetchIdsByTitles($titles);
    }
}
