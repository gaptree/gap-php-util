<?php
$baseDir = realpath(__DIR__ . '/../../');
require $baseDir . '/vendor/autoload.php';

$configBuilder = new \Gap\Config\ConfigBuilder(
    $baseDir,
    'setting/setting.php',
    'cache/setting-http.php'
);
$config = $configBuilder->build();

if ($config->get('debug') !== false) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$dmg = new \Gap\Database\DatabaseManager($config->get('db'), $config->get('server.id'));
$cmg = new \Gap\Cache\CacheManager($config->get('cache'));

$app = new \Gap\Base\App($config, $dmg, $cmg);

$srcOpts = [];
foreach ($config->get('app') as $appName => $appOpts) {
    $srcOpts[$appName]['dir'] = $appOpts['dir'] . '/setting/router';
}

$routerBuilder = new \Gap\Routing\RouterBuilder(
    $config->get('baseDir'),
    $srcOpts
);
if (false === $config->get('debug')) {
    $routerBuilder
        ->setCacheFile('cache/setting-router-http.php');
}
$router = $routerBuilder->build();

$siteManager = new \Gap\Http\SiteManager($config->get('site'));

$httpHandler = new \Gap\Base\HttpHandler($app, $siteManager, $router);
$request = new \Gap\Http\Request(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER
);
$response = $httpHandler->handle($request);
$response->send();
