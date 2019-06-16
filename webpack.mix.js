const mix = require('laravel-mix');

require('laravel-mix-tailwind');
require('laravel-mix-purgecss');

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

    .sourceMaps();

if (mix.inProduction()) {
    mix.purgeCss({
            globs: [
                path.join(__dirname, 'node_modules/simplemde/**/*.js'),
                path.join(__dirname, 'node_modules/turbolinks/**/*.js'),
                path.join(__dirname, 'vendor/spatie/menu/**/*.php'),
                path.join(__dirname, 'vendor/scrivo/highlight.php/**/*.php'),
                path.join(__dirname, 'resources/css/components/*.css'),
                path.join(__dirname, 'resources/views/**/*.blade.php'),
                path.join(__dirname, 'app/**/*.php'),
            ],
            whitelistPatterns: [/carbon/, /language/, /hljs/, /cm-/, /alert-/, /page/, /iframe/],
        })
        .version();
}
