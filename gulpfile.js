var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */
var paths = {
  'bootstrap': './resources/assets/vendor/bootstrap-sass/assets/',
  'jquery': './resources/assets/vendor/jquery/'
}

elixir(function(mix) {
    mix.sass('app.scss', 'public/css/', {includePaths: [paths.bootstrap + 'stylesheets/']})
      .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')
      .scripts([
        paths.jquery + "dist/jquery.js",
        paths.bootstrap + "javascripts/bootstrap.js"
      ], "public/js", "public/js/app.js");
});
