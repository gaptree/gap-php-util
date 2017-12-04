<?php
namespace Gap\Util\Coder\Parser;

class AppParser
{
    protected $appName;
    protected $appSubDir;
    protected $baseDir;

    public function __construct($baseDir, $input, $appSubDir = '')
    {
        $this->baseDir = $baseDir;
        $this->appName = $input;
        $this->appSubDir = $appSubDir;
    }

    public function getAppName()
    {
        return $this->appName;
    }

    public function getAppDir()
    {
        $dir = $this->baseDir
            . '/'
            . $this->getAppSubDir();

        if (file_exists($dir)) {
            //throw new \Exception("Cannot find app {$this->appName} dir");
            return realpath($dir);
        }

        return $dir;
    }

    public function getAppSubDir()
    {
        if ($this->appSubDir) {
            return $this->appSubDir;
        }

        return 'app/' . $this->getAppPath();
    }

    public function getAppPath()
    {
        $segs = explode("\\", trim($this->appName, "\\"));
        $appPath = '';

        foreach ($segs as $seg) {
            $appPath .= '/' . lcfirst($seg);
        }

        return trim($appPath, '/');
    }
}
