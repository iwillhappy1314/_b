var fs = require('fs');
var postcss = require('postcss');
var classPrfx = require('postcss-class-prefix');

/* eslint-disable global-require, import/no-extraneous-dependencies */
module.exports = {
	// You can add more plugins and other postcss config
	// For more info see
	// <https://github.com/postcss/postcss-loader#configuration>
	// There is no need to use cssnano, webpack takes care of it!
	plugins: [
		require('autoprefixer'),
		require('cssnano')({
			preset: 'default',
		}),
	],
};
