<?php

namespace Guzzle\Batch;

use Guzzle\Batch\Exception\BatchTransferException;





class ExceptionBufferingBatch extends AbstractBatchDecorator
{

protected $exceptions = array();

public function flush()
{
$items = array();

while (!$this->decoratedBatch->isEmpty()) {
try {
$transferredItems = $this->decoratedBatch->flush();
} catch (BatchTransferException $e) {
$this->exceptions[] = $e;
$transferredItems = $e->getTransferredItems();
}
$items = array_merge($items, $transferredItems);
}

return $items;
}






public function getExceptions()
{
return $this->exceptions;
}




public function clearExceptions()
{
$this->exceptions = array();
}
}
