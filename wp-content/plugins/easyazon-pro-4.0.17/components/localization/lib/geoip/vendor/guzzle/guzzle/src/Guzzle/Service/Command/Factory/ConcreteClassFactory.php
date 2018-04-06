<?php

namespace Guzzle\Service\Command\Factory;

use Guzzle\Inflection\InflectorInterface;
use Guzzle\Inflection\Inflector;
use Guzzle\Service\ClientInterface;




class ConcreteClassFactory implements FactoryInterface
{

protected $client;


protected $inflector;





public function __construct(ClientInterface $client, InflectorInterface $inflector = null)
{
$this->client = $client;
$this->inflector = $inflector ?: Inflector::getDefault();
}

public function factory($name, array $args = array())
{

$prefix = $this->client->getConfig('command.prefix');
if (!$prefix) {

$prefix = implode('\\', array_slice(explode('\\', get_class($this->client)), 0, -1)) . '\\Command\\';
$this->client->getConfig()->set('command.prefix', $prefix);
}

$class = $prefix . str_replace(' ', '\\', ucwords(str_replace('.', ' ', $this->inflector->camel($name))));


if (class_exists($class)) {
return new $class($args);
}
}
}
