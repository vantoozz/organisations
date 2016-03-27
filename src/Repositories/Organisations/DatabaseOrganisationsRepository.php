<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;
use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use App\Organisation;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Class CommonDatabaseOrganisationsRepository
 * @package App\Repositories\Organisations
 */
class DatabaseOrganisationsRepository implements OrganisationsRepositoryInterface
{

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var OrganisationsDataProviderInterface
     */
    private $dataProvider;

    /**
     * DatabaseOrganisationsRepository constructor.
     * @param Connection $db
     * @param OrganisationsDataProviderInterface $dataProvider
     */
    public function __construct(Connection $db, OrganisationsDataProviderInterface $dataProvider)
    {
        $this->db = $db;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param OrganisationsCollection $organisations
     * @throws \Doctrine\DBAL\DBALException
     */
    public function store(OrganisationsCollection $organisations)
    {
        $titles = $this->getUniqueTitles($organisations);
        $ids = $this->dataProvider->fetchIdsByTitles($titles);
        $this->storeRelations($organisations, $ids);
    }

    /**
     * @param OrganisationsCollection $organisations
     * @return mixed
     */
    protected function getUniqueTitles(OrganisationsCollection $organisations)
    {
        $titles = [$organisations->getTitles()];
        foreach ($organisations as $organisation) {
            /** @var Organisation $organisation */
            $titles[] = $organisation->getParents()->getTitles();
        }

        return array_unique(array_flatten($titles));
    }

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     * @throws DBALException
     */
    protected function storeRelations(OrganisationsCollection $organisations, array $ids)
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
            $parentId = $ids[$parent->getTitle()];
            if (in_array($parentId, $stored, true)) {
                continue;
            }
            $this->db->insert('relations', ['organisation_id' => $organisationId, 'parent_id' => $parentId]);
            $stored[] = $parentId;
        }
    }
}
