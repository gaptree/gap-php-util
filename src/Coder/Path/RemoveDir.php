<?php
namespace Gap\Util\Coder\Path;

class RemoveDir
{
    public function remove($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            if (is_array($objects)) {
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (is_dir($dir . "/" . $object)) {
                            $this->remove($dir . "/" . $object);
                            continue;
                        }

                        unlink($dir . "/" . $object);
                        echo "remove file: $dir/$object \n";
                    }
                }
            }

            rmdir($dir);
            echo "remove dir: $dir \n";
        }
    }
}
