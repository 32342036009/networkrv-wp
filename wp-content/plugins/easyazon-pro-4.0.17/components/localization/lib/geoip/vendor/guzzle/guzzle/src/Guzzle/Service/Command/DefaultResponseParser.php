<?php

namespace Guzzle\Service\Command;

use Guzzle\Http\Message\Response;




class DefaultResponseParser implements ResponseParserInterface
{

protected static $instance;





public static function getInstance()
{
if (!self::$instance) {
self::$instance = new self;
}

return self::$instance;
}

public function parse(CommandInterface $command)
{
$response = $command->getRequest()->getResponse();


if ($contentType = $command['command.expects']) {
$response->setHeader('Content-Type', $contentType);
} else {
$contentType = (string) $response->getHeader('Content-Type');
}

return $this->handleParsing($command, $response, $contentType);
}

protected function handleParsing(CommandInterface $command, Response $response, $contentType)
{
$result = $response;
if ($result->getBody()) {
if (stripos($contentType, 'json') !== false) {
$result = $result->json();
} elseif (stripos($contentType, 'xml') !== false) {
$result = $result->xml();
}
}

return $result;
}
}
