<?php
namespace Gap\Util\Coder\Entity;

class BuildEntity
{
    protected $baseDir;
    protected $buildParser;
    protected $appParser;
    protected $layerDir;

    public function __construct($baseDir, $buildParser, $appParser)
    {
        $this->baseDir = $baseDir;
        $this->buildParser = $buildParser;
        $this->appParser = $appParser;

        /*
        $this->layerDir = $appParser->getAppDir() . '/src/'
            . $buildParser->getModuleName() . '/'
            . $buildParser->getLayerName();
         */
    }

    public function build()
    {
        (new BuildEntityBaseByApp(
            $this->baseDir,
            $this->buildParser->getApps(),
            $this->buildParser->getAppName(),
            $this->buildParser->getLayerName()
        ))->build();

        $appDir = $this->baseDir . '/' . $this->buildParser->getAppSubDir();

        (new BuildEntityBaseByLayer(
            $appDir,
            $this->buildParser->getAppName(),
            $this->buildParser->getModuleName(),
            $this->buildParser->getLayerName()
        ))->build();

        $dir = $appDir . '/src/' . $this->buildParser->getModuleName()
            . '/' . $this->buildParser->getLayerName();
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file = $dir . '/' . $this->buildParser->getEntityName() . '.php';
        if (!file_exists($file)) {
            $tpl = $this->buildParser->getLayerName()
                . '/' . $this->buildParser->getLayerName() . 'Entity';

            $content = $this->tpl($tpl, [
                'appName' => $this->buildParser->getAppName(),
                'moduleName' => $this->buildParser->getModuleName(),
                'layerName' => $this->buildParser->getLayerName(),
                'entityName' => $this->buildParser->getEntityName()
            ]);

            file_put_contents($file, "<?php\n" . $content);
            echo "create file: $file \n";
        }
    }

    protected function tpl($name, $val = [])
    {
        extract($val);

        ob_start();
        include __DIR__ . "/tpl/entity/$name.tpl";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
