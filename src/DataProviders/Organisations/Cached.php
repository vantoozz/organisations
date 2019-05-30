<?php

namespace App\DataProviders\Organisations;

use App\Collections\OrganisationsCollection;
use App\Exceptions\NotFoundException;
use Illuminate\Cache\TaggedCache;

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
     * @var TaggedCache
     */
    private $cache;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Cached constructor.
     * @param OrganisationsDataProviderInterface $provider
     * @param TaggedCache $cache
     * @param $ttl
     */
    public function __construct(OrganisationsDataProviderInterface $provider, TaggedCache $cache, $ttl)
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
                continue;
            }
            $notCached[] = $title;
        }

        if (0 === count($notCached)) {
            return $data;
        }

        foreach ($this->provider->fetchIdsByTitles($notCached) as $title => $id) {
            $key = $this->makeKey(__METHOD__ . '_' . $title);
            $this->cache->put($key, $id, $this->ttl);
            $data[$title] = (int)$id;
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

        $key = $this->makeKey(__METHOD__ . '_' . $id . '_' . $limit . '_' . $offset);

        $data = $this->cache->get($key, null);
        if (null !== $data) {
            return $data;
        }

        $data = $this->provider->getOrganisationRelations($id, $limit, $offset);
        $this->cache->put($key, $data, $this->ttl);

        return $data;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getOrganisationRelationsCount($id)
    {
        $key = $this->makeKey(__METHOD__ . '_' . $id);

        $count = $this->cache->get($key, null);
        if (null !== $count) {
            return $count;
        }

        $count = $this->provider->getOrganisationRelationsCount($id);
        $this->cache->put($key, $count, $this->ttl);

        return (int)$count;
    }

    /**
     * @return mixed
     */
    public function deleteAll()
    {
        $this->cache->flush();
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

        $id = $this->cache->get($key, null);
        if (null !== $id) {
            return $id;
        }

        $id = $this->provider->getOrganisationId($title);
        $this->cache->put($key, $id, $this->ttl);

        return (int)$id;
    }

    /**
     * @param OrganisationsCollection $organisations
     * @param array $ids
     */
    public function storeRelations(OrganisationsCollection $organisations, array $ids)
    {
        $this->cache->flush();
        return $this->provider->storeRelations($organisations, $ids);
    }
}
