<?php

namespace Guzzle\Cache;








interface CacheAdapterInterface
{








public function contains($id, array $options = null);









public function delete($id, array $options = null);









public function fetch($id, array $options = null);











public function save($id, $data, $lifeTime = false, array $options = null);
}
