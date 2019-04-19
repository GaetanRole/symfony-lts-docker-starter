// The better option is to require CSS needed from inside a JS file.
import '../scss/app.scss';

import $ from 'jquery';
import 'bootstrap'
import '@fortawesome/fontawesome-free'

// Example importing a function from greet.js
import greet from './greet';

// To copy all ../images into public/build
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);

$(document).ready(function () {
    // Testing
    $('[data-toggle="popover"]').popover();
    console.log(greet('Github User'));
});
