<?php

namespace Guzzle\Service\Exception;

use Guzzle\Http\Exception\MultiTransferException;
use Guzzle\Service\Command\CommandInterface;




class CommandTransferException extends MultiTransferException
{
protected $successfulCommands = array();
protected $failedCommands = array();








public static function fromMultiTransferException(MultiTransferException $e)
{
$ce = new self($e->getMessage(), $e->getCode(), $e->getPrevious());
$ce->setSuccessfulRequests($e->getSuccessfulRequests());

$alreadyAddedExceptions = array();
foreach ($e->getFailedRequests() as $request) {
if ($re = $e->getExceptionForFailedRequest($request)) {
$alreadyAddedExceptions[] = $re;
$ce->addFailedRequestWithException($request, $re);
} else {
$ce->addFailedRequest($request);
}
}


if (count($alreadyAddedExceptions) < count($e)) {
foreach ($e as $ex) {
if (!in_array($ex, $alreadyAddedExceptions)) {
$ce->add($ex);
}
}
}

return $ce;
}






public function getAllCommands()
{
return array_merge($this->successfulCommands, $this->failedCommands);
}








public function addSuccessfulCommand(CommandInterface $command)
{
$this->successfulCommands[] = $command;

return $this;
}








public function addFailedCommand(CommandInterface $command)
{
$this->failedCommands[] = $command;

return $this;
}






public function getSuccessfulCommands()
{
return $this->successfulCommands;
}






public function getFailedCommands()
{
return $this->failedCommands;
}








public function getExceptionForFailedCommand(CommandInterface $command)
{
return $this->getExceptionForFailedRequest($command->getRequest());
}
}
