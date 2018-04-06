<?php

namespace Guzzle\Service\Command;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Common\Exception\UnexpectedValueException;
use Guzzle\Http\Message\RequestInterface;






class ClosureCommand extends AbstractCommand
{




protected function init()
{
if (!$this['closure']) {
throw new InvalidArgumentException('A closure must be passed in the parameters array');
}
}





protected function build()
{
$closure = $this['closure'];

$this->request = $closure($this, $this->operation);

if (!$this->request || !$this->request instanceof RequestInterface) {
throw new UnexpectedValueException('Closure command did not return a RequestInterface object');
}
}
}
