<?php

namespace App\Collections;

use App\Organisation;

/**
 * Class OrganisationsCollection
 * @package App\Collections
 */
class OrganisationsCollection extends TypedCollection
{
    /**
     * @var string
     */
    protected $type = Organisation::class;

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        //nothing to do here
    }

    /**
     * @return string[]
     */
    public function getTitles()
    {
        $titles = [];
        foreach ($this->items as $item) {
            /** @var Organisation $item */
            $titles[] = $item->getTitle();
        }
        return $titles;
    }
}
