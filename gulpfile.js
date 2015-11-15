var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // Bootstrap CSS and Selecte2 CSS is imported in app.scss
    mix.sass('app.scss')
        .browserSync({
            proxy: "localhost:8000"
        });
    
    // Copy JS files to resources/assets folder
    mix.copy('node_modules/jquery/dist/jquery.min.js', 'resources/assets/js');
    mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'resources/assets/js');
    mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts/bootstrap');
    mix.copy('node_modules/select2/dist/js/select2.min.js', 'resources/assets/js');
    
    
    // Combine all JS files into one file
    mix.scripts([
        'jquery.min.js',
        'bootstrap.min.js',
        'select2.min.js',
        'strucko.js'
    ]);
    
    
});
