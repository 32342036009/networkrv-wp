<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Service\Command\CommandInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;




class BodyVisitor extends AbstractResponseVisitor
{
public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
) {
$value[$param->getName()] = $param->filter($response->getBody());
}
}
