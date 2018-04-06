<?php

namespace Guzzle\Batch;

use Guzzle\Batch\BatchTransferInterface;
use Guzzle\Batch\BatchDivisorInterface;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Exception\InconsistentClientTransferException;





class BatchCommandTransfer implements BatchTransferInterface, BatchDivisorInterface
{

protected $batchSize;




public function __construct($batchSize = 50)
{
$this->batchSize = $batchSize;
}





public function createBatches(\SplQueue $queue)
{
$groups = new \SplObjectStorage();
foreach ($queue as $item) {
if (!$item instanceof CommandInterface) {
throw new InvalidArgumentException('All items must implement Guzzle\Service\Command\CommandInterface');
}
$client = $item->getClient();
if (!$groups->contains($client)) {
$groups->attach($client, new \ArrayObject(array($item)));
} else {
$groups[$client]->append($item);
}
}

$batches = array();
foreach ($groups as $batch) {
$batches = array_merge($batches, array_chunk($groups[$batch]->getArrayCopy(), $this->batchSize));
}

return $batches;
}

public function transfer(array $batch)
{
if (empty($batch)) {
return;
}


$client = reset($batch)->getClient();


$invalid = array_filter($batch, function ($command) use ($client) {
return $command->getClient() !== $client;
});

if (!empty($invalid)) {
throw new InconsistentClientTransferException($invalid);
}

$client->execute($batch);
}
}
