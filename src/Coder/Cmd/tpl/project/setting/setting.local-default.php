<?php
$collection = new \Gap\Config\Config();

$collection
    ->set('debug', true)
    ->set('baseHost', 'gap.sun')
    ->set('local', [
        'db' => [
            'host' => 'db',
            'username' => 'gap',
            'password' => '123456789'
        ],
        'cache' => [
            'host' => 'redis'
        ],
        /*
        'session' => [
            'save_handler' => 'redis',
            'save_path' => 'tcp://redis:6379?database=10',
            'cookie_domain' => 'gap.sun'
        ]
         */
    ]);

return $collection;

/*
$baseHost = $this->get('baseHost');

$this->set('site', [
    'www' => [
        'host' => 'www.' . $baseHost,
    ],
    'static' => [
        'host' => 'static.' . $baseHost,
        'dir' => $this->get('baseDir') . '/site/static',
    ],
]);
 */
