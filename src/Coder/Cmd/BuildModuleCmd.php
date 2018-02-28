<?php
namespace Gap\Util\Coder\Cmd;

use Gap\Util\Coder\Parser\BuildParser;
use Gap\Util\Coder\Parser\AppParser;

class BuildModuleCmd extends CmdBase
{
    public function run()
    {
        $buildParser = new BuildParser(
            $this->parameters[0] ?? '',
            $this->app->getConfig()->arr('app')
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


        echo "Build module: {$buildParser->getAppName()} - {$buildParser->getModuleName()} \n";

        $moduleDir = $appParser->getAppDir() . '/src/' . $buildParser->getModuleName();
        if (!file_exists($moduleDir)) {
            mkdir($moduleDir);
            echo "  Create dir: $moduleDir \n";
        }
        $this->generateRouter($buildParser, $appParser);
    }

    protected function tpl($name, $val = [])
    {
        extract($val);

        ob_start();
        include __DIR__ . "/tpl/module/$name.tpl";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    protected function generateRouter($buildParser, $appParser)
    {
        $dir = $appParser->getAppDir() . '/setting/router';
        if (!is_dir($dir)) {
            throw new \Exception("$dir does not exist");
        }

        $file = $dir . '/' . lcfirst($buildParser->getModuleName()) . '.php';
        if (file_exists($file)) {
            return;
        }

        $content = $this->tpl('router', [
            'appName' => $appParser->getAppName(),
            'moduleName' => $buildParser->getModuleName()
        ]);
        file_put_contents($file, "<?php\n" . $content);
        echo "  Generate router: $file \n";
    }
}
