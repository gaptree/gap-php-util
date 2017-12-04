<?php
namespace Gap\Util\Test\Cmd;

class TestCmd extends CmdBase
{
    public function run()
    {
        $testClass = $this->getTest();
        obj(new $testClass())->test();
    }

    protected function getTest()
    {
        $argv = $this->argv;
        if (!isset($argv[2])) {
            throw new \Exception('no test set');
        }
        return "cmdtest\\" . str_replace('/', "\\", trim($argv[2])) . 'Test';
    }
}
