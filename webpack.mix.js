let mix = require('laravel-mix')

mix
    .setPublicPath('dist')
    .sass('resources/sass/filter.scss', 'css')
    .js('resources/js/filter.js', 'js')
