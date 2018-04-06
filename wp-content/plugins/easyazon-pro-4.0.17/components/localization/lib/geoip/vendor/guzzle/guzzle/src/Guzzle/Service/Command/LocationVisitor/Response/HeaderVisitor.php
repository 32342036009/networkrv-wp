<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;




class HeaderVisitor extends AbstractResponseVisitor
{
public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
) {
if ($param->getType() == 'object' && $param->getAdditionalProperties() instanceof Parameter) {
$this->processPrefixedHeaders($response, $param, $value);
} else {
$value[$param->getName()] = $param->filter((string) $response->getHeader($param->getWireName()));
}
}








protected function processPrefixedHeaders(Response $response, Parameter $param, &$value)
{

if ($prefix = $param->getSentAs()) {
$container = $param->getName();
$len = strlen($prefix);

foreach ($response->getHeaders()->toArray() as $key => $header) {
if (stripos($key, $prefix) === 0) {

$value[$container][substr($key, $len)] = count($header) == 1 ? end($header) : $header;
}
}
}
}
}
