<?php

namespace Guzzle\Service\Command\LocationVisitor\Request;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\Parameter;




class PostFieldVisitor extends AbstractRequestVisitor
{
public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
{
$request->setPostField($param->getWireName(), $this->prepareValue($value, $param));
}
}
