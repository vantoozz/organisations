<?php

namespace App\DataProviders\Organisations;

/**
 * Class MysqlOrganisationsDataProvider
 * @package App\DataProviders\Organisations
 */
class MysqlOrganisationsDataProvider extends DatabaseOrganisationsDataProvider
{
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
