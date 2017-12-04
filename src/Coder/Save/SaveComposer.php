<?php
namespace Gap\Util\Coder\Save;

class SaveComposer
{
    protected $opts;
    protected $baseDir;

    public function __construct($opts, $baseDir)
    {
        $this->opts = $opts;
        $this->baseDir = $baseDir;
    }

    public function save()
    {
        file_put_contents(
            $this->baseDir . '/composer.json',
            json_encode($this->opts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        chdir($this->baseDir);
        echo "\n  exec composer dumpautoload \n  ---\n";
        exec('composer dumpautoload');
        echo "  ---\n\n";
    }
}
