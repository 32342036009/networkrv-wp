<?php

namespace Guzzle\Service;

use Guzzle\Common\FromConfigInterface;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\ClientInterface as HttpClientInterface;
use Guzzle\Service\Exception\CommandTransferException;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\ServiceDescriptionInterface;
use Guzzle\Service\Resource\ResourceIteratorInterface;




interface ClientInterface extends HttpClientInterface, FromConfigInterface
{











public function getCommand($name, array $args = array());










public function execute($command);








public function setDescription(ServiceDescriptionInterface $service);






public function getDescription();










public function getIterator($command, array $commandOptions = null, array $iteratorOptions = array());
}
