<?php
namespace Gap\Util\Coder\Cmd;

use Gap\Util\Coder\Parser\BuildParser;
use Gap\Util\Coder\Parser\AppParser;
use Gap\Util\Coder\Entity\BuildEntity;

class BuildEntityCmd extends CmdBase
{
    public function run()
    {
        $buildParser = new BuildParser(
            $this->parameters[0] ?? '',
            $this->app->getConfig()->arr('app')
        );

        if (!$buildParser->getAppName()) {
            throw new \Exception('app: ' . $buildParser->getAppName() . ' not found');
        }

        $appParser = new AppParser(
            $this->baseDir,
            $buildParser->getAppName(),
            $buildParser->getAppSubDir()
        );

        $appDir = $appParser->getAppDir();
        if (!$appDir && !file_exists($appDir)) {
            throw new \Exception('appDir: ' . $appDir . ' not found');
        }

        if (!$buildParser->getModuleName()) {
            throw new \Exception('module: ' . $buildParser->getModuleName() . ' not found');
        }

        if (!$buildParser->getLayerName()) {
            throw new \Exception('layout: ' . $buildParser->getLayerName() . ' not found');
        }

        if (!$buildParser->getEntityName()) {
            throw new \Exception('entity: ' . $buildParser->getEntityName() . ' not found');
        }

        echo "build entity: {$buildParser->getAppName()} - "
            . $buildParser->getModuleName() . ' - '
            . $buildParser->getLayerName() . ' - '
            . $buildParser->getEntityName()
            . "\n";

        (new BuildEntity(
            $this->baseDir,
            $buildParser,
            $appParser
        ))->build();
        //$this->generateEntityClass($this->layerName, $this->entityName);
    }

    /*
    protected function generateEntityClass($layerName, $entityName)
    {
        $dir = $this->appDir . "/src/{$this->moduleName}/$layerName";
        $file = $dir . "/$entityName.php";
        $tpl = "{$layerName}/{$layerName}Entity";

        if (file_exists($file)) {
            echo "$file has already existed";
            return;
        }

        $content = $this->tpl($tpl, [
            'appName' => $this->appName,
            'moduleName' => $this->moduleName,
            'layerName' => $layerName,
            'entityName' => $entityName
        ]);

        file_put_contents($file, "<?php\n" . $content);
        echo "generate file: $file \n";
    }
    */

    /*
    protected function tpl($name, $val = [])
    {
        extract($val);

        ob_start();
        include __DIR__ . "/tpl/entity/$name.tpl";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
    */
}
