<?php

use Wenprise\SpaceName\Vendor\Wenprise\Dispatcher\Router;

$routers = [
    '_b-serials' => ['\Wenprise\SpaceName\Controllers\SerialsController', 'index'],
];

// 社交响应
Router::routes(apply_filters('_b_routers', $routers));