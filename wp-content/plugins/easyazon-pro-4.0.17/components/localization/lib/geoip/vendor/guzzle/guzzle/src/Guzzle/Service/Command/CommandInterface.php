<?php

namespace Guzzle\Service\Command;

use Guzzle\Common\Collection;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Exception\CommandException;
use Guzzle\Service\Description\OperationInterface;
use Guzzle\Service\ClientInterface;
use Guzzle\Common\ToArrayInterface;




interface CommandInterface extends \ArrayAccess, ToArrayInterface
{





public function getName();






public function getOperation();







public function execute();






public function getClient();








public function setClient(ClientInterface $client);







public function getRequest();







public function getResponse();







public function getResult();








public function setResult($result);






public function isPrepared();






public function isExecuted();







public function prepare();






public function getRequestHeaders();









public function setOnComplete($callable);
}
