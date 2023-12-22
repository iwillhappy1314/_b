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

mix.sass('frontend/styles/index.scss', 'dist/styles').
    tailwind().
    options({
        outputStyle: 'compressed',
        postCss: [
            require('css-mqpacker'),
        ],
    });

mix.js('frontend/scripts/main.js', 'dist/scripts');
mix.js('admin/admin.js', 'dist/scripts').vue();

//mix.copyWatched('assets/images', 'dist/images').
//    copyWatched('assets/fonts', 'dist/fonts');

if (mix.inProduction()) {
    mix.versionHash();
} else {
    mix.sourceMaps();
    mix.webpackConfig({devtool: 'eval-cheap-source-map'});
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
