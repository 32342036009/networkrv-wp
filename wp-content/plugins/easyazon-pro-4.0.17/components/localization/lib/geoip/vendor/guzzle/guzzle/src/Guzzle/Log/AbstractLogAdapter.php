<?php

namespace Guzzle\Log;




abstract class AbstractLogAdapter implements LogAdapterInterface
{
protected $log;

public function getLogObject()
{
return $this->log;
}
}
