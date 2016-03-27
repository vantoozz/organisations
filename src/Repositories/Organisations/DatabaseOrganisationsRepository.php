<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;
use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use App\Organisation;

/**
 * Class CommonDatabaseOrganisationsRepository
 * @package App\Repositories\OrganisationsCollection
 */
class DatabaseOrganisationsRepository implements OrganisationsRepositoryInterface
{

    /**
     * @var OrganisationsDataProviderInterface
     */
    private $dataProvider;

    /**
     * DatabaseOrganisationsRepository constructor.
     * @param OrganisationsDataProviderInterface $dataProvider
     */
    public function __construct(OrganisationsDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param OrganisationsCollection $organisations
     */
    public function store(OrganisationsCollection $organisations)
    {
        $titles = $this->getUniqueTitles($organisations);
        $ids = $this->dataProvider->fetchIdsByTitles($titles);
        $this->dataProvider->storeRelations($organisations, $ids);
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
     *
     */
    public function deleteAll()
    {
        return $this->dataProvider->deleteAll();
    }
}
