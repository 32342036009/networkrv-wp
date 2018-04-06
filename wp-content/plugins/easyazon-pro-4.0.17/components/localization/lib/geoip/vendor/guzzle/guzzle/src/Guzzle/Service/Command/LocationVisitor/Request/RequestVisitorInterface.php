<?php

namespace Guzzle\Service\Command\LocationVisitor\Request;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;




interface RequestVisitorInterface
{






public function after(CommandInterface $command, RequestInterface $request);









public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value);
}
