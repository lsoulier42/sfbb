var $ = require('jquery');
global.$ = global.jQuery = $;
window.$ = window.jQuery = $
require('bootstrap/dist/js/bootstrap.bundle');

import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
})
