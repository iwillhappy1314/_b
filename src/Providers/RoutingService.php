<?php

namespace WenpriseSpaceName\Providers;

use Wenprise\Mvc\Facades\Route;
use Wenprise\Mvc\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider {
	/**
	 * 定义主题路径命名空间
	 */
	public function register() {
		Route::group( [
			'namespace' => 'WenpriseSpaceName\Controllers',
		], function () {
			require SPACENAME_PATH .'src/routes.php';
		} );
	}
}