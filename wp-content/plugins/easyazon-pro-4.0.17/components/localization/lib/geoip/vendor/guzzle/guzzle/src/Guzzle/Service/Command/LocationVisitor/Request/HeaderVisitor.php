<?php

namespace Guzzle\Service\Command\LocationVisitor\Request;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\Parameter;




class HeaderVisitor extends AbstractRequestVisitor
{
public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
{
$value = $param->filter($value);
if ($param->getType() == 'object' && $param->getAdditionalProperties() instanceof Parameter) {
$this->addPrefixedHeaders($request, $param, $value);
} else {
$request->setHeader($param->getWireName(), $value);
}
}










protected function addPrefixedHeaders(RequestInterface $request, Parameter $param, $value)
{
if (!is_array($value)) {
throw new InvalidArgumentException('An array of mapped headers expected, but received a single value');
}
$prefix = $param->getSentAs();
foreach ($value as $headerName => $headerValue) {
$request->setHeader($prefix . $headerName, $headerValue);
}
}
}
