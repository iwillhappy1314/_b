<?php
/**
 * 主题辅助函数
 */

use Wenprise\Dumper;

if ( ! function_exists( 'dd' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dd( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x );
		}
		die( 1 );
	}
}

if ( ! function_exists( 'dda' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dda( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x->toArray() );
		}
		die( 1 );
	}
}