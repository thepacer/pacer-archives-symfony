/* jshint esversion: 6 */

// Import Bootstrap components
import { Tooltip } from 'bootstrap';

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../css/app.scss');

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  // Find all tooltips and initialize them with Bootstrap's native API
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  Array.from(tooltipTriggerList).forEach(tooltipTriggerEl => {
    new Tooltip(tooltipTriggerEl);
  });
});
