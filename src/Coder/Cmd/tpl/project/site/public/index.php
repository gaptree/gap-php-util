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

$app = new \Gap\Base\App($config);

register_router($app);
register_session($app);
register_view_engine($app);
register_site_manager($app);
register_site_url_builder($app);
register_route_url_builder($app);
// register_locale_manager($app);
// register_dmg($app);
// register_cmg($app);
// register_translator($app);

register_request_filter_manager($app, [
    new \Gap\Base\RequestFilter\CsrfFilter()
]);
register_route_filter_manager($app, [
    new \Gap\Base\RouteFilter\LoginFilter()
]);

/*
// composer require gap/meta
$app->set('meta', function () use ($app, $config) {
    return new \Gap\Meta\Meta(
        $app->get('dmg')->connect($config->get('meta.db')),
        $app->get('cmg')->connect($config->get('meta.cache'))
    );
});
 */

$httpHandler = new \Gap\Site\HttpHandler($app);
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
