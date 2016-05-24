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
  'jquery': './resources/assets/vendor/jquery/',
  'jSignature': './resources/assets/vendor/jSignature/libs/'
}

elixir(function(mix) {
    mix.sass("app.scss")
      .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')
      .scripts([
        paths.jquery + "dist/jquery.js",
        paths.bootstrap + "javascripts/bootstrap.js"
      ], "public/js/application.js")
      .scripts([
        paths.jSignature + "modernizr.js",
        paths.jSignature + "jSignature.min.js"
      ], "public/js/signature.js");
});
