<?php
namespace Gap\Util\Coder\Cmd;

class ListAppCmd extends CmdBase
{
    public function run()
    {
        $apps = $this->app->getConfig()->get('app', []);

        foreach ($apps as $appName => $opts) {
            echo $appName . ' - ';
            foreach ($opts as $optKey => $optVal) {
                echo "[$optKey]($optVal)";
            }
            echo "\n";
        }
    }
}
