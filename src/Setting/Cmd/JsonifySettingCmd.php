<?php
namespace Gap\Util\Setting\Cmd;

class JsonifySettingCmd extends CmdBase
{
    // http://stackoverflow.com/questions/5947742/how-to-change-the-output-color-of-echo-in-linux
    public function run()
    {
        $vcode = uniqid('gap');

        $config = $this->app->getConfig();

        $jsSetting = [
            'debug' => $config->get('debug'),
            'vcode' => $vcode,
            'baseDir' => $config->get('baseDir'),
            'baseHost' => $config->get('baseHost'),
            'site' => $config->get('site'),
            'app' => $config->get('app'),
        ];

        $content = json_encode($jsSetting, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(
            $this->baseDir . '/setting/setting.local.json',
            $content
        );

        $this->echoGreen("Jsonify setting to setting/setting.local.json");
        echo $content;
        echo "\n\n";

        $settingContent = $this->getSettingContent($vcode);
        file_put_contents(
            $this->baseDir . '/setting/local/version.php',
            $settingContent
        );

        $this->echoGreen("Write vcode to setting/local/version.php");
        echo $settingContent;
        echo "\n";
    }

    protected function echoGreen($msg)
    {
        $green = "\033[0;32m";
        $noColor = "\033[0m";
        echo $green . $msg . $noColor . "\n";
    }

    protected function getSettingContent($vcode)
    {
        $codes = [];
        $codes[] = '<?php';
        $codes[] = '$collection = new \Gap\Config\ConfigCollection();';
        $codes[] = '$collection';
        $codes[] = "    ->set(\"vcode\", \"$vcode\");";
        $codes[] = 'return $collection;';
        return implode("\n", $codes) . "\n";
    }
}
