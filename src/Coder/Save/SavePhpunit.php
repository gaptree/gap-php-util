<?php
namespace Gap\Util\Coder\Save;

use Gap\Util\Coder\Parser\AppParser;

class SavePhpunit
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
        $codes = [];
        $codes[] = '<phpunit bootstrap="vendor/autoload.php">';
        $codes[] = '    <testsuites>';

        foreach (array_keys($this->opts) as $appName) {
            $appPath = $this->toPath($appName);
            $codes[] = "        <testsuite name=\"$appPath\">";
            $codes[] = "            <directory>app/$appPath/phpunit</directory>";
            $codes[] = "        </testsuite>";
        }

        $codes[] = '    </testsuites>';
        $codes[] = '</phpunit>';

        file_put_contents(
            $this->baseDir . '/phpunit.xml',
            implode("\n", $codes) . "\n"
        );
    }

    protected function toPath($appName)
    {
        return (new AppParser('', $appName))
            ->getAppPath();
    }
}
