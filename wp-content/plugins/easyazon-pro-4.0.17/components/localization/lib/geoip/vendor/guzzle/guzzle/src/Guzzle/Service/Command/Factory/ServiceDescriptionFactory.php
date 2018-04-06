<?php

namespace Guzzle\Service\Command\Factory;

use Guzzle\Service\Description\ServiceDescriptionInterface;
use Guzzle\Inflection\InflectorInterface;




class ServiceDescriptionFactory implements FactoryInterface
{

protected $description;


protected $inflector;





public function __construct(ServiceDescriptionInterface $description, InflectorInterface $inflector = null)
{
$this->setServiceDescription($description);
$this->inflector = $inflector;
}








public function setServiceDescription(ServiceDescriptionInterface $description)
{
$this->description = $description;

return $this;
}






public function getServiceDescription()
{
return $this->description;
}

public function factory($name, array $args = array())
{
$command = $this->description->getOperation($name);


if (!$command) {
$command = $this->description->getOperation(ucfirst($name));

if (!$command && $this->inflector) {
$command = $this->description->getOperation($this->inflector->snake($name));
}
}

if ($command) {
$class = $command->getClass();
return new $class($args, $command, $this->description);
}
}
}
