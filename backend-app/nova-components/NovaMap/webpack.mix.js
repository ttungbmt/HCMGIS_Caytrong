let mix = require('laravel-mix')

mix.webpackConfig({
    externals: {
        'lodash-es': '_',
        'lodash': '_',
        'axios': 'axios'
    }
});

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .sass('resources/sass/tool.scss', 'css')
