<?php
namespace Gap\Util\Coder\Cmd;

class InitCmd extends CmdBase
{
    protected $aliases = [
    ];

    public function run()
    {
        if (!$this->isGapInstalled()) {
            $composerCmd = "composer require gap/site";
            echo $composerCmd . "\n";
            system($composerCmd);
        }

        // Do not overwrite existing files
        $cmd = 'cp -vnR ' . __DIR__ . '/tpl/project/custom/. ' . $this->baseDir;
        echo $cmd . "\n";
        system($cmd);

        // Overwrite existing files
        $cmd = 'cp -vR ' . __DIR__ . '/tpl/project/sys/. ' . $this->baseDir;
        echo $cmd . "\n";
        system($cmd);

        // do not overwrite existing setting.local.php
        $cpCmd = 'cp -vn '
            . $this->baseDir . '/setting/setting.local-default.php '
            . $this->baseDir . '/setting/setting.local.php';
        echo "cp setting/setting.{local-default -> local}.php \n";
        system($cpCmd);
    }

    protected function isGapInstalled()
    {
        $gapRealDir = $this->baseDir . '/vendor/gap/base';
        return file_exists($gapRealDir);
    }
}
