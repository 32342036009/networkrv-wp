<?php

namespace Guzzle\Service\Command;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;




interface RequestSerializerInterface
{







public function prepare(CommandInterface $command);
}
