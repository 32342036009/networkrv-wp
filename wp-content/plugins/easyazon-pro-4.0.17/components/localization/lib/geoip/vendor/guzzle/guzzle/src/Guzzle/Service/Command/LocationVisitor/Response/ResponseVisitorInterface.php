<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Command\CommandInterface;




interface ResponseVisitorInterface
{







public function before(CommandInterface $command, array &$result);






public function after(CommandInterface $command);










public function visit(
CommandInterface $command,
Response $response,
Parameter $param,
&$value,
$context = null
);
}
