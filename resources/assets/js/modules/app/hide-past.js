const hidePast = document.getElementById('hide-past-toggle');

// If hide past events checkbox is present.
if (hidePast) {
    // Set initial state if previously saved.
    let initialState = JSON.parse(localStorage.getItem('hide-past'));
    hidePast.checked = initialState;
    handleHidePast(initialState);

    // Attach click handler.
    hidePast.addEventListener('click', function (event) {
        handleHidePast(this.checked);

        // Stop event from propagating.
        event.stopPropagation();
    });
}

function handleHidePast(checked) {
    // Class to HTML element to hide past dates.
    document.documentElement.classList.toggle('hide-past', checked);

    // Store state in local storage.
    localStorage.setItem('hide-past', JSON.stringify(checked));
}
