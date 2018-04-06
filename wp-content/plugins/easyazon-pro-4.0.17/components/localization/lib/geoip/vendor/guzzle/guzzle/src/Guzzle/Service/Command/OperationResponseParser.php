<?php

namespace Guzzle\Service\Command;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Command\LocationVisitor\VisitorFlyweight;
use Guzzle\Service\Command\LocationVisitor\Response\ResponseVisitorInterface;
use Guzzle\Service\Description\Parameter;
use Guzzle\Service\Description\OperationInterface;
use Guzzle\Service\Description\Operation;
use Guzzle\Service\Exception\ResponseClassException;
use Guzzle\Service\Resource\Model;




class OperationResponseParser extends DefaultResponseParser
{

protected $factory;


protected static $instance;


private $schemaInModels;





public static function getInstance()
{
if (!static::$instance) {
static::$instance = new static(VisitorFlyweight::getInstance());
}

return static::$instance;
}





public function __construct(VisitorFlyweight $factory, $schemaInModels = false)
{
$this->factory = $factory;
$this->schemaInModels = $schemaInModels;
}









public function addVisitor($location, ResponseVisitorInterface $visitor)
{
$this->factory->addResponseVisitor($location, $visitor);

return $this;
}

protected function handleParsing(CommandInterface $command, Response $response, $contentType)
{
$operation = $command->getOperation();
$type = $operation->getResponseType();
$model = null;

if ($type == OperationInterface::TYPE_MODEL) {
$model = $operation->getServiceDescription()->getModel($operation->getResponseClass());
} elseif ($type == OperationInterface::TYPE_CLASS) {
return $this->parseClass($command);
}

if (!$model) {

return parent::handleParsing($command, $response, $contentType);
} elseif ($command[AbstractCommand::RESPONSE_PROCESSING] != AbstractCommand::TYPE_MODEL) {

return new Model(parent::handleParsing($command, $response, $contentType));
} else {

return new Model($this->visitResult($model, $command, $response), $this->schemaInModels ? $model : null);
}
}









protected function parseClass(CommandInterface $command)
{

$event = new CreateResponseClassEvent(array('command' => $command));
$command->getClient()->getEventDispatcher()->dispatch('command.parse_response', $event);
if ($result = $event->getResult()) {
return $result;
}

$className = $command->getOperation()->getResponseClass();
if (!method_exists($className, 'fromCommand')) {
throw new ResponseClassException("{$className} must exist and implement a static fromCommand() method");
}

return $className::fromCommand($command);
}










protected function visitResult(Parameter $model, CommandInterface $command, Response $response)
{
$foundVisitors = $result = $knownProps = array();
$props = $model->getProperties();

foreach ($props as $schema) {
if ($location = $schema->getLocation()) {

if (!isset($foundVisitors[$location])) {
$foundVisitors[$location] = $this->factory->getResponseVisitor($location);
$foundVisitors[$location]->before($command, $result);
}
}
}


if (($additional = $model->getAdditionalProperties()) instanceof Parameter) {
$this->visitAdditionalProperties($model, $command, $response, $additional, $result, $foundVisitors);
}


foreach ($props as $schema) {
$knownProps[$schema->getName()] = 1;
if ($location = $schema->getLocation()) {
$foundVisitors[$location]->visit($command, $response, $schema, $result);
}
}


if ($additional === false) {
$result = array_intersect_key($result, $knownProps);
}


foreach ($foundVisitors as $visitor) {
$visitor->after($command);
}

return $result;
}

protected function visitAdditionalProperties(
Parameter $model,
CommandInterface $command,
Response $response,
Parameter $additional,
&$result,
array &$foundVisitors
) {

if ($location = $additional->getLocation()) {
if (!isset($foundVisitors[$location])) {
$foundVisitors[$location] = $this->factory->getResponseVisitor($location);
$foundVisitors[$location]->before($command, $result);
}

if (is_array($result)) {

foreach (array_keys($result) as $key) {

if (!$model->getProperty($key)) {

$additional->setName($key);
$foundVisitors[$location]->visit($command, $response, $additional, $result);
}
}

$additional->setName(null);
}
}
}
}
