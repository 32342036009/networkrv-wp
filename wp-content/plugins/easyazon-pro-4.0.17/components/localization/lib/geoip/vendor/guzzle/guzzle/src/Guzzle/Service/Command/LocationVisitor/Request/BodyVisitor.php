<?php

namespace Guzzle\Service\Command\LocationVisitor\Request;

use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\EntityBodyInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\Parameter;








class BodyVisitor extends AbstractRequestVisitor
{
public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
{
$value = $param->filter($value);
$entityBody = EntityBody::factory($value);
$request->setBody($entityBody);
$this->addExpectHeader($request, $entityBody, $param->getData('expect_header'));

if ($encoding = $entityBody->getContentEncoding()) {
$request->setHeader('Content-Encoding', $encoding);
}
}








protected function addExpectHeader(EntityEnclosingRequestInterface $request, EntityBodyInterface $body, $expect)
{

if ($expect === false) {
$request->removeHeader('Expect');
} elseif ($expect !== true) {

$expect = $expect ?: 1048576;

if (is_numeric($expect) && $body->getSize()) {
if ($body->getSize() < $expect) {
$request->removeHeader('Expect');
} else {
$request->setHeader('Expect', '100-Continue');
}
}
}
}
}
