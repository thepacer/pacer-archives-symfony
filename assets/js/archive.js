/* jshint esversion: 6 */

// Import Bootstrap components
import { Collapse } from 'bootstrap';
import { initializeCalendar } from './calendar';
import { initYearCalendars } from './pages/year-calendar';
import { initVolumeCalendars } from './pages/volume-calendar';

// CSS
require('../../node_modules/bootstrap/dist/css/bootstrap.css');
require('../css/app.scss');

// Make calendar function globally available for inline scripts
window.initializeCalendar = initializeCalendar;

// Initialize view toggle functionality
document.addEventListener('DOMContentLoaded', function() {
  const viewOptions = document.querySelectorAll('input[name="viewOptions"]');
  
  viewOptions.forEach(option => {
    option.addEventListener('change', function(e) {
      const targetId = e.target.dataset.target;
      
      // Hide all collapses
      document.querySelectorAll('.collapse').forEach(el => {
        if (!el.classList.contains('collapse-hide')) {
          const collapse = new Collapse(el, { toggle: false });
          collapse.hide();
        }
      });
      
      // Show the selected one
      const targetEl = document.getElementById(targetId);
      if (targetEl) {
        const collapse = new Collapse(targetEl, { toggle: false });
        collapse.show();
      }
    });
  });
  
  // Initialize page-specific calendars
  if (window.yearCalendarData) {
    initYearCalendars();
  }
  
  if (window.volumeCalendarData) {
    initVolumeCalendars();
  }
});
