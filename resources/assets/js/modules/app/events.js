document.addEventListener('click', function (event) {
  let targetElement = event.target;

  // If clicked element is read more link.
  if (targetElement.classList.contains('event-readmore')) {
    // Remove class truncating description.
    targetElement.parentElement.classList.remove('event__truncated');

    // Prevent default behaviour.
    event.preventDefault();
  }
});
