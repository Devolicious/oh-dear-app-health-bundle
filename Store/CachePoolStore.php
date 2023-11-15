<?php

declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Store;

use Symfony\Component\Cache\Adapter\AdapterInterface;

final class CachePoolStore implements ResultStore
{
    public function __construct(
        private readonly AdapterInterface $cache,
    ) {
    }

    public function save(string $identifier, StoredResult $result): void
    {
        $this->cache->save(
            $this->cache->getItem($identifier)->set($result)
        );
    }

    public function fetchLastResult(string $identifier): ?StoredResult
    {
        $cachedItem = $this->cache->getItem($identifier);
        if (false === $cachedItem->isHit()) {
            return null;
        }

        return $cachedItem->get();
    }
}
