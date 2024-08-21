import './bootstrap.js';
// Project assets
import './styles/app.scss'
import './js/common'
// Jquery
import $ from "jquery"
// Bootstrap 
import 'bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.min.css'
import "bootswatch/dist/zephyr/bootstrap.min.css";
import 'bootbox/dist/bootbox.locales.min.js';
import zoomPlugin from 'chartjs-plugin-zoom';

window.$ = window.jQuery = $;
global.bootbox = require('bootbox');

const customCanvasBackgroundColor = {
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart, args, options) => {
        const { ctx } = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = chart.config.options.backgroundColor || 'white';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

// register globally for all charts
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(zoomPlugin);
    Chart.register(customCanvasBackgroundColor);
});