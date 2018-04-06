<?php

namespace Guzzle\Service\Resource;

use Guzzle\Service\Command\CommandInterface;




class MapResourceIteratorFactory extends AbstractResourceIteratorFactory
{

protected $map;


public function __construct(array $map)
{
$this->map = $map;
}

public function getClassName(CommandInterface $command)
{
$className = $command->getName();

if (isset($this->map[$className])) {
return $this->map[$className];
} elseif (isset($this->map['*'])) {

return $this->map['*'];
}

return null;
}
}
