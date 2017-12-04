<?php
namespace Gap\Util\Coder\Parser;

class BuildParser
{
    protected $input;
    protected $apps = [];

    protected $appName = '';
    protected $moduleName = '';
    protected $layerName = '';
    protected $entityName = '';

    public function __construct($input, $apps = [])
    {
        $input = ucwords(str_replace("/", "\\", $input), "\\");
        $input = trim($input, "  \t\n\r\0\x0B\\");

        if (empty($input)) {
            throw new \Exception("input cannot be empty");
        }

        $this->input = $input;
        $this->apps = $apps;

        $this->parse();
    }

    public function getApps()
    {
        return $this->apps;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getAppName()
    {
        return $this->appName;
    }

    public function getAppSubDir()
    {
        $opts = $this->apps[$this->appName] ?? false;
        if (!$opts) {
            throw new \Exception("Cannot find app: {$this->appName}");
        }

        return $opts['dir'];
    }

    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getLayerName()
    {
        return $this->layerName;
    }

    protected function parse()
    {
        $input = $this->input;
        $apps = $this->apps;

        $existed = $input;
        $count = 0;
        $stacks = [];

        while ($existed && $count <= 4) {
            $count++;
            if (isset($apps[$existed])) {
                break;
            }

            $lastPos = strrpos($existed, "\\");
            $pre = substr($existed, 0, $lastPos);
            array_unshift($stacks, substr($existed, $lastPos + 1));
            $existed = $pre;
        }

        if (!$existed) {
            return;
        }

        $this->appName = $existed;
        if (isset($stacks[3])) {
            throw new \Exception('Error format');
        }

        $this->moduleName = $stacks[0] ?? '';
        $this->layerName = $stacks[1] ??  '';
        $this->entityName = $stacks[2] ?? '';
    }
}
