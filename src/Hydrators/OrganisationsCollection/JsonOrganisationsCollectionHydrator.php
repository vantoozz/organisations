<?php

namespace App\Hydrators\OrganisationsCollection;

use App\Collections\OrganisationsCollection;
use App\Exceptions\InvalidArgumentException;
use App\Hydrators\HydratorInterface;
use App\Organisation;

/**
 * Class JsonOrganisationsCollectionHydrator
 * @package App\Hydrators\OrganisationsCollection
 */
class JsonOrganisationsCollectionHydrator implements HydratorInterface
{
    const FIELD_DAUGHTERS = 'daughters';
    const FIELD_ORG_NAME = 'org_name';

    /**
     * @param OrganisationsCollection $resource
     * @return string
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function extract($resource)
    {
        if (!$resource instanceof OrganisationsCollection) {
            throw new InvalidArgumentException('Resource must be an instance of '.OrganisationsCollection::class);
        }

        return '';
    }

    /**
     * @param string $data
     * @return OrganisationsCollection
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function hydrate($data)
    {
        $data = json_decode($data, true);
        if (!is_array($data)) {
            throw new InvalidArgumentException('Bad input data. Error message: ' . json_last_error_msg());
        }
        
        $collection = new OrganisationsCollection;
        $this->populateCollection($collection, $data);
        
        return $collection;
    }

    /**
     * @param OrganisationsCollection $collection
     * @param array $data
     * @param Organisation|null $parent
     * @throws InvalidArgumentException
     */
    private function populateCollection(OrganisationsCollection $collection, array $data, Organisation $parent = null)
    {
        if (!array_key_exists(self::FIELD_ORG_NAME, $data)) {
            throw new InvalidArgumentException('No ' . self::FIELD_ORG_NAME . ' field');
        }

        $title = $data[self::FIELD_ORG_NAME];
        $organisation = $collection->get($title, new Organisation($title));

        if ($parent) {
            $organisation->addParent($parent);
        }

        $collection->put($organisation->getTitle(), $organisation);

        if (array_key_exists(self::FIELD_DAUGHTERS, $data) and is_array($data[self::FIELD_DAUGHTERS])) {
            foreach ($data[self::FIELD_DAUGHTERS] as $daughterData) {
                $this->populateCollection($collection, $daughterData, $organisation);
            }
        }
    }
}
