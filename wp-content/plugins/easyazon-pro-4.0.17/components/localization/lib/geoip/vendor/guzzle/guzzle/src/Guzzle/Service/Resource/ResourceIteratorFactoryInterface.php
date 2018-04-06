<?php

namespace Guzzle\Service\Resource;

use Guzzle\Service\Command\CommandInterface;




interface ResourceIteratorFactoryInterface
{








public function build(CommandInterface $command, array $options = array());








public function canBuild(CommandInterface $command);
}
