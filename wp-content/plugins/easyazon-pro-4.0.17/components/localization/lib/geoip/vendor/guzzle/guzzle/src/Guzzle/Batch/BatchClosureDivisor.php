<?php

namespace Guzzle\Batch;

use Guzzle\Common\Exception\InvalidArgumentException;




class BatchClosureDivisor implements BatchDivisorInterface
{

protected $callable;


protected $context;








public function __construct($callable, $context = null)
{
if (!is_callable($callable)) {
throw new InvalidArgumentException('Must pass a callable');
}

$this->callable = $callable;
$this->context = $context;
}

public function createBatches(\SplQueue $queue)
{
return call_user_func($this->callable, $queue, $this->context);
}
}
