let mix = require('laravel-mix')

mix.webpackConfig({
    externals: {
        'lodash-es': '_',
        'lodash': '_',
    }
});

mix
  .setPublicPath('dist')
  .js('resources/js/card.js', 'js')
  .js('resources/js/tool.js', 'js')
  .sass('resources/sass/card.scss', 'css')
