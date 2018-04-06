<?php

namespace Guzzle\Service\Command;

use Guzzle\Common\Event;




class CreateResponseClassEvent extends Event
{





public function setResult($result)
{
$this['result'] = $result;
$this->stopPropagation();
}






public function getResult()
{
return $this['result'];
}
}
