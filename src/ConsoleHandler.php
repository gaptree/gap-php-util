<?php
namespace Gap\Util;

use Gap\Base\App;

class ConsoleHandler
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function handle($argv = [], $argc = 1)
    {
        $cmdMap = $this->buildCmdMap();

        if (!isset($argv[1])) {
            $this->printHelp($cmdMap);
            return;
        }

        $commandName = $argv[1];
        $commandClass = $cmdMap[$commandName] ?? '';
        if (!$commandClass) {
            echo "cannot find command [$commandName];\n";
            return;
        }
        obj(new $commandClass($this->app, $argv, $argc))
            ->run();
    }

    protected function printHelp($cmdMap)
    {
        $help = "Useage: \n"
        . "  gap COMMAND [options]\n"
        . "  COMMAND:\n";

        foreach ($cmdMap as $key => $val) {
            $help .= "    $key => $val \n";
        }
        echo $help;
    }

    protected function buildCmdMap()
    {
        $cmdFile = $this->app->getConfig()->get('baseDir')
            . '/setting/cmd/cmd.php';
        $cmdMap = [];
        if (file_exists($cmdFile)) {
            $cmdMap = require $cmdFile;
        }
        $cmdMap['init'] = 'Gap\Util\Coder\Cmd\InitCmd';
        return $cmdMap;
    }
}
