import './bootstrap.js';
// Project assets
import './styles/app.scss'
import './js/common'

// Jquery
import $ from "jquery"
window.$ = window.jQuery = $;

// Bootstrap 
import 'bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.min.css'
import "bootswatch/dist/zephyr/bootstrap.min.css";


global.bootbox = require('bootbox');
import 'bootbox/dist/bootbox.locales.min.js';

import zoomPlugin from 'chartjs-plugin-zoom';
// register globally for all charts
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(zoomPlugin);
});