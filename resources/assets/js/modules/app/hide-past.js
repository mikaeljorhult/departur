const hidePast = document.getElementById('hide-past-toggle');

// If hide past events checkbox is present.
if (hidePast) {
    // Attach click handler.
    hidePast.addEventListener('click', function (event) {
        document.documentElement.classList.toggle('hide-past', this.checked);

        // Stop event from propagating.
        event.stopPropagation();
    });
}
