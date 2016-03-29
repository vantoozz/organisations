<?php

namespace App\Hydrators\RelationsCollection;

use App\Collections\RelationsCollection;
use App\Exceptions\InvalidArgumentException;
use App\Hydrators\HydratorInterface;
use App\Relation;

/**
 * Class JsonRelationsCollectionHydrator
 * @package App\Hydrators\RelationsCollection
 */
class ArrayRelationsCollectionHydrator implements HydratorInterface
{

    const FIELD_TITLE = 'org_name';
    const FIELD_TYPE = 'relationship_type';

    /**
     * @param RelationsCollection $resource
     * @return string
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function extract($resource)
    {
        if (!$resource instanceof RelationsCollection) {
            throw new InvalidArgumentException('Resource must be an instance of RelationsCollection');
        }

        $data = [];
        foreach ($resource as $relation) {
            /** @var Relation $relation */
            $data[] = [
                self::FIELD_TITLE => $relation->getTo()->getTitle(),
                self::FIELD_TYPE => $relation->getRelationType()->getType()
            ];
        }

        return $data;
    }

    /**
     * @param array $data
     * @return RelationsCollection
     */
    public function hydrate($data)
    {
        return new RelationsCollection();
    }
}
