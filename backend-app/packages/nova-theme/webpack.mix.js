let mix = require('laravel-mix')

mix
    .setPublicPath('dist')
    .js('resources/js/theme.js', 'js')
    .sass('resources/sass/theme.scss', 'css')
