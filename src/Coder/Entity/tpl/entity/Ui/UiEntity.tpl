namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Ui;

use Gap\Http\ResponseInterface;
use Gap\Http\Response;

class <?php echo $entityName; ?> extends UiBase
{
    public function show(): ResponseInterface
    {
        return new Response('show');
    }
}
