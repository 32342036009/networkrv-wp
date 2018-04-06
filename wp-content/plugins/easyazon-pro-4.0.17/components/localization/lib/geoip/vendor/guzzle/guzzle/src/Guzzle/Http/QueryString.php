<?php

namespace Guzzle\Http;

use Guzzle\Common\Collection;
use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\QueryAggregator\DuplicateAggregator;
use Guzzle\Http\QueryAggregator\QueryAggregatorInterface;
use Guzzle\Http\QueryAggregator\PhpAggregator;




class QueryString extends Collection
{

const RFC_3986 = 'RFC 3986';


const FORM_URLENCODED = 'application/x-www-form-urlencoded';


const BLANK = "_guzzle_blank_";


protected $fieldSeparator = '&';


protected $valueSeparator = '=';


protected $urlEncode = 'RFC 3986';


protected $aggregator;


private static $defaultAggregator = null;








public static function fromString($query)
{
$q = new static();
if ($query === '') {
return $q;
}

$foundDuplicates = $foundPhpStyle = false;

foreach (explode('&', $query) as $kvp) {
$parts = explode('=', $kvp, 2);
$key = rawurldecode($parts[0]);
if ($paramIsPhpStyleArray = substr($key, -2) == '[]') {
$foundPhpStyle = true;
$key = substr($key, 0, -2);
}
if (isset($parts[1])) {
$value = rawurldecode(str_replace('+', '%20', $parts[1]));
if (isset($q[$key])) {
$q->add($key, $value);
$foundDuplicates = true;
} elseif ($paramIsPhpStyleArray) {
$q[$key] = array($value);
} else {
$q[$key] = $value;
}
} else {

$q->add($key, false);
}
}


if ($foundDuplicates && !$foundPhpStyle) {
$q->setAggregator(new DuplicateAggregator());
}

return $q;
}







public function __toString()
{
if (!$this->data) {
return '';
}

$queryList = array();
foreach ($this->prepareData($this->data) as $name => $value) {
$queryList[] = $this->convertKvp($name, $value);
}

return implode($this->fieldSeparator, $queryList);
}






public function getFieldSeparator()
{
return $this->fieldSeparator;
}






public function getValueSeparator()
{
return $this->valueSeparator;
}








public function getUrlEncoding()
{
return $this->urlEncode;
}






public function isUrlEncoding()
{
return $this->urlEncode !== false;
}












public function setAggregator(QueryAggregatorInterface $aggregator = null)
{

if (!$aggregator) {
if (!self::$defaultAggregator) {
self::$defaultAggregator = new PhpAggregator();
}
$aggregator = self::$defaultAggregator;
}

$this->aggregator = $aggregator;

return $this;
}








public function useUrlEncoding($encode)
{
$this->urlEncode = ($encode === true) ? self::RFC_3986 : $encode;

return $this;
}








public function setFieldSeparator($separator)
{
$this->fieldSeparator = $separator;

return $this;
}








public function setValueSeparator($separator)
{
$this->valueSeparator = $separator;

return $this;
}






public function urlEncode()
{
return $this->prepareData($this->data);
}








public function encodeValue($value)
{
if ($this->urlEncode == self::RFC_3986) {
return rawurlencode($value);
} elseif ($this->urlEncode == self::FORM_URLENCODED) {
return urlencode($value);
} else {
return (string) $value;
}
}








protected function prepareData(array $data)
{

if (!$this->aggregator) {
$this->setAggregator(null);
}

$temp = array();
foreach ($data as $key => $value) {
if ($value === false || $value === null) {

$temp[$this->encodeValue($key)] = $value;
} elseif (is_array($value)) {
$temp = array_merge($temp, $this->aggregator->aggregate($key, $value, $this));
} else {
$temp[$this->encodeValue($key)] = $this->encodeValue($value);
}
}

return $temp;
}









private function convertKvp($name, $value)
{
if ($value === self::BLANK || $value === null || $value === false) {
return $name;
} elseif (!is_array($value)) {
return $name . $this->valueSeparator . $value;
}

$result = '';
foreach ($value as $v) {
$result .= $this->convertKvp($name, $v) . $this->fieldSeparator;
}

return rtrim($result, $this->fieldSeparator);
}
}
