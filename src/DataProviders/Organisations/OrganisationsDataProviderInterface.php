<?php

namespace App\DataProviders\Organisations;

/**
 * Interface OrganisationsDataProviderInterface
 * @package App\DataProviders\Organisations
 */
interface OrganisationsDataProviderInterface
{
    /**
     * @param array $titles
     * @return array
     */
    public function fetchIdsByTitles(array $titles);
}
