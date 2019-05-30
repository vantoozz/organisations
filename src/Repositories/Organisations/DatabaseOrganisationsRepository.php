<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;
use App\Collections\RelationsCollection;
use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Hydrators\RelationsCollection\DatabaseRelationsCollectionHydrator;
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
     * @var DatabaseRelationsCollectionHydrator
     */
    private $hydrator;
    /**
     * @var
     */
    private $maxPerPage;

    /**
     * DatabaseOrganisationsRepository constructor.
     * @param OrganisationsDataProviderInterface $dataProvider
     * @param DatabaseRelationsCollectionHydrator $hydrator
     * @param $maxPerPage
     */
    public function __construct(
        OrganisationsDataProviderInterface $dataProvider,
        DatabaseRelationsCollectionHydrator $hydrator,
        $maxPerPage
    ) {
    

        $this->dataProvider = $dataProvider;
        $this->hydrator = $hydrator;
        $this->maxPerPage = (int)$maxPerPage;
    }

    /**
     * @param string $title
     * @param int $page
     * @return RelationsCollection
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public function getRelationsByTitle($title, $page)
    {
        $organisationId = $this->dataProvider->getOrganisationId($title);

        $count = $this->dataProvider->getOrganisationRelationsCount($organisationId);
        $limit = $this->maxPerPage;
        $offset = $limit * ($page - 1);

        $data = [];
        if ($offset < $count) {
            $data = $this->dataProvider->getOrganisationRelations($organisationId, $limit, $offset);
        }

        $data = array_map(function ($row) use ($title) {
            $row['from'] = $title;
            return $row;
        }, $data);

        return $this->hydrator->hydrate($data);
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
