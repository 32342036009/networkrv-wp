<?php

namespace Guzzle\Cache;

use Doctrine\Common\Cache\Cache;






class DoctrineCacheAdapter extends AbstractCacheAdapter
{



public function __construct(Cache $cache)
{
$this->cache = $cache;
}

public function contains($id, array $options = null)
{
return $this->cache->contains($id);
}

public function delete($id, array $options = null)
{
return $this->cache->delete($id);
}

public function fetch($id, array $options = null)
{
return $this->cache->fetch($id);
}

public function save($id, $data, $lifeTime = false, array $options = null)
{
return $this->cache->save($id, $data, $lifeTime);
}
}
