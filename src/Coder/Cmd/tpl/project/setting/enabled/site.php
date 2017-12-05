<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('site', [
        'www' => [
            'host' => 'www.%baseHost%',
        ],
        'static' => [
            'host' => 'static.%baseHost%',
            'dir' => '%baseDir%/site/static',
        ],
    ]);

return $collection;
