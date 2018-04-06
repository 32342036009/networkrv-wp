<?php

namespace Guzzle\Service;

use Guzzle\Cache\CacheAdapterInterface;




class CachingConfigLoader implements ConfigLoaderInterface
{

protected $loader;


protected $cache;





public function __construct(ConfigLoaderInterface $loader, CacheAdapterInterface $cache)
{
$this->loader = $loader;
$this->cache = $cache;
}

public function load($config, array $options = array())
{
if (!is_string($config)) {
$key = false;
} else {
$key = 'loader_' . crc32($config);
if ($result = $this->cache->fetch($key)) {
return $result;
}
}

$result = $this->loader->load($config, $options);
if ($key) {
$this->cache->save($key, $result);
}

return $result;
}
}
