<?php

use WenpriseSpaceNameVendor\Wenprise\Dispatcher\Router;

$routers = [
    '_b' => ['\WenpriseSpaceName\Controllers\SerialsController', 'index'],
];

// 社交响应
Router::routes(apply_filters('_b_routers', $routers));