<?php

namespace Guzzle\Service\Command\LocationVisitor\Request;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\Parameter;




class JsonVisitor extends AbstractRequestVisitor
{

protected $jsonContentType = 'application/json';


protected $data;

public function __construct()
{
$this->data = new \SplObjectStorage();
}









public function setContentTypeHeader($header = 'application/json')
{
$this->jsonContentType = $header;

return $this;
}

public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
{
if (isset($this->data[$command])) {
$json = $this->data[$command];
} else {
$json = array();
}
$json[$param->getWireName()] = $this->prepareValue($value, $param);
$this->data[$command] = $json;
}

public function after(CommandInterface $command, RequestInterface $request)
{
if (isset($this->data[$command])) {

if ($this->jsonContentType && !$request->hasHeader('Content-Type')) {
$request->setHeader('Content-Type', $this->jsonContentType);
}

$request->setBody(json_encode($this->data[$command]));
unset($this->data[$command]);
}
}
}
