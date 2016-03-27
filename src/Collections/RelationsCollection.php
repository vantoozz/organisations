<?php

namespace App\Collections;

use App\Relation;

/**
 * Class RelationsCollection
 * @package App\Collections
 */
class RelationsCollection extends TypedCollection
{
    /**
     * @var string
     */
    protected $type = Relation::class;

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        //nothing to do here
    }
}
