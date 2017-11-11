const calendarURL = document.getElementById('calendar-url');
const typeSelector = calendarURL.getElementsByTagName('select')[0];

if (calendarURL) {
    typeSelector.addEventListener('change', toggleFileClass);
    toggleFileClass();
}

function toggleFileClass() {
    calendarURL.classList.toggle('calendar-url__file', typeSelector.value.match('-file'));
}
