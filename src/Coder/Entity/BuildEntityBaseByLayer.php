<?php
namespace Gap\Util\Coder\Entity;

class BuildEntityBaseByLayer
{
    protected $appDir;
    protected $appName;
    protected $moduleName;
    protected $layerName;

    public function __construct($appDir, $appName, $moduleName, $layerName)
    {
        $this->appDir = $appDir;
        $this->appName = $appName;
        $this->moduleName = $moduleName;
        $this->layerName = $layerName;
    }

    public function build()
    {
        $dir =  $this->appDir
            . '/src/'
            . $this->moduleName
            . '/' . $this->layerName;

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/' . $this->layerName . 'Base.php';

        if (!file_exists($file)) {
            $tpl = $this->layerName . '/' . $this->layerName . 'Base';
            $content = $this->tpl($tpl, [
                'appName' => $this->appName,
                'moduleName' => $this->moduleName
            ]);

            file_put_contents($file, "<?php\n" . $content);
            echo "create file: $file \n";
        }
    }

    protected function tpl($name, $val = [])
    {
        extract($val);

        ob_start();
        include __DIR__ . "/tpl/module/$name.tpl";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
