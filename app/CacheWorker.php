<?php

use Doctrine\Common\Cache\Cache;

class CacheWorker
{
    /** @var Cache */
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }
}
