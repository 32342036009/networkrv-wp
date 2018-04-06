<?php

namespace Guzzle\Service\Resource;

use Guzzle\Common\AbstractHasDispatcher;
use Guzzle\Service\Command\CommandInterface;

abstract class ResourceIterator extends AbstractHasDispatcher implements ResourceIteratorInterface
{

protected $command;


protected $originalCommand;


protected $resources;


protected $retrievedCount = 0;


protected $iteratedCount = 0;


protected $nextToken = false;


protected $pageSize;


protected $limit;


protected $requestCount = 0;


protected $data = array();


protected $invalid;

public static function getAllEvents()
{
return array(

'resource_iterator.before_send',

'resource_iterator.after_send'
);
}








public function __construct(CommandInterface $command, array $data = array())
{

$this->originalCommand = $command;


$this->data = $data;
$this->limit = array_key_exists('limit', $data) ? $data['limit'] : 0;
$this->pageSize = array_key_exists('page_size', $data) ? $data['page_size'] : false;
}






public function toArray()
{
return iterator_to_array($this, false);
}

public function setLimit($limit)
{
$this->limit = $limit;
$this->resetState();

return $this;
}

public function setPageSize($pageSize)
{
$this->pageSize = $pageSize;
$this->resetState();

return $this;
}








public function get($key)
{
return array_key_exists($key, $this->data) ? $this->data[$key] : null;
}









public function set($key, $value)
{
$this->data[$key] = $value;

return $this;
}

public function current()
{
return $this->resources ? current($this->resources) : false;
}

public function key()
{
return max(0, $this->iteratedCount - 1);
}

public function count()
{
return $this->retrievedCount;
}






public function getRequestCount()
{
return $this->requestCount;
}




public function rewind()
{

$this->command = clone $this->originalCommand;
$this->resetState();
$this->next();
}

public function valid()
{
return !$this->invalid && (!$this->resources || $this->current() || $this->nextToken)
&& (!$this->limit || $this->iteratedCount < $this->limit + 1);
}

public function next()
{
$this->iteratedCount++;


$sendRequest = false;
if (!$this->resources) {
$sendRequest = true;
} else {

$current = next($this->resources);
$sendRequest = $current === false && $this->nextToken && (!$this->limit || $this->iteratedCount < $this->limit + 1);
}

if ($sendRequest) {

$this->dispatch('resource_iterator.before_send', array(
'iterator' => $this,
'resources' => $this->resources
));


$this->command = clone $this->originalCommand;

$this->resources = $this->sendRequest();
$this->requestCount++;



if (empty($this->resources)) {
$this->invalid = true;
} else {

$this->retrievedCount += count($this->resources);

reset($this->resources);
}

$this->dispatch('resource_iterator.after_send', array(
'iterator' => $this,
'resources' => $this->resources
));
}
}






public function getNextToken()
{
return $this->nextToken;
}







protected function calculatePageSize()
{
if ($this->limit && $this->iteratedCount + $this->pageSize > $this->limit) {
return 1 + ($this->limit - $this->iteratedCount);
}

return (int) $this->pageSize;
}




protected function resetState()
{
$this->iteratedCount = 0;
$this->retrievedCount = 0;
$this->nextToken = false;
$this->resources = null;
$this->invalid = false;
}






abstract protected function sendRequest();
}
