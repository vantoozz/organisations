<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;
use App\Collections\RelationsCollection;
use App\Exceptions\NotFoundException;

/**
 * Interface OrganisationsRepositoryInterface
 * @package App\Repositories\OrganisationsCollection
 */
interface OrganisationsRepositoryInterface
{
    /**
     * @param OrganisationsCollection $organisations
     */
    public function store(OrganisationsCollection $organisations);

    /**
     *
     */
    public function deleteAll();

    /**
     * @param string $title
     * @param int $page
     * @return RelationsCollection
     * @throws NotFoundException
     */
    public function getRelationsByTitle($title, $page);
}
