// The better option is to require CSS needed from inside a JS file.
import '../scss/app.scss';

import $ from 'jquery';
import 'bootstrap'
import '@fortawesome/fontawesome-free'

// Example importing a function from greet.js
import greet from './greet';

$(document).ready(function () {
    // Bootstrap JS dependency
    $('[data-toggle="popover"]').popover();

    // Always show Bootstrap modal for flash messages
    $('.modal').modal('show');

    console.log(greet('Github User'));
});
