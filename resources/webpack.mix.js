let mix = require('laravel-mix');
let webpack = require('webpack');
let path = require('path');

require('laravel-mix-tailwind');
require('laravel-mix-versionhash');
require('laravel-mix-copy-watched');
require('mix-white-sass-icons');

mix.setPublicPath('./');

mix.webpackConfig({
    externals: {
        jquery: 'jQuery',
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'admin/src'),
        },
    },
    plugins  : [
        new webpack.ProvidePlugin({
            $              : 'jquery',
            jQuery         : 'jquery',
            'window.jQuery': 'jquery',
        })],
});

mix.sass('frontend/index.scss', 'dist').
    tailwind().
    options({
        outputStyle: 'compressed',
        postCss: [
            require('css-mqpacker'),
        ],
    });

mix.js('frontend/main.js', 'dist');
mix.css('admin/src/styles.css', 'dist/admin.css').
    tailwind().
    options({
        outputStyle: 'compressed',
        postCss: [
            require('css-mqpacker'),
        ],
    });
mix.ts('admin/src/main.tsx', 'dist/admin.js', {
    configFile: path.resolve(__dirname, 'admin/tsconfig.json'),
    compilerOptions: {
        noEmit: false,
        allowImportingTsExtensions: false,
        lib: ['ES2022', 'DOM', 'DOM.Iterable'],
    },
}).react();

//mix.copyWatched('assets/images', 'dist/images').
//    copyWatched('assets/fonts', 'dist/fonts');

if (mix.inProduction()) {
    mix.versionHash();
} else {
    mix.sourceMaps();
    mix.webpackConfig({devtool: 'eval-cheap-source-map'});
}

mix.override((config) => {
    config.plugins = config.plugins.filter(plugin => {
        const options = plugin && plugin.options ? plugin.options : {};
        const isOffending = options.reporters || options.reporter || (options.name === 'Mix');
        return !isOffending;
    });
});

mix.browserSync({
    proxy         : 'upends-member-center.test',
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
