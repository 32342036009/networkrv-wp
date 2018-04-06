<?php

namespace Guzzle\Cache;




abstract class AbstractCacheAdapter implements CacheAdapterInterface
{
protected $cache;






public function getCacheObject()
{
return $this->cache;
}
}
