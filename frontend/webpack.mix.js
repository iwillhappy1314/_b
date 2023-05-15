let mix = require('laravel-mix');
let webpack = require('webpack');

require('laravel-mix-tailwind');
require('laravel-mix-versionhash');
require('laravel-mix-copy-watched');
require('mix-white-sass-icons');

mix.setPublicPath('./');

mix.webpackConfig({
    externals: {
        jquery: 'jQuery',
    },
    plugins  : [
        new webpack.ProvidePlugin({
            $              : 'jquery',
            jQuery         : 'jquery',
            'window.jQuery': 'jquery',
        })],
});

mix.sass('assets/styles/index.scss', 'dist/styles').
    tailwind().
    options({
        outputStyle: 'compressed',
        postCss: [
            require('css-mqpacker'),
        ],
    });

mix.js('assets/scripts/main.js', 'dist/scripts');

//mix.copyWatched('assets/images', 'dist/images').
//    copyWatched('assets/fonts', 'dist/fonts');

if (mix.inProduction()) {
    mix.versionHash();
} else {
    mix.sourceMaps();
    mix.webpackConfig({devtool: 'frontend-map'});
}

mix.browserSync({
    proxy         : 's.as',
    files         : [
        {
            match  : [
                './dist/**/*',
                '../**/*.php',
            ],
            options: {
                ignored: '*.txt',
            },
        },
    ],
    snippetOptions: {
        whitelist: ['/wp-admin/admin-ajax.php'],
        blacklist: ['/wp-admin/**'],
    },
});
