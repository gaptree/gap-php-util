<?php
namespace Gap\Util\Coder\Save;

class SaveAppConfig
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
        $codes[] = '<?php';
        $codes[] = '$collection = new \Gap\Config\ConfigCollection();';
        $codes[] = '$collection';
        $codes[] = "    ->set(\"app\", [";
        foreach ($this->opts as $appName => $appOpts) {
            $codes[] = "        \"$appName\" => [";
            foreach ($appOpts as $key => $val) {
                $codes[] = "            \"$key\" => \"$val\",";
            }
            $codes[] = "        ],";
        }
        $codes[] = "    ]);";
        $codes[] = 'return $collection;';

        file_put_contents(
            $this->baseDir . '/setting/setting.app.php',
            implode("\n", $codes) . "\n"
        );
    }
}
