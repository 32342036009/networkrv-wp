<?php

namespace Guzzle\Iterator;

use Guzzle\Common\Exception\InvalidArgumentException;




class MapIterator extends \IteratorIterator
{

protected $callback;







public function __construct(\Traversable $iterator, $callback)
{
parent::__construct($iterator);
if (!is_callable($callback)) {
throw new InvalidArgumentException('The callback must be callable');
}
$this->callback = $callback;
}

public function current()
{
return call_user_func($this->callback, parent::current());
}
}
