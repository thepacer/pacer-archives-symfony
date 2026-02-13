/**
 * Volume page calendar initialization
 */
import { initializeCalendar } from '../calendar';

export function initVolumeCalendars() {
  // Look for the volumeIssues data stored in window
  const volumeIssues = window.volumeCalendarData;
  
  if (!volumeIssues || volumeIssues.length === 0) {
    return;
  }

  const startYear = window.volumeStartYear;
  const endYear = window.volumeEndYear;
  const academicMonths = [7, 8, 9, 10, 11, 0, 1, 2, 3, 4, 5, 6];
  const calendarElements = document.querySelectorAll('.calendar');
  
  calendarElements.forEach((el, i) => {
    const month = academicMonths[i];
    const year = month < 7 ? endYear : startYear;
    
    initializeCalendar(el, year, month, volumeIssues);
    
    // Check if this month has any events and adjust opacity
    const hasEvents = volumeIssues.some(event => {
      const eventMonth = parseInt(event.date.split('-')[1]) - 1;
      return eventMonth === month;
    });
    
    if (!hasEvents) {
      el.style.opacity = '0.25';
    }
  });
}
