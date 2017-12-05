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

        $cmd = 'cp -vnR ' . __DIR__ . '/tpl/project/* ' . $this->baseDir;
        echo $cmd . "\n";
        system($cmd);
    }

    protected function isGapInstalled()
    {
        $gapRealDir = $this->baseDir . '/vendor/gap/site';
        return file_exists($gapRealDir);
    }
}
