/**
 * Year page calendar initialization
 */
import { initializeCalendar } from '../calendar';

export function initYearCalendars() {
  // Look for the volumeIssues data stored in window
  const volumeIssues = window.yearCalendarData;
  
  if (!volumeIssues || volumeIssues.length === 0) {
    return;
  }

  const year = window.yearCalendarYear;
  const academicMonths = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
  const calendarElements = document.querySelectorAll('.calendar');
  
  calendarElements.forEach((el, i) => {
    const month = academicMonths[i];
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
