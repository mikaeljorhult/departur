const calendarURL = document.getElementById('calendar-url');
let typeSelector;

if (calendarURL) {
    typeSelector = calendarURL.getElementsByTagName('select')[0];
    typeSelector.addEventListener('change', toggleFileClass);
    toggleFileClass();
}

function toggleFileClass() {
    calendarURL.classList.toggle('calendar-url__file', typeSelector.value.match('-file'));
}
