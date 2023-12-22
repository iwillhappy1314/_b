var fs = require('fs');
var postcss = require('postcss');
var cssnano = require('cssnano');
var autoprefixer = require('autoprefixer');
var tailwindcss = require('tailwindcss');
var classPrfx = require('postcss-class-prefix');

module.exports = {
	plugins: {
		tailwindcss: {},
		autoprefixer: {},
		cssnano: {
			preset: 'default',
		},
	},
};