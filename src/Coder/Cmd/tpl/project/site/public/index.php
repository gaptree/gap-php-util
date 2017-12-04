<?php
$baseDir = realpath(__DIR__ . '/../../');
require $baseDir . '/vendor/autoload.php';

// baseDir: /var/space/gap/gap-core

$configBuilder = new \Gap\Config\ConfigBuilder(
    $baseDir,
    'setting/setting.php',
    'cache/setting-http.php'
);
$config = $configBuilder->build();

if ($config->get('debug')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$app = new \Gap\Base\App($config);

$app->set('router', function () use ($config) {
    $srcOpts = [];
    foreach ($config->get('app') as $appName => $appOpts) {
        $srcOpts[$appName]['dir'] = $appOpts['dir']
            . '/setting/router';
    }

    $buildRouter = new \Gap\Routing\BuildRouter(
        $config->get('baseDir'),
        $srcOpts
    );
    if (false === $config->get('debug')) {
        $buildRouter
            ->setCacheFile('cache/setting-router-http.php');
    }
    return $buildRouter->build();
});
$app->set('session', function () use ($config) {
    return obj(new \Gap\Site\BuildSession($config->get('session')))
        ->build();
});
$app->set('viewEngine', function () use ($config) {
    return \Foil\Foil::boot([
        'folders' => [$config->get('baseDir') . '/view'],
        'autoescape' => false,
        'ext' => 'phtml'
    ])->engine();
});

$app->set(
    'siteManager',
    \Gap\Routing\SiteManager::class,
    [$config->get('site')]
);
/*
$app->set(
    'localeManager',
    \Gap\I18n\Locale\LocaleManager::class,
    [$config->get('i18n.locale')]
);
$app->set(
    'dmg',
    \Gap\Database\DatabaseManager::class,
    [$config->get('db'), $config->get('server.id')]
);

$app->set(
    'cmg',
    \Gap\Cache\CacheManager::class,
    [$config->get('cache'), $config->get('server.id')]
);

$app->set('translator', function () use ($app, $config) {
    return new \Gap\I18n\Translator\Translator(
        $app->get('dmg')->connect($config->get('i18n.db')),
        $app->get('cmg')->connect($config->get('i18n.cache'))
    );
});
$app->set('meta', function () use ($app, $config) {
    return new \Gap\Meta\Meta(
        $app->get('dmg')->connect($config->get('meta.db')),
        $app->get('cmg')->connect($config->get('meta.cache'))
    );
});
 */

$handleHttp = new \Gap\Site\HandleHttp($app);
$request = new \Gap\Http\Request(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER
);
$response = $handleHttp->handle($request);
$response->send();
