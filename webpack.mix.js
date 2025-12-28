const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue().postCss('resources/css/app.css', 'public/css')

// Custom plugin to refresh environment variables
class RefreshEnvPlugin {
    apply(compiler) {
        compiler.hooks.beforeRun.tap('RefreshEnvPlugin', () => {
            delete require.cache[require.resolve('./.env')];
        });
    }
}

mix.extend('refreshEnv', new RefreshEnvPlugin());
mix.refreshEnv();

// Clear cache before each build
mix.before(() => {
    const fs = require('fs');
    const path = './node_modules/.cache';
    if (fs.existsSync(path)) {
        fs.rmSync(path, { recursive: true, force: true });
    }
});
