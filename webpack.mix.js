
const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'public/js') // Aquí se incluyen tus scripts existentes
    .js('node_modules/dropzone/dist/min/dropzone.min.js', 'public/js') // Agrega el script de Dropzone.js
    .sass('resources/sass/app.scss', 'public/css') // Agrega tus estilos SCSS existentes
    .version();
