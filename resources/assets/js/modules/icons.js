import fontawesome from '@fortawesome/fontawesome';
import { faCalendarAlt, faColumns, faPowerOff, faUser } from '@fortawesome/fontawesome-free-solid';

// Setup available icons.
const icons = {
  calendar: fontawesome.icon(faCalendarAlt),
  columns: fontawesome.icon(faColumns),
  logout: fontawesome.icon(faPowerOff),
  user: fontawesome.icon(faUser),
};

// Get all icon elements on page.
const elements = document.querySelectorAll('i.icon');

// Go through and insert each icon to the DOM.
elements.forEach(function (htmlNode) {
  let icon = htmlNode.getAttribute('data-icon');

  if (icons[icon] !== undefined) {
    htmlNode.innerHTML = icons[icon].html;
  }
});
