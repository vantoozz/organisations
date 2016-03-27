<?php

namespace App\Repositories\Relations;

use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
use App\Exceptions\NotFoundException;

/**
 * Class DatabaseRelationsRepository
 * @package App\Repositories\Relations
 */
class DatabaseRelationsRepository implements RelationsRepositoryInterface
{

    /**
     * @var OrganisationsDataProviderInterface
     */
    private $dataProvider;

    /**
     * @var int
     */
    private $maxPerPage;

    /**
     * DatabaseOrganisationsRepository constructor.
     * @param OrganisationsDataProviderInterface $dataProvider
     * @param $maxPerPage
     */
    public function __construct(OrganisationsDataProviderInterface $dataProvider, $maxPerPage)
    {
        $this->dataProvider = $dataProvider;
        $this->maxPerPage = (int)$maxPerPage;
    }

    /**
     * @param string $title
     * @param int $page
     * @return array
     * @throws NotFoundException
     */
    public function getOrganisationRelations($title, $page)
    {
        $organisationId = $this->dataProvider->getOrganisationId($title);

        $count = $this->dataProvider->getOrganisationRelationsCount($organisationId);
        $limit = $this->maxPerPage;
        $offset = $limit * ($page - 1);
        
        $data = [];
        if ($offset < $count) {
            $data = $this->dataProvider->getOrganisationRelations($organisationId, $limit, $offset);
        }

        return $data;
    }
}
