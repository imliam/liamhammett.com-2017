const mix = require('laravel-mix')

require('laravel-mix-tailwind')
require('laravel-mix-purgecss')

mix.js('resources/js/app.js', 'public/js')

    .postCss('resources/css/app.css', 'public/css')

    .tailwind('./tailwind.config.js')

    .options({
        processCssUrls: false,
    })

    .babelConfig({
        plugins: ['@babel/plugin-syntax-dynamic-import'],
    })

    .webpackConfig({
        output: {
            chunkFilename: 'js/[name].js',
        },
    })

    .sourceMaps()

if (mix.inProduction()) {
    mix.purgeCss()
        .version()
}
