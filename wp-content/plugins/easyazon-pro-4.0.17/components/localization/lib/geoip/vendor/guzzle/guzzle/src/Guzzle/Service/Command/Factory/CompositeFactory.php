<?php

namespace Guzzle\Service\Command\Factory;

use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\ClientInterface;




class CompositeFactory implements \IteratorAggregate, \Countable, FactoryInterface
{

protected $factories;








public static function getDefaultChain(ClientInterface $client)
{
$factories = array();
if ($description = $client->getDescription()) {
$factories[] = new ServiceDescriptionFactory($description);
}
$factories[] = new ConcreteClassFactory($client);

return new self($factories);
}




public function __construct(array $factories = array())
{
$this->factories = $factories;
}









public function add(FactoryInterface $factory, $before = null)
{
$pos = null;

if ($before) {
foreach ($this->factories as $i => $f) {
if ($before instanceof FactoryInterface) {
if ($f === $before) {
$pos = $i;
break;
}
} elseif (is_string($before)) {
if ($f instanceof $before) {
$pos = $i;
break;
}
}
}
}

if ($pos === null) {
$this->factories[] = $factory;
} else {
array_splice($this->factories, $i, 0, array($factory));
}

return $this;
}








public function has($factory)
{
return (bool) $this->find($factory);
}








public function remove($factory = null)
{
if (!($factory instanceof FactoryInterface)) {
$factory = $this->find($factory);
}

$this->factories = array_values(array_filter($this->factories, function($f) use ($factory) {
return $f !== $factory;
}));

return $this;
}








public function find($factory)
{
foreach ($this->factories as $f) {
if ($factory === $f || (is_string($factory) && $f instanceof $factory)) {
return $f;
}
}
}









public function factory($name, array $args = array())
{
foreach ($this->factories as $factory) {
$command = $factory->factory($name, $args);
if ($command) {
return $command;
}
}
}

public function count()
{
return count($this->factories);
}

public function getIterator()
{
return new \ArrayIterator($this->factories);
}
}
