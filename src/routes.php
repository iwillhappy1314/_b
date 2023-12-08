<?php

/**
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

use Wenprise\Mvc\Facades\Input;
use Wenprise\Mvc\Facades\Route;
use Wenprise\Mvc\Facades\View;


Route::get('account', 'OrderController@index' );