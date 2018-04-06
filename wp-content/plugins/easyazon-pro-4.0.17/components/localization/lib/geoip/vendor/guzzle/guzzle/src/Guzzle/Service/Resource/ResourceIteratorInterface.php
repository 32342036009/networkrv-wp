<?php

namespace Guzzle\Service\Resource;

use Guzzle\Common\HasDispatcherInterface;
use Guzzle\Common\ToArrayInterface;




interface ResourceIteratorInterface extends ToArrayInterface, HasDispatcherInterface, \Iterator, \Countable
{





public function getNextToken();










public function setLimit($limit);











public function setPageSize($pageSize);








public function get($key);









public function set($key, $value);
}
