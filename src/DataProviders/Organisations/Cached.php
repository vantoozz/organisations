<?php

namespace App\DataProviders\Organisations;

use Illuminate\Contracts\Cache\Repository;

/**
 * Class Cached
 * @package App\DataProviders\Organisations
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
            $key = $this->makeKey($title);
            if ($this->cache->has($key)) {
                $data[$title] = $this->cache->get($key);
            }
            $notCached[] = $title;
        }

        if (0 === count($notCached)) {
            return $data;
        }

        foreach ($this->provider->fetchIdsByTitles($notCached) as $title => $id) {
            $key = $this->makeKey($title);
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
}
