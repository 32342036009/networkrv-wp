<?php

namespace Guzzle\Log;

use Zend\Log\Logger;




class Zf2LogAdapter extends AbstractLogAdapter
{
public function __construct(Logger $logObject)
{
$this->log = $logObject;
}

public function log($message, $priority = LOG_INFO, $extras = array())
{
$this->log->log($priority, $message, $extras);
}
}
