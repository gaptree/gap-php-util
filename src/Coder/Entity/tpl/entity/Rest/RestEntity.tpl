namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Rest;

use Gap\Http\ResponseInterface;
use Gap\Http\JsonResponse;

class <?php echo $entityName; ?> extends RestBase
{
    public function post(): ResponseInterface
    {
        return new JsonResponse([]);
    }
}
