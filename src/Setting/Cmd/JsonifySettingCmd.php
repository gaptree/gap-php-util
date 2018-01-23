<?php
namespace Gap\Util\Setting\Cmd;

class JsonifySettingCmd extends CmdBase
{
    // http://stackoverflow.com/questions/5947742/how-to-change-the-output-color-of-echo-in-linux
    public function run()
    {
        $vcode = uniqid('gap');

        $config = $this->app->getConfig();
        $serverId = $config->get('server.id');

        if (empty($serverId) || 1 === intval($serverId)) {
            $serverId = bin2hex(random_bytes(3));
        }

        $jsSetting = [
            'debug' => $config->get('debug'),
            'vcode' => $vcode,
            'serverId' => $serverId,
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

        $versionSettingStr = $this->getVersionSettingStr($vcode);
        file_put_contents(
            $this->baseDir . '/setting/local/version.php',
            $versionSettingStr
        );

        $this->echoGreen("Write vcode to setting/local/version.php");
        echo $versionSettingStr;
        echo "\n";

        $serverSettingStr = $this->getServerSettingStr($vcode);
        file_put_contents(
            $this->baseDir . '/setting/local/server.php',
            $serverSettingStr
        );

        $this->echoGreen("Write server.id to setting/local/server.php");
        echo $serverSettingStr;
        echo "\n";
    }

    protected function echoGreen($msg)
    {
        $green = "\033[0;32m";
        $noColor = "\033[0m";
        echo $green . $msg . $noColor . "\n";
    }

    protected function getVersionSettingStr($vcode)
    {
        $codes = [];
        $codes[] = '<?php';
        $codes[] = '$collection = new \Gap\Config\ConfigCollection();';
        $codes[] = '$collection';
        $codes[] = "    ->set(\"vcode\", \"$vcode\");";
        $codes[] = 'return $collection;';
        return implode("\n", $codes) . "\n";
    }

    protected function getServerSettingStr($serverId)
    {
        $codes = [];
        $codes[] = '<?php';
        $codes[] = '$collection = new \Gap\Config\ConfigCollection();';
        $codes[] = '$collection->set(\"server\", [';
        $codes[] = "    \"id\" => \"$serverId\"";
        $codes[] = ']);';
        $codes[] = 'return $collection;';
        return implode("\n", $codes) . "\n";
    }
}
