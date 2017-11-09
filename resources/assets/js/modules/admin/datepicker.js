import flatpickr from 'flatpickr';

const datepickers = document.getElementsByClassName('datepicker');

// Check if datepicker is present on current page.
if (datepickers.length > 0) {
    const datepicker = datepickers[0];
    const startDate = datepicker.querySelectorAll('[name="start_date"]')[0];
    const endDate = datepicker.querySelectorAll('[name="end_date"]')[0];

    // Create and append a label for new input.
    let dateLabel = document.createElement('label');
    dateLabel.innerHTML = datepicker.getAttribute('data-label');
    datepicker.insertAdjacentElement('afterbegin', dateLabel);

    // Setup flatpickr on start date.
    let startPicker = flatpickr(startDate, {
        defaultDate: startDate.value || 'today',
        locale: {
            firstDayOfWeek: 1
        },
        onChange: function (selectedDate) {
            // End date must be after start date.
            endPicker.set('minDate', selectedDate[0]);

            // Check if current end date was before start date.
            if (endPicker.selectedDates.length === 0) {
                let newEndDate = addMonths(selectedDate[0], 3);

                // Set new end date three months into the future.
                endPicker.setDate(newEndDate);
            }
        }
    });

    // Setup flatpickr on end date.
    let endPicker = flatpickr(endDate, {
        defaultDate: endDate.value || addMonths(startPicker.selectedDates[0], 3),
        locale: {
            firstDayOfWeek: 1
        }
    });
}

function addMonths(date, months) {
    return date.setMonth(date.getMonth() + months);
}