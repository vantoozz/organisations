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
        $this->db->exec('SET foreign_key_checks=0;');
        $this->db->exec('TRUNCATE `relations`;');
        $this->db->exec('TRUNCATE `organisations`;');
        $this->db->exec('SET foreign_key_checks=1;');
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
            $organisationId = $ids[$organisation->getTitle()];
            foreach ($organisation->getParents() as $parent) {
                /** @var Organisation $parent */
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

        $this->db->exec($query);
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
        $statement = $this->db->prepare($query);

        $i = 0;
        foreach ($titles as $title) {
            $statement->bindValue(++$i, $title);
        }
        $statement->execute();

        return $this->fetchIdsByTitles($titles);
    }
}
