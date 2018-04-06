<?php

namespace Guzzle\Service\Command\Factory;

use Guzzle\Service\Command\CommandInterface;




interface FactoryInterface
{








public function factory($name, array $args = array());
}
