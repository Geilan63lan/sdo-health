/**
 * PostCSS Configuration File
 * 
 * This file configures PostCSS, which is used by Laravel Mix to process CSS.
 * PostCSS is a tool for transforming CSS with JavaScript plugins.
 * 
 * Key plugins:
 * - tailwindcss: Processes Tailwind CSS directives and generates utility classes
 * - autoprefixer: Automatically adds vendor prefixes to CSS rules for better browser compatibility
 */

module.exports = {
   plugins: {
      /**
       * Tailwind CSS Plugin
       * 
       * Processes @tailwind directives and generates all utility classes.
       * Reads configuration from tailwind.config.js file.
       */
      tailwindcss: {},
      /**
       * Autoprefixer Plugin
       * 
       * Adds vendor prefixes to CSS rules for better cross-browser compatibility.
       * Automatically handles prefixes like -webkit-, -moz-, -ms-, etc.
       */
      autoprefixer: {},
   },
}
