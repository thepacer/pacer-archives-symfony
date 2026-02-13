/* jshint esversion: 6 */

// Import Bootstrap components
import { Tooltip } from 'bootstrap';

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../css/admin.scss');

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  // Find all tooltips and initialize them with Bootstrap's native API
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  Array.from(tooltipTriggerList).forEach(tooltipTriggerEl => {
    new Tooltip(tooltipTriggerEl);
  });
  
  // Note: DataTables has been removed as it requires jQuery
  // If needed, consider replacing with a vanilla JS table library like DataTables 2.0
  // or implement search/sort functionality with vanilla JavaScript
});