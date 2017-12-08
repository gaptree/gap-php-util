namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Open;

use Gap\Http\ResponseInterface;
use Gap\Http\JsonResponse;

class <?php echo $entityName; ?> extends OpenBase
{
    public function post(): ResponseInterface
    {
        return new JsonResponse([]);
    }
}
