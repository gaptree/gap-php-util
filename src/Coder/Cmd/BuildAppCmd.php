<?php
namespace Gap\Util\Coder\Cmd;

use Gap\Util\Coder\Parser\BuildParser;
use Gap\Util\Coder\Parser\AppParser;
use Gap\Util\Coder\Save\SaveAppConfig;
use Gap\Util\Coder\Save\SaveComposer;
use Gap\Util\Coder\Save\SavePhpunit;

class BuildAppCmd extends CmdBase
{
    protected $aliases = [
        'b' => 'base',
        't' => 'target'
    ];

    public function run()
    {
        $buildParser = new BuildParser(
            $this->parameters[0] ?? '',
            $this->app->getConfig()->arr('app')
        );

        $target = $this->options['target'] ?? '';

        $appParser = new AppParser(
            $this->baseDir,
            $buildParser->getInput(),
            $target
        );

        echo "build app: {$appParser->getAppName()}\n";

        $appDir = $appParser->getAppDir();

        $this->makeDir($appDir, 'phpunit');
        $this->makeDir($appDir, 'setting/config');
        $this->makeDir($appDir, 'setting/router');
        $this->makeDir($appDir, 'front/js');
        $this->makeDir($appDir, 'front/scss');
        $this->makeDir($appDir, 'view');
        $this->makeDir($appDir, 'src');

        $this->buildAppConfig($appParser);
        $this->buildComposer($appParser);
        $this->buildPhpunit($appParser);
    }

    protected function makeDir($appDir, $subDir)
    {
        $dir = $appDir . '/' . $subDir;

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            echo "  Create dir: " . $dir . "\n";
        }

        $gitignore = $dir . '/.gitignore';

        if (!file_exists($gitignore)) {
            touch($gitignore);
        }
    }

    protected function buildAppConfig($appParser)
    {
        echo "  buildAppConfig\n";


        $appAsm = $this->app->getConfig()->arr('app');
        $appName = $appParser->getAppName();
        $appAsm[$appName] = [
            'dir' => $appParser->getAppSubDir()
        ];

        $base = $this->options['base'] ?? '';
        $base = ucwords(str_replace("/", "\\", $base), "\\");
        if ($base = trim($base, "  \t\n\r\0\x0B\\")) {
            $appAsm[$appName]['base'] = $base;
        }

        (new SaveAppConfig($appAsm, $this->baseDir))
            ->save();
    }

    protected function buildComposer($appParser)
    {
        echo "  buildComposer \n";
        $composerFile = $this->baseDir . '/composer.json';
        $fileContents = file_get_contents($composerFile);
        if ($fileContents === false) {
            throw new \Exception('cannot get content from file: ' . $composerFile);
        }
        $composerAsm = json_decode($fileContents, true);
        $appName = $appParser->getAppName();

        $autoloadKey = $this->isDev ? 'autoload-dev' : 'autoload';
        $composerAsm[$autoloadKey]['psr-4'][$appName . '\\']
            = $appParser->getAppSubDir() . "/src";

        $composerAsm['autoload-dev']['psr-4']["phpunit\\{$appName}\\"]
            = $appParser->getAppSubDir() . "/phpunit";

        (new SaveComposer($composerAsm, $this->baseDir))
            ->save();
    }

    protected function buildPhpunit($appParser)
    {
        echo "  buildPhpunit \n";
        $appAsm = $this->app->getConfig()->arr('app');
        $appAsm[$appParser->getAppName()] = $appParser->getAppPath();

        (new SavePhpunit($appAsm, $this->baseDir))
            ->save();
    }
}
