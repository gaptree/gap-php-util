<?php
namespace Gap\Util\Coder\Cmd;

use Gap\Util\Coder\Parser\BuildParser;
use Gap\Util\Coder\Parser\AppParser;
use Gap\Util\Coder\Path\RemoveDir;

class RemoveModuleCmd extends CmdBase
{
    public function run()
    {
        $buildParser = new BuildParser(
            $this->parameters[0] ?? '',
            $this->app->getConfig()->get('app')
        );

        if (!$buildParser->getAppName()) {
            throw new \Exception('app: ' . $buildParser->getAppName() . ' not found');
        }

        $appParser = new AppParser(
            $this->baseDir,
            $buildParser->getAppName(),
            $buildParser->getAppSubDir()
        );

        $appDir = $appParser->getAppDir();
        if (!$appDir && !file_exists($appDir)) {
            throw new \Exception('appDir: ' . $appDir . ' not found');
        }

        if (!$buildParser->getModuleName()) {
            throw new \Exception('module: ' . $buildParser->getModuleName() . ' not found');
        }


        echo "Remove module: {$buildParser->getAppName()} - {$buildParser->getModuleName()} \n";

        $moduleDir = $appParser->getAppDir() . '/src/' . $buildParser->getModuleName();
        if (!file_exists($moduleDir)) {
            throw new \Exception("Cannot find $moduleDir");
        }

        echo "  Remove module dir: \n";
        echo "  $ rm -rf $moduleDir \n";

        $dir = $appParser->getAppDir() . '/setting/router';
        if (!is_dir($dir)) {
            throw new \Exception("$dir does not exist");
        }
        $file = $dir . '/' . lcfirst($buildParser->getModuleName()) . '.php';
        if (file_exists($file)) {
            //unlink($file);
            echo "  Remove file: \n";
            echo "  $ rm $file \n";
        }
    }
}
