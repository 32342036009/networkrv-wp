<?php

namespace Guzzle\Service\Builder;

use Guzzle\Service\Exception\ServiceNotFoundException;







interface ServiceBuilderInterface
{













public function get($name, $throwAway = false);










public function set($key, $service);
}
