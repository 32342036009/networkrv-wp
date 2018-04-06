<?php

namespace Guzzle\Service\Command\Factory;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\ClientInterface;




class AliasFactory implements FactoryInterface
{

protected $aliases;


protected $client;





public function __construct(ClientInterface $client, array $aliases)
{
$this->client = $client;
$this->aliases = $aliases;
}

public function factory($name, array $args = array())
{
if (isset($this->aliases[$name])) {
try {
return $this->client->getCommand($this->aliases[$name], $args);
} catch (InvalidArgumentException $e) {
return null;
}
}
}
}
