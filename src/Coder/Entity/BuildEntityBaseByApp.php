<?php
namespace Gap\Util\Coder\Entity;

class BuildEntityBaseByApp
{
    protected $baseDir;
    protected $apps;
    protected $appName;
    protected $layerName;

    public function __construct($baseDir, $apps, $appName, $layerName)
    {
        $this->baseDir = $baseDir;
        $this->apps = $apps;
        $this->appName = $appName;
        $this->layerName = $layerName;
    }

    public function build()
    {
        $appOpts = $this->apps[$this->appName];
        if (!$appOpts) {
            throw new \Exception('Cannot find app: ' . $this->appName);
        }

        if ($baseAppName = $appOpts['base'] ?? '') {
            obj(new self(
                $this->baseDir,
                $this->apps,
                $baseAppName,
                $this->layerName
            ))->build();
        }

        $targetDir = $this->baseDir
            . '/' . $appOpts['dir']
            . '/src/Base/'
            . $this->layerName;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file = $targetDir
            . '/' . $this->layerName . 'Base.php';

        if (!file_exists($file)) {
            $tpl = $this->layerName . '/' . $this->layerName . 'Base';
            $baseName = $baseAppName ? $baseAppName . "\\Base" : "Gap\\Base";
            $content = $this->tpl($tpl, [
                'appName' => $this->appName,
                'baseName' => $baseName
            ]);

            file_put_contents($file, "<?php\n" . $content);
            echo "create file: $file \n";
        }
    }

    protected function tpl($name, $val = [])
    {
        extract($val);

        ob_start();
        include __DIR__ . "/tpl/app/$name.tpl";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
