<?php

namespace Guzzle\Service\Resource;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\Command\CommandInterface;




class CompositeResourceIteratorFactory implements ResourceIteratorFactoryInterface
{

protected $factories;


public function __construct(array $factories)
{
$this->factories = $factories;
}

public function build(CommandInterface $command, array $options = array())
{
if (!($factory = $this->getFactory($command))) {
throw new InvalidArgumentException('Iterator was not found for ' . $command->getName());
}

return $factory->build($command, $options);
}

public function canBuild(CommandInterface $command)
{
return $this->getFactory($command) !== false;
}








public function addFactory(ResourceIteratorFactoryInterface $factory)
{
$this->factories[] = $factory;

return $this;
}








protected function getFactory(CommandInterface $command)
{
foreach ($this->factories as $factory) {
if ($factory->canBuild($command)) {
return $factory;
}
}

return false;
}
}
