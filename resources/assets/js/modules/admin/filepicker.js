const calendarURL = document.getElementById('calendar-url');
let fileInput;
let fileName;
let typeSelector;

if (calendarURL) {
    fileInput = calendarURL.querySelector('input[type="file"]');
    fileName = calendarURL.querySelector('.file-name');
    typeSelector = calendarURL.getElementsByTagName('select')[0];

    fileInput.addEventListener('change', showFileName);

    typeSelector.addEventListener('change', toggleFileClass);
    toggleFileClass();
}

function showFileName() {
    fileName.innerHTML = this.files.length > 0 ? this.files[0].name : this.getAttribute('placeholder');
}

function toggleFileClass() {
    calendarURL.classList.toggle('calendar-url__file', typeSelector.value.match('-file'));
}
