<?php

namespace Guzzle\Service\Description;

use Guzzle\Common\ToArrayInterface;




class SchemaValidator implements ValidatorInterface
{

protected static $instance;


protected $castIntegerToStringType;


protected $errors;





public static function getInstance()
{
if (!self::$instance) {
self::$instance = new self();
}

return self::$instance;
}





public function __construct($castIntegerToStringType = true)
{
$this->castIntegerToStringType = $castIntegerToStringType;
}

public function validate(Parameter $param, &$value)
{
$this->errors = array();
$this->recursiveProcess($param, $value);

if (empty($this->errors)) {
return true;
} else {
sort($this->errors);
return false;
}
}






public function getErrors()
{
return $this->errors ?: array();
}











protected function recursiveProcess(Parameter $param, &$value, $path = '', $depth = 0)
{

$value = $param->getValue($value);

$required = $param->getRequired();

if ((null === $value && !$required) || $param->getStatic()) {
return true;
}

$type = $param->getType();

$valueIsArray = is_array($value);

if ($name = $param->getName()) {
$path .= "[{$name}]";
}

if ($type == 'object') {


if ($param->getInstanceOf()) {
$instance = $param->getInstanceOf();
if (!($value instanceof $instance)) {
$this->errors[] = "{$path} must be an instance of {$instance}";
return false;
}
}


$traverse = $temporaryValue = false;


if (!$valueIsArray && $value instanceof ToArrayInterface) {
$value = $value->toArray();
}

if ($valueIsArray) {

if (isset($value[0])) {
$this->errors[] = "{$path} must be an array of properties. Got a numerically indexed array.";
return false;
}
$traverse = true;
} elseif ($value === null) {

$value = array();
$temporaryValue = $valueIsArray = $traverse = true;
}

if ($traverse) {

if ($properties = $param->getProperties()) {

foreach ($properties as $property) {
$name = $property->getName();
if (isset($value[$name])) {
$this->recursiveProcess($property, $value[$name], $path, $depth + 1);
} else {
$current = null;
$this->recursiveProcess($property, $current, $path, $depth + 1);

if (null !== $current) {
$value[$name] = $current;
}
}
}
}

$additional = $param->getAdditionalProperties();
if ($additional !== true) {

$keys = array_keys($value);

$diff = array_diff($keys, array_keys($properties));
if (!empty($diff)) {

if ($additional instanceOf Parameter) {
foreach ($diff as $key) {
$this->recursiveProcess($additional, $value[$key], "{$path}[{$key}]", $depth);
}
} else {

foreach ($diff as $prop) {
$this->errors[] = sprintf('%s[%s] is not an allowed property', $path, $prop);
}
}
}
}




if ($temporaryValue && empty($value)) {
$value = null;
$valueIsArray = false;
}
}

} elseif ($type == 'array' && $valueIsArray && $param->getItems()) {
foreach ($value as $i => &$item) {

$this->recursiveProcess($param->getItems(), $item, $path . "[{$i}]", $depth + 1);
}
}


if ($required && $value === null && $type != 'null') {
$message = "{$path} is " . ($param->getType() ? ('a required ' . implode(' or ', (array) $param->getType())) : 'required');
if ($param->getDescription()) {
$message .= ': ' . $param->getDescription();
}
$this->errors[] = $message;
return false;
}



if ($type && (!$type = $this->determineType($type, $value))) {
if ($this->castIntegerToStringType && $param->getType() == 'string' && is_integer($value)) {
$value = (string) $value;
} else {
$this->errors[] = "{$path} must be of type " . implode(' or ', (array) $param->getType());
}
}


if ($type == 'string') {


if (($enum = $param->getEnum()) && !in_array($value, $enum)) {
$this->errors[] = "{$path} must be one of " . implode(' or ', array_map(function ($s) {
return '"' . addslashes($s) . '"';
}, $enum));
}

if (($pattern = $param->getPattern()) && !preg_match($pattern, $value)) {
$this->errors[] = "{$path} must match the following regular expression: {$pattern}";
}

$strLen = null;
if ($min = $param->getMinLength()) {
$strLen = strlen($value);
if ($strLen < $min) {
$this->errors[] = "{$path} length must be greater than or equal to {$min}";
}
}
if ($max = $param->getMaxLength()) {
if (($strLen ?: strlen($value)) > $max) {
$this->errors[] = "{$path} length must be less than or equal to {$max}";
}
}

} elseif ($type == 'array') {

$size = null;
if ($min = $param->getMinItems()) {
$size = count($value);
if ($size < $min) {
$this->errors[] = "{$path} must contain {$min} or more elements";
}
}
if ($max = $param->getMaxItems()) {
if (($size ?: count($value)) > $max) {
$this->errors[] = "{$path} must contain {$max} or fewer elements";
}
}

} elseif ($type == 'integer' || $type == 'number' || $type == 'numeric') {
if (($min = $param->getMinimum()) && $value < $min) {
$this->errors[] = "{$path} must be greater than or equal to {$min}";
}
if (($max = $param->getMaximum()) && $value > $max) {
$this->errors[] = "{$path} must be less than or equal to {$max}";
}
}

return empty($this->errors);
}









protected function determineType($type, $value)
{
foreach ((array) $type as $t) {
if ($t == 'string' && (is_string($value) || (is_object($value) && method_exists($value, '__toString')))) {
return 'string';
} elseif ($t == 'object' && (is_array($value) || is_object($value))) {
return 'object';
} elseif ($t == 'array' && is_array($value)) {
return 'array';
} elseif ($t == 'integer' && is_integer($value)) {
return 'integer';
} elseif ($t == 'boolean' && is_bool($value)) {
return 'boolean';
} elseif ($t == 'number' && is_numeric($value)) {
return 'number';
} elseif ($t == 'numeric' && is_numeric($value)) {
return 'numeric';
} elseif ($t == 'null' && !$value) {
return 'null';
} elseif ($t == 'any') {
return 'any';
}
}

return false;
}
}
