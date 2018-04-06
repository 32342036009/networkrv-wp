<?php

namespace Guzzle\Service;

use Guzzle\Common\Collection;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Common\Exception\BadMethodCallException;
use Guzzle\Common\Version;
use Guzzle\Inflection\InflectorInterface;
use Guzzle\Inflection\Inflector;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\MultiTransferException;
use Guzzle\Service\Exception\CommandTransferException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\Factory\CompositeFactory;
use Guzzle\Service\Command\Factory\FactoryInterface as CommandFactoryInterface;
use Guzzle\Service\Resource\ResourceIteratorClassFactory;
use Guzzle\Service\Resource\ResourceIteratorFactoryInterface;
use Guzzle\Service\Description\ServiceDescriptionInterface;




class Client extends HttpClient implements ClientInterface
{
const COMMAND_PARAMS = 'command.params';


protected $serviceDescription;


protected $commandFactory;


protected $resourceIteratorFactory;


protected $inflector;








public static function factory($config = array())
{
return new static(isset($config['base_url']) ? $config['base_url'] : null, $config);
}

public static function getAllEvents()
{
return array_merge(HttpClient::getAllEvents(), array(
'client.command.create',
'command.before_prepare',
'command.after_prepare',
'command.before_send',
'command.after_send',
'command.parse_response'
));
}










public function __call($method, $args)
{
return $this->getCommand($method, isset($args[0]) ? $args[0] : array())->getResult();
}

public function getCommand($name, array $args = array())
{

if ($options = $this->getConfig(self::COMMAND_PARAMS)) {
$args += $options;
}

if (!($command = $this->getCommandFactory()->factory($name, $args))) {
throw new InvalidArgumentException("Command was not found matching {$name}");
}

$command->setClient($this);
$this->dispatch('client.command.create', array('client' => $this, 'command' => $command));

return $command;
}








public function setCommandFactory(CommandFactoryInterface $factory)
{
$this->commandFactory = $factory;

return $this;
}








public function setResourceIteratorFactory(ResourceIteratorFactoryInterface $factory)
{
$this->resourceIteratorFactory = $factory;

return $this;
}

public function getIterator($command, array $commandOptions = null, array $iteratorOptions = array())
{
if (!($command instanceof CommandInterface)) {
$command = $this->getCommand($command, $commandOptions ?: array());
}

return $this->getResourceIteratorFactory()->build($command, $iteratorOptions);
}

public function execute($command)
{
if ($command instanceof CommandInterface) {
$this->send($this->prepareCommand($command));
$this->dispatch('command.after_send', array('command' => $command));
return $command->getResult();
} elseif (is_array($command) || $command instanceof \Traversable) {
return $this->executeMultiple($command);
} else {
throw new InvalidArgumentException('Command must be a command or array of commands');
}
}

public function setDescription(ServiceDescriptionInterface $service)
{
$this->serviceDescription = $service;

if ($this->getCommandFactory() && $this->getCommandFactory() instanceof CompositeFactory) {
$this->commandFactory->add(new Command\Factory\ServiceDescriptionFactory($service));
}


if ($baseUrl = $service->getBaseUrl()) {
$this->setBaseUrl($baseUrl);
}

return $this;
}

public function getDescription()
{
return $this->serviceDescription;
}








public function setInflector(InflectorInterface $inflector)
{
$this->inflector = $inflector;

return $this;
}






public function getInflector()
{
if (!$this->inflector) {
$this->inflector = Inflector::getDefault();
}

return $this->inflector;
}








protected function prepareCommand(CommandInterface $command)
{

$request = $command->setClient($this)->prepare();

$request->setState(RequestInterface::STATE_NEW);
$this->dispatch('command.before_send', array('command' => $command));

return $request;
}









protected function executeMultiple($commands)
{
$requests = array();
$commandRequests = new \SplObjectStorage();

foreach ($commands as $command) {
$request = $this->prepareCommand($command);
$commandRequests[$request] = $command;
$requests[] = $request;
}

try {
$this->send($requests);
foreach ($commands as $command) {
$this->dispatch('command.after_send', array('command' => $command));
}
return $commands;
} catch (MultiTransferException $failureException) {

$e = CommandTransferException::fromMultiTransferException($failureException);


foreach ($failureException->getFailedRequests() as $request) {
if (isset($commandRequests[$request])) {
$e->addFailedCommand($commandRequests[$request]);
unset($commandRequests[$request]);
}
}


foreach ($commandRequests as $success) {
$e->addSuccessfulCommand($commandRequests[$success]);
$this->dispatch('command.after_send', array('command' => $commandRequests[$success]));
}

throw $e;
}
}

protected function getResourceIteratorFactory()
{
if (!$this->resourceIteratorFactory) {

$clientClass = get_class($this);
$prefix = substr($clientClass, 0, strrpos($clientClass, '\\'));
$this->resourceIteratorFactory = new ResourceIteratorClassFactory(array(
"{$prefix}\\Iterator",
"{$prefix}\\Model"
));
}

return $this->resourceIteratorFactory;
}






protected function getCommandFactory()
{
if (!$this->commandFactory) {
$this->commandFactory = CompositeFactory::getDefaultChain($this);
}

return $this->commandFactory;
}





public function enableMagicMethods($isEnabled)
{
Version::warn(__METHOD__ . ' is deprecated');
}
}
