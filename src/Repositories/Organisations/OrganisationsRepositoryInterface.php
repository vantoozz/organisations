<?php

namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;

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
}
