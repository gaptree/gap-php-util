namespace <?php echo $appName; ?>\<?php echo $moduleName; ?>\Open;

use Gap\Http\JsonResponse;

class <?php echo $entityName; ?> extends OpenBase
{
    public function post(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
