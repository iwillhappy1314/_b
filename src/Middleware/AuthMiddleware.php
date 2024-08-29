<?php

namespace WenpriseSpaceName\Middleware;

use Closure;

class AuthMiddleware
{
	public function handle( $request, Closure $next )
	{

		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url( '/login/?next=' . $request->url() ) );
		}

		return $next( $request );
	}
}