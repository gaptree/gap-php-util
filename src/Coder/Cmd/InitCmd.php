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

        $cmd = 'cp -vnR ' . __DIR__ . '/tpl/project/. ' . $this->baseDir;
        echo $cmd . "\n";
        system($cmd);

        $cpCmd = 'cp -vn '
            . $this->baseDir . '/setting/setting.local-default.php '
            . $this->baseDir . '/setting/setting.local.php';
        echo "cp setting/setting.{local-default -> local}.php \n";
        system($cpCmd);
    }

    protected function isGapInstalled()
    {
        $gapRealDir = $this->baseDir . '/vendor/gap/site';
        return file_exists($gapRealDir);
    }
}
