<?php

namespace Guzzle\Service\Resource;

use Guzzle\Common\AbstractHasDispatcher;
use Guzzle\Batch\BatchBuilder;
use Guzzle\Batch\BatchSizeDivisor;
use Guzzle\Batch\BatchClosureTransfer;
use Guzzle\Common\Version;






class ResourceIteratorApplyBatched extends AbstractHasDispatcher
{

protected $callback;


protected $iterator;


protected $batches = 0;


protected $iterated = 0;

public static function getAllEvents()
{
return array(

'iterator_batch.before_batch',

'iterator_batch.after_batch',

'iterator_batch.created_batch'
);
}






public function __construct(ResourceIteratorInterface $iterator, $callback)
{
$this->iterator = $iterator;
$this->callback = $callback;
Version::warn(__CLASS__ . ' is deprecated');
}








public function apply($perBatch = 50)
{
$this->iterated = $this->batches = $batches = 0;
$that = $this;
$it = $this->iterator;
$callback = $this->callback;

$batch = BatchBuilder::factory()
->createBatchesWith(new BatchSizeDivisor($perBatch))
->transferWith(new BatchClosureTransfer(function (array $batch) use ($that, $callback, &$batches, $it) {
$batches++;
$that->dispatch('iterator_batch.before_batch', array('iterator' => $it, 'batch' => $batch));
call_user_func_array($callback, array($it, $batch));
$that->dispatch('iterator_batch.after_batch', array('iterator' => $it, 'batch' => $batch));
}))
->autoFlushAt($perBatch)
->build();

$this->dispatch('iterator_batch.created_batch', array('batch' => $batch));

foreach ($this->iterator as $resource) {
$this->iterated++;
$batch->add($resource);
}

$batch->flush();
$this->batches = $batches;

return $this->iterated;
}






public function getBatchCount()
{
return $this->batches;
}






public function getIteratedCount()
{
return $this->iterated;
}
}
