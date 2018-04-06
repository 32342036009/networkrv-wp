<?php

namespace Guzzle\Batch;

use Guzzle\Common\Exception\InvalidArgumentException;





class BatchClosureTransfer implements BatchTransferInterface
{

protected $callable;


protected $context;








public function __construct($callable, $context = null)
{
if (!is_callable($callable)) {
throw new InvalidArgumentException('Argument must be callable');
}

$this->callable = $callable;
$this->context = $context;
}

public function transfer(array $batch)
{
return empty($batch) ? null : call_user_func($this->callable, $batch, $this->context);
}
}
