<?php

namespace App\Repositories\Relations;

use App\Exceptions\NotFoundException;

interface RelationsRepositoryInterface
{
    /**
     * @param string $title
     * @param int $page
     * @return array
     * @throws NotFoundException
     */
    public function getOrganisationRelations($title, $page);
}
