<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Exceptions\NotFoundException;

/**
 * Interface OrganisationsDataProviderInterface
 * @package App\DataProviders\OrganisationsCollection
 */
interface OrganisationsDataProviderInterface
{
    /**
     * @param array $titles
     * @return array
     */
    public function fetchIdsByTitles(array $titles);

    /**
     * @param string $title
     * @return array
     * @throws NotFoundException
     */
    public function getOrganisationRelations($title);

    /**
     * @param string $title
     * @return int
     * @throws NotFoundException
     */
    public function getOrganisationId($title);

    /**
     *
     */
    public function deleteAll();

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     */
    public function storeRelations(OrganisationsCollection $organisations, array $ids);
}
