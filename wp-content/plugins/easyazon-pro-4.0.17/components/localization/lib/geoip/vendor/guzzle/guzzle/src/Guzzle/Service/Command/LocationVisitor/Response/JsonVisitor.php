<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;









class JsonVisitor extends AbstractResponseVisitor
{
public function before(CommandInterface $command, array &$result)
{

$result = $command->getResponse()->json();
}

public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
) {
$name = $param->getName();
$key = $param->getWireName();
if (isset($value[$key])) {
$this->recursiveProcess($param, $value[$key]);
if ($key != $name) {
$value[$name] = $value[$key];
unset($value[$key]);
}
}
}







protected function recursiveProcess(Parameter $param, &$value)
{
if ($value === null) {
return;
}

if (is_array($value)) {
$type = $param->getType();
if ($type == 'array') {
foreach ($value as &$item) {
$this->recursiveProcess($param->getItems(), $item);
}
} elseif ($type == 'object' && !isset($value[0])) {

$knownProperties = array();
if ($properties = $param->getProperties()) {
foreach ($properties as $property) {
$name = $property->getName();
$key = $property->getWireName();
$knownProperties[$name] = 1;
if (isset($value[$key])) {
$this->recursiveProcess($property, $value[$key]);
if ($key != $name) {
$value[$name] = $value[$key];
unset($value[$key]);
}
}
}
}


if ($param->getAdditionalProperties() === false) {
$value = array_intersect_key($value, $knownProperties);
} elseif (($additional = $param->getAdditionalProperties()) !== true) {

foreach ($value as &$v) {
$this->recursiveProcess($additional, $v);
}
}
}
}

$value = $param->filter($value);
}
}
