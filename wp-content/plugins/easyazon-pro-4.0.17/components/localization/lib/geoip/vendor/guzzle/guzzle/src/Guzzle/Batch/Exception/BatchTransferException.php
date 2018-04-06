<?php

namespace Guzzle\Batch\Exception;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Batch\BatchTransferInterface as TransferStrategy;
use Guzzle\Batch\BatchDivisorInterface as DivisorStrategy;




class BatchTransferException extends \Exception implements GuzzleException
{

protected $batch;


protected $transferStrategy;


protected $divisorStrategy;


protected $transferredItems;








public function __construct(
array $batch,
array $transferredItems,
\Exception $exception,
TransferStrategy $transferStrategy = null,
DivisorStrategy $divisorStrategy = null
) {
$this->batch = $batch;
$this->transferredItems = $transferredItems;
$this->transferStrategy = $transferStrategy;
$this->divisorStrategy = $divisorStrategy;
parent::__construct(
'Exception encountered while transferring batch: ' . $exception->getMessage(),
$exception->getCode(),
$exception
);
}






public function getBatch()
{
return $this->batch;
}






public function getTransferredItems()
{
return $this->transferredItems;
}






public function getTransferStrategy()
{
return $this->transferStrategy;
}






public function getDivisorStrategy()
{
return $this->divisorStrategy;
}
}
