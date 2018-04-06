<?php

namespace Guzzle\Log;




interface LogAdapterInterface
{







public function log($message, $priority = LOG_INFO, $extras = array());
}
