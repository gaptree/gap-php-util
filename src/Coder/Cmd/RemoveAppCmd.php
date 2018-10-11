<?php
namespace Gap\Util\Coder\Cmd;

use Gap\Util\Coder\Parser\BuildParser;
use Gap\Util\Coder\Parser\AppParser;
use Gap\Util\Coder\Save\SaveAppConfig;
use Gap\Util\Coder\Save\SaveComposer;
use Gap\Util\Coder\Save\SavePhpunit;
use Gap\Util\Coder\Path\RemoveDir;

class RemoveAppCmd extends CmdBase
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



        echo "Remove app: {$appParser->getAppName()} \n";

        $this->buildAppConfig($appParser);
        $this->buildComposer($appParser);
        $this->buildPhpunit($appParser);

        //(new RemoveDir())->remove($appParser->getAppDir());
        echo "  remove app files manually: \n";
        echo "  $ rm -rf {$appParser->getAppDir()} \n";
    }

    protected function buildAppConfig($appParser)
    {
        echo "  buildAppConfig \n";

        $appAsm = $this->app->getConfig()->arr('app');
        $appName = $appParser->getAppName();
        unset($appAsm[$appName]);
        (new SaveAppConfig($appAsm, $this->baseDir))
            ->save();
    }

    protected function buildComposer($appParser)
    {
        echo "  buildComposer \n";

        $composerFile = $this->baseDir . '/composer.json';
        $appName = $appParser->getAppName();
        $fileContents = file_get_contents($composerFile);
        if ($fileContents === false) {
            throw new \Exception('cannot get contents from file: ' . $composerFile);
        }
        $composerAsm = json_decode($fileContents, true);
        unset($composerAsm['autoload']['psr-4'][$appName . "\\"]);
        unset($composerAsm['autoload-dev']['psr-4']["phpunit\\{$appName}\\"]);

        $this->clearEmptyAttr($composerAsm['autoload-dev'], 'psr-4');
        $this->clearEmptyAttr($composerAsm, 'autoload-dev');

        $this->clearEmptyAttr($composerAsm['autoload'], 'psr-4');
        $this->clearEmptyAttr($composerAsm, 'autoload');

        (new SaveComposer($composerAsm, $this->baseDir))
            ->save();
    }

    protected function clearEmptyAttr(array &$arr, string $key): void
    {
        if (empty($arr[$key])) {
            unset($arr[$key]);
        }
    }

    protected function buildPhpunit($appParser)
    {
        echo "  buildPhpunit \n";

        $appAsm = $this->app->getConfig()->arr('app');
        unset($appAsm[$appParser->getAppName()]);
        (new SavePhpunit($appAsm, $this->baseDir))
            ->save();
    }
}
