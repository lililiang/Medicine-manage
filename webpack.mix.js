const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix.combine(
    [
        'resources/assets/js/app.js',
        'node_modules/vue/dist/vue.js',
        'node_modules/vue/dist/vue.common.js',
        'node_modules/vue/dist/vue.esm.js'
    ],
    'public/js/app.js'
)
