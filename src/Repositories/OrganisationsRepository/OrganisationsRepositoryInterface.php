<?php


namespace App\Repositories\OrganisationsRepository;

use Illuminate\Support\Collection;

/**
 * Interface OrganisationsRepositoryInterface
 * @package App\Repositories\OrganisationsRepository
 */
interface OrganisationsRepositoryInterface
{
    /**
     * @param Collection $organisations
     * @return mixed
     */
    public function store(Collection $organisations);
}
