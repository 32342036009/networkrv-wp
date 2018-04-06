<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;




class XmlVisitor extends AbstractResponseVisitor
{
public function before(CommandInterface $command, array &$result)
{

$result = json_decode(json_encode($command->getResponse()->xml()), true);
}

public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
) {
$sentAs = $param->getWireName();
$name = $param->getName();
if (isset($value[$sentAs])) {
$this->recursiveProcess($param, $value[$sentAs]);
if ($name != $sentAs) {
$value[$name] = $value[$sentAs];
unset($value[$sentAs]);
}
}
}







protected function recursiveProcess(Parameter $param, &$value)
{
$type = $param->getType();

if (!is_array($value)) {
if ($type == 'array') {

$this->recursiveProcess($param->getItems(), $value);
$value = array($value);
}
} elseif ($type == 'object') {
$this->processObject($param, $value);
} elseif ($type == 'array') {
$this->processArray($param, $value);
} elseif ($type == 'string' && gettype($value) == 'array') {
$value = '';
}

if ($value !== null) {
$value = $param->filter($value);
}
}







protected function processArray(Parameter $param, &$value)
{

if (!isset($value[0])) {





if ($param->getItems() && isset($value[$param->getItems()->getWireName()])) {

$value = $value[$param->getItems()->getWireName()];

if (!isset($value[0]) || !is_array($value)) {
$value = array($value);
}
} elseif (!empty($value)) {


$value = array($value);
}
}

foreach ($value as &$item) {
$this->recursiveProcess($param->getItems(), $item);
}
}







protected function processObject(Parameter $param, &$value)
{

if (!isset($value[0]) && ($properties = $param->getProperties())) {
$knownProperties = array();
foreach ($properties as $property) {
$name = $property->getName();
$sentAs = $property->getWireName();
$knownProperties[$name] = 1;
if ($property->getData('xmlAttribute')) {
$this->processXmlAttribute($property, $value);
} elseif (isset($value[$sentAs])) {
$this->recursiveProcess($property, $value[$sentAs]);
if ($name != $sentAs) {
$value[$name] = $value[$sentAs];
unset($value[$sentAs]);
}
}
}


if ($param->getAdditionalProperties() === false) {
$value = array_intersect_key($value, $knownProperties);
}
}
}







protected function processXmlAttribute(Parameter $property, array &$value)
{
$sentAs = $property->getWireName();
if (isset($value['@attributes'][$sentAs])) {
$value[$property->getName()] = $value['@attributes'][$sentAs];
unset($value['@attributes'][$sentAs]);
if (empty($value['@attributes'])) {
unset($value['@attributes']);
}
}
}
}
