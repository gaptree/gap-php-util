namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Rest;

use Gap\Http\JsonResponse;

class <?php echo $entityName; ?> extends RestBase
{
    public function post(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
