/**
 * Webpack Mix Configuration File
 * 
 * This file replaces Vite as the build tool for compiling frontend assets.
 * Laravel Mix provides a simpler API on top of Webpack for Laravel applications.
 * 
 * Key differences from Vite:
 * - Uses Webpack instead of Rollup (Vite's bundler)
 * - Provides Laravel-specific helper functions
 * - Compatible with older server environments
 * - Works well with Tailwind CSS v3
 */

const mix = require('laravel-mix');

/**
 * Configure Mix for development and production
 * 
 * mix.js(): Compiles JavaScript files
 * - First parameter: Source file (resources/js/app.js)
 * - Second parameter: Output directory (public/js)
 * 
 * mix.postCss(): Processes CSS files with PostCSS
 * - First parameter: Source file (resources/css/app.css)
 * - Second parameter: Output directory (public/css)
 * - Third parameter: PostCSS plugins array (Tailwind CSS)
 * 
 * mix.options(): Configure additional Mix options
 * - processCssUrls: false - Prevents Mix from rewriting CSS URLs
 *   This is important for Tailwind CSS to work correctly
 */
mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
      require('tailwindcss'),
   ])
   .copy('vendor/livewire/flux/dist/flux.css', 'public/css/flux.css')
   .options({
      processCssUrls: false,
   });

/**
 * Configure source maps for debugging
 * 
 * Source maps help in debugging by mapping compiled code back to source files.
 * - In development: Generates full source maps
 * - In production: Generates smaller source maps for better performance
 */
if (!mix.inProduction()) {
   mix.sourceMaps();
}
