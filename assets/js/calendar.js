/**
 * Simple vanilla JavaScript calendar implementation
 * Replaces the jQuery-based clndr calendar
 */

export function initializeCalendar(containerEl, year, month, events) {
  // Clear any existing content
  containerEl.innerHTML = '';
  
  const date = new Date(year, month, 1);
  const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                      'July', 'August', 'September', 'October', 'November', 'December'];
  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  
  // Create month header
  const header = document.createElement('h5');
  header.className = 'my-3';
  header.textContent = monthNames[month];
  containerEl.appendChild(header);
  
  // Create table
  const table = document.createElement('table');
  table.className = 'table table-sm clndr-table';
  
  // Create header row with day names
  const thead = document.createElement('thead');
  const headerRow = document.createElement('tr');
  headerRow.className = 'header-days';
  
  dayNames.forEach(day => {
    const th = document.createElement('td');
    th.className = 'header-day';
    th.textContent = day;
    headerRow.appendChild(th);
  });
  
  thead.appendChild(headerRow);
  table.appendChild(thead);
  
  // Create calendar body
  const tbody = document.createElement('tbody');
  
  // Get first day of month (0 = Sunday)
  const firstDay = date.getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  
  // Build event map by date for fast lookup
  const eventMap = {};
  events.forEach(event => {
    eventMap[event.date] = event;
  });
  
  let dayCounter = 1;
  let rows = Math.ceil((firstDay + daysInMonth) / 7);
  
  for (let i = 0; i < rows; i++) {
    const row = document.createElement('tr');
    
    for (let j = 0; j < 7; j++) {
      const cell = document.createElement('td');
      const dayContent = document.createElement('div');
      dayContent.className = 'day-contents';
      
      // Show empty cells before the 1st and after the last day
      if (i === 0 && j < firstDay) {
        // Empty cell before month starts
        cell.className = 'empty-day';
        dayContent.textContent = '';
      } else if (dayCounter > daysInMonth) {
        // Empty cell after month ends
        cell.className = 'empty-day';
        dayContent.textContent = '';
      } else {
        // Current month day
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCounter).padStart(2, '0')}`;
        
        if (eventMap[dateString]) {
          const event = eventMap[dateString];
          const link = document.createElement('a');
          link.href = event.url;
          link.textContent = dayCounter;
          dayContent.appendChild(link);
          cell.className = 'has-event';
        } else {
          dayContent.textContent = dayCounter;
        }
        
        dayCounter++;
      }
      
      cell.appendChild(dayContent);
      row.appendChild(cell);
    }
    
    tbody.appendChild(row);
  }
  
  table.appendChild(tbody);
  containerEl.appendChild(table);
}
