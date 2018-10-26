import Sortable from 'sortablejs';

// Get all lists on page.
let lists = document.querySelectorAll('.sortable');

// Go through each list found on page.
if (lists.length > 0) {
  for (let i = 0; i < lists.length; ++i) {
    // Make list sortable.
    Sortable.create(lists[i]);
  }
}
