namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Ui;

use Gap\Http\Response;

class <?php echo $entityName; ?> extends UiBase
{
    public function show(): Response
    {
        return new Response('show');
    }
}
