<?php
namespace Gap\Util\Routing\Cmd;

use Gap\Util\Routing\Lib\BuildRouter;

class ListRouteCmd extends CmdBase
{
    public function run()
    {
        $router = obj(new BuildRouter($this->app->getConfig()))
            ->build();

        $routes = [];
        $errors = [];

        foreach ($router->getRouteMap() as $routeName => $set) {
            foreach ($set as $mode => $sons) {
                foreach ($sons as $method => $route) {
                    $action = $route->getAction();
                    $appName = $route->getApp();

                    if (0 !== strpos($action, $appName)) {
                        $errors[] = "$appName - $routeName - $action";
                        continue;
                    }

                    $appLen = strlen($appName);

                    $tplAction = substr($action, $appLen + 1);
                    $moduleName = strstr($tplAction, "\\", true);

                    //echo "$appName - $moduleName - $action - $tplAction \n";
                    //continue;

                    $routes[$appName][$moduleName][$routeName]["$mode$method"] = 1;
                }
            }
        }

        ksort($routes);
        foreach ($routes as $appName => $modules) {
            ksort($modules);
            echo "$appName\n";

            foreach ($modules as $moduleName => $routes) {
                ksort($routes);
                echo " - $moduleName\n";

                foreach ($routes as $routeName => $ways) {
                    echo "  - $routeName ";
                    echo "[" . implode(',', array_keys($ways)) . "]";
                    echo "\n";
                }
            }
        }

        echo "\nerror\n";
        echo " - the action of route does not match with appName: \n";

        foreach ($errors as $error) {
            echo "  - $error\n";
        }

        //print_r($routes);
        //print_r($errors);
    }
}
