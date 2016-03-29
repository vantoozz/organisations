<?php

namespace App\Hydrators\RelationsCollection;

use App\Collections\OrganisationsCollection;
use App\Collections\RelationsCollection;
use App\Exceptions\InvalidArgumentException;
use App\Hydrators\HydratorInterface;
use App\Organisation;
use App\Relation;
use App\RelationType;

/**
 * Class DatabaseRelationsCollectionHydrator
 * @package App\Hydrators\RelationsCollection
 */
class DatabaseRelationsCollectionHydrator implements HydratorInterface
{
    const FIELD_FROM = 'from';
    const FIELD_TO = 'to';
    const FIELD_RELATION = 'relation';

    /**
     * @param RelationsCollection $resource
     * @return string
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function extract($resource)
    {
        if (!$resource instanceof RelationsCollection) {
            throw new InvalidArgumentException('Resource must be an instance of '.RelationsCollection::class);
        }

        return '';
    }

    /**
     * @param array $data
     * @return RelationsCollection
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function hydrate($data)
    {
        $organisations = new OrganisationsCollection();
        $relations = new RelationsCollection();
        foreach ($data as $row) {
            $from = $organisations->get($row[self::FIELD_FROM], new Organisation($row[self::FIELD_FROM]));
            $to = $organisations->get($row[self::FIELD_TO], new Organisation($row[self::FIELD_TO]));

            $organisations->put($from->getTitle(), $from);
            $organisations->put($to->getTitle(), $to);

            $relation = new Relation($from, $to, new RelationType($row[self::FIELD_RELATION]));
            $relations->push($relation);
        }

        return $relations;
    }
}
