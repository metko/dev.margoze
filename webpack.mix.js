const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// postcss.config.js
const purgecss = require('@fullhuman/postcss-purgecss')({

  // Specify the paths to all of the template files in your project 
  content: [
    './resources/views/**/*.html',
    './resources/views/**/*.blade.php',
    './resources/views/**/*.vue',
    './resources/views/**/*.jsx',
    // etc.
  ],

  // Include any special characters you're using in this regular expression
  defaultExtractor: content => content.match(/[A-Za-z0-9-_:/]+/g) || []
})

mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [ 
            tailwindcss('./tailwind.config.js'),
            ...process.env.NODE_ENV === 'production'
            ? [purgecss]
            : []
         ],
    });