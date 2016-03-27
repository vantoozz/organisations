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
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrganisationRelations($id, $limit, $offset);

    /**
     * @param int $id
     * @return int
     */
    public function getOrganisationRelationsCount($id);

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
