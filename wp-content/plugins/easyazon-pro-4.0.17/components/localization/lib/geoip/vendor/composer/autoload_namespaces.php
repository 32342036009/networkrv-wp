<?php



$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
'Symfony\\Component\\EventDispatcher\\' => array($vendorDir . '/symfony/event-dispatcher'),
'MaxMind' => array($vendorDir . '/maxmind-db/reader/src'),
'JsonSerializable' => array($baseDir . '/compat'),
'Guzzle\\Tests' => array($vendorDir . '/guzzle/guzzle/tests'),
'Guzzle' => array($vendorDir . '/guzzle/guzzle/src'),
'GeoIp2' => array($baseDir . '/src'),
);
