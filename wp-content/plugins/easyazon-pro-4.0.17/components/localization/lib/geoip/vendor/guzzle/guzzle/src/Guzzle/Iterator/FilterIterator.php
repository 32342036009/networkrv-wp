<?php

namespace Guzzle\Iterator;

use Guzzle\Common\Exception\InvalidArgumentException;






class FilterIterator extends \FilterIterator
{

protected $callback;







public function __construct(\Iterator $iterator, $callback)
{
parent::__construct($iterator);
if (!is_callable($callback)) {
throw new InvalidArgumentException('The callback must be callable');
}
$this->callback = $callback;
}

public function accept()
{
return call_user_func($this->callback, $this->current());
}
}
