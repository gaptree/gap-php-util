<?php
$collection = new \Gap\Config\Config();

$collection->set('meta', [
    'db' => 'meta',
    'cache' => 'meta',
]);

return $collection;
