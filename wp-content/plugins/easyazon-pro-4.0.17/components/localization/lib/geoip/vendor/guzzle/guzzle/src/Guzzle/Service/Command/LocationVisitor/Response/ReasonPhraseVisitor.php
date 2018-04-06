<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;




class ReasonPhraseVisitor extends AbstractResponseVisitor
{
public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
) {
$value[$param->getName()] = $response->getReasonPhrase();
}
}
