<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Exceptions\NotFoundException;
use Illuminate\Contracts\Cache\Repository;

/**
 * Class Cached
 * @package App\DataProviders\OrganisationsCollection
 */
class Cached implements OrganisationsDataProviderInterface
{
    const CACHE_PREFIX = 'CachedOrganisationsDataProviderInterface';

    /**
     * @var OrganisationsDataProviderInterface
     */
    private $provider;

    /**
     * @var Repository
     */
    private $cache;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Cached constructor.
     * @param OrganisationsDataProviderInterface $provider
     * @param Repository $cache
     * @param $ttl
     */
    public function __construct(OrganisationsDataProviderInterface $provider, Repository $cache, $ttl)
    {
        $this->provider = $provider;
        $this->cache = $cache;
        $this->ttl = (int)$ttl;
    }

    /**
     * @param array $titles
     * @return array
     */
    public function fetchIdsByTitles(array $titles)
    {
        $data = [];
        $notCached = [];
        foreach ($titles as $title) {
            $key = $this->makeKey(__METHOD__ . '_' . $title);
            if ($this->cache->has($key)) {
                $data[$title] = $this->cache->get($key);
            }
            $notCached[] = $title;
        }

        if (0 === count($notCached)) {
            return $data;
        }

        foreach ($this->provider->fetchIdsByTitles($notCached) as $title => $id) {
            $key = $this->makeKey(__METHOD__ . '_' . $title);
            $this->cache->put($key, $id, $this->ttl);
            $data[$title] = $id;
        }

        return $data;
    }

    /**
     * @param string $title
     * @return string
     */
    private function makeKey($title)
    {
        return self::CACHE_PREFIX . ':' . sha1($title);
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getOrganisationRelations($id, $limit, $offset)
    {
        return $this->provider->getOrganisationRelations($id, $limit, $offset);
    }

    /**
     * @param int $id
     * @return int
     */
    public function getOrganisationRelationsCount($id)
    {
        return $this->provider->getOrganisationRelationsCount($id);
    }
    
    /**
     * @return mixed
     */
    public function deleteAll()
    {
        return $this->provider->deleteAll();
    }

    /**
     * @param string $title
     * @return int
     * @throws NotFoundException
     */
    public function getOrganisationId($title)
    {
        $key = $this->makeKey(__METHOD__ . '_' . $title);

        return (int)$this->cache->remember($key, $this->ttl, function () use ($title) {
            return $this->provider->getOrganisationId($title);
        });
    }

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     */
    public function storeRelations(OrganisationsCollection $organisations, array $ids)
    {
        return $this->provider->storeRelations($organisations, $ids);
    }
}
