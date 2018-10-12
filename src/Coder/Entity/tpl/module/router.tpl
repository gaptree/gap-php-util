$collection = new \Gap\Routing\RouteCollection();

/*
$collection
    ->site('default') 
    ->noFilter()

    ->get('/get/pattern', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Ui\Entity@show')
    ->post('/post/patter', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Rest\Entity@show')
    ->postRest('/post-rest/patter', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Rest\Entity@post')
    ->getOpen('/get-open/patter', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Open\Entity@show')
    ->postOpen('/post-open/patter', 'routeName', '<?php echo $appName; ?>\<?php echo $moduleName; ?>\Open\Entity@post');

// Add filters in setting/system/routeFilter.php
*/

return $collection;
