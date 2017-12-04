<?php
namespace Gap\Util\Routing\Lib;

use Gap\Routing\RouterManager;
use Gap\Config\Config;

class BuildRouter
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function build()
    {
        $srcOpts = [];
        $config = $this->config;
        foreach ($config->get('app') as $appName => $appOpts) {
            $srcOpts[$appName]['dir'] = $appOpts['dir']
                . '/setting/router';
        }

        $routerBuilder = new \Gap\Routing\BuildRouter(
            $config->get('baseDir'),
            $srcOpts
        );
        return $routerBuilder->build();
    }
}
