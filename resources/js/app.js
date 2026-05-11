import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Make Chart.js globally available for all pages
import Chart from 'chart.js/auto';
window.Chart = Chart;
