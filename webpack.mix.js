const mix = require('laravel-mix');
const fs = require('fs');
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

mix.react('resources/js/app.js', 'public/js');

fs.readdirSync(`${__dirname}/resources/sass`).forEach(file => {
   mix.sass(`${__dirname}/resources/sass/${file}`, 'public/css');
})

mix.sourceMaps();
