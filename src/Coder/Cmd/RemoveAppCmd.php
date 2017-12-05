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



        echo "Remove app: {$appParser->getAppName()} \n";

        $this->buildAppConfig($appParser);
        $this->buildComposer($appParser);
        $this->buildPhpunit($appParser);

        //obj(new RemoveDir())->remove($appParser->getAppDir());
        echo "  remove app files manually: \n";
        echo "  $ rm -rf {$appParser->getAppDir()} \n";
    }

    protected function buildAppConfig($appParser)
    {
        echo "  buildAppConfig \n";

        $appAsm = $this->app->getConfig()->get('app');
        $appName = $appParser->getAppName();
        unset($appAsm[$appName]);
        obj(new SaveAppConfig($appAsm, $this->baseDir))
            ->save();
    }

    protected function buildComposer($appParser)
    {
        echo "  buildComposer \n";

        $composerFile = $this->baseDir . '/composer.json';
        $appName = $appParser->getAppName();
        $composerAsm = json_decode(file_get_contents($composerFile), true);
        unset($composerAsm['autoload']['psr-4'][$appName . "\\"]);
        unset($composerAsm['autoload-dev']['psr-4']["phpunit\\{$appName}\\"]);

        if (!$composerAsm['autoload-dev']['psr-4']) {
            unset($composerAsm['autoload-dev']['psr-4']);
        }
        if (!$composerAsm['autoload-dev']) {
            unset($composerAsm['autoload-dev']);
        }

        obj(new SaveComposer($composerAsm, $this->baseDir))
            ->save();
    }

    protected function buildPhpunit($appParser)
    {
        echo "  buildPhpunit \n";

        $appAsm = $this->app->getConfig()->get('app');
        unset($appAsm[$appParser->getAppName()]);
        obj(new SavePhpunit($appAsm, $this->baseDir))
            ->save();
    }
}
