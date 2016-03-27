<?php

namespace App\DataProviders\Organisations;

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
}
