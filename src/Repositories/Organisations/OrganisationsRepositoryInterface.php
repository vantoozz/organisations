<?php


namespace App\Repositories\Organisations;

use App\Collections\OrganisationsCollection;

/**
 * Interface OrganisationsRepositoryInterface
 * @package App\Repositories\Organisations
 */
interface OrganisationsRepositoryInterface
{
    /**
     * @param OrganisationsCollection $organisations
     */
    public function store(OrganisationsCollection $organisations);
}
