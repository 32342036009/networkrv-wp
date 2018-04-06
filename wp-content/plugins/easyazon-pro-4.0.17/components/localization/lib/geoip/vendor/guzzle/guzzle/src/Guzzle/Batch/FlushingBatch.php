<?php

namespace Guzzle\Batch;




class FlushingBatch extends AbstractBatchDecorator
{

protected $threshold;


protected $currentTotal = 0;





public function __construct(BatchInterface $decoratedBatch, $threshold)
{
$this->threshold = $threshold;
parent::__construct($decoratedBatch);
}








public function setThreshold($threshold)
{
$this->threshold = $threshold;

return $this;
}






public function getThreshold()
{
return $this->threshold;
}

public function add($item)
{
$this->decoratedBatch->add($item);
if (++$this->currentTotal >= $this->threshold) {
$this->currentTotal = 0;
$this->decoratedBatch->flush();
}

return $this;
}
}
