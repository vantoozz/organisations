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
     * DatabaseOrganisationsRepository constructor.
     * @param OrganisationsDataProviderInterface $dataProvider
     */
    public function __construct(OrganisationsDataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param string $title
     * @return array
     * @throws NotFoundException
     */
    public function getOrganisationRelations($title)
    {
        // TODO: Implement getOrganisationRelations() method.
    }
}
