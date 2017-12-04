<?php
namespace Gap\Util\Routing\Cmd;

use Gap\Util\Routing\Lib\BuildRouter;

class PrintRouteMapCmd extends CmdBase
{
    public function run()
    {
        $router = obj(new BuildRouter($this->app->getConfig()))
            ->build();

        print_r($router->getRouteMap());
    }
}
