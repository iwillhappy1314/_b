const pkg = require('./package.json');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    appName            : 'wenpriseSpaceName',
    type               : 'plugin',
    slug               : '_b',
    bannerConfig       : {
        name         : 'wenpriseSpaceName',
        author       : '',
        license      : 'UNLICENSED',
        link         : 'UNLICENSED',
        version      : pkg.version,
        copyrightText: 'This software is released under the UNLICENSED License\nhttps://opensource.org/licenses/UNLICENSED',
        credit       : true,
    },
    files              : [
        {
            name         : 'app',
            entry        : {
                // mention each non-interdependent files as entry points
                // The keys of the object will be used to generate filenames
                // The values can be string or Array of strings (string|string[])
                // But unlike webpack itself, it can not be anything else
                // <https://webpack.js.org/concepts/#entry>
                // You do not need to worry about file-size, because we would do
                // code splitting automatically. When using ES6 modules, forget
                // global namespace pollutions 😉
                //admin: './src/admin/index.js', // Could be a string
                frontend: ['./assets/frontend/main.js'], // Or an array of string (string[])
            },
            webpackConfig: {
                module : {
                    rules: [
                        {
                            test  : /\.vue$/,
                            loader: ['vue-loader'],
                        },
                        {
                            test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                            use : ['svg-inline-loader'],
                        },
                    ],
                },
                plugins: [
                    new VueLoaderPlugin(),
                ],
            },
        },
        {
            name         : 'admin',
            entry        : {
                // mention each non-interdependent files as entry points
                // The keys of the object will be used to generate filenames
                // The values can be string or Array of strings (string|string[])
                // But unlike webpack itself, it can not be anything else
                // <https://webpack.js.org/concepts/#entry>
                // You do not need to worry about file-size, because we would do
                // code splitting automatically. When using ES6 modules, forget
                // global namespace pollutions 😉
                admin: './assets/admin/admin.js', // Could be a string
            },
            webpackConfig: {
                module : {
                    rules: [
                        {
                            test  : /\.vue$/,
                            loader: ['vue-loader'],
                        },
                        {
                            test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                            use : ['svg-inline-loader'],
                        },
                    ],
                },
                plugins: [
                    new VueLoaderPlugin(),
                ],
            },
        },
    ],
    outputPath         : 'dist',
    hasReact           : false,
    hasSass            : true,
    hasLess            : false,
    hasFlow            : false,
    // Externals https://webpack.js.org/configuration/externals/
    externals          : {
        jquery: 'jQuery',
    },
    // Webpack Aliases https://webpack.js.org/configuration/resolve/#resolve-alias
    alias              : undefined,
    // Show overlay on development
    errorOverlay       : true,
    // Auto optimization by webpack, Split all common chunks with default config,
    // <https://webpack.js.org/plugins/split-chunks-plugin/#optimization-splitchunks>
    // Won't hurt because we use PHP to automate loading
    optimizeSplitChunks: true,
    // Usually PHP and other files to watch and reload when changed
    watch              : './inc|includes/**/*.php',
    // Files that you want to copy to your ultimate theme/plugin package
    // Supports glob matching from minimatch
    // @link <https://github.com/isaacs/minimatch#usage>
    packageFiles       : [
        'inc/**',
        'vendor/**',
        'dist/**',
        '*.php',
        '*.md',
        'readme.txt',
        'languages/**',
        'layouts/**',
        'LICENSE',
        '*.css',
    ],
    // Path to package directory, relative to the root
    packageDirPath     : 'package',
};
