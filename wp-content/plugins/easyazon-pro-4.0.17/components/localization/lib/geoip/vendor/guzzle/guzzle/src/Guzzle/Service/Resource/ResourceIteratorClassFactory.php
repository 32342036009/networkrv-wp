<?php

namespace Guzzle\Service\Resource;

use Guzzle\Inflection\InflectorInterface;
use Guzzle\Inflection\Inflector;
use Guzzle\Service\Command\CommandInterface;






class ResourceIteratorClassFactory extends AbstractResourceIteratorFactory
{

protected $namespaces;


protected $inflector;





public function __construct($namespaces = array(), InflectorInterface $inflector = null)
{
$this->namespaces = (array) $namespaces;
$this->inflector = $inflector ?: Inflector::getDefault();
}








public function registerNamespace($namespace)
{
array_unshift($this->namespaces, $namespace);

return $this;
}

protected function getClassName(CommandInterface $command)
{
$iteratorName = $this->inflector->camel($command->getName()) . 'Iterator';


foreach ($this->namespaces as $namespace) {
$potentialClassName = $namespace . '\\' . $iteratorName;
if (class_exists($potentialClassName)) {
return $potentialClassName;
}
}

return false;
}
}
