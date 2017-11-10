let basicModal = require('basicmodal');

document.addEventListener('click', function (event) {
    let targetElement = event.target;

    // If clicked element is delete button.
    if (targetElement.classList.contains('resource-delete')) {
        // Show modal for confirming deletion.
        basicModal.show({
            body: '<p>Are you sure you want to delete the resource?</p>',
            buttons: {
                cancel: {
                    title: 'Cancel',
                    fn: basicModal.close
                },
                action: {
                    title: 'Delete',
                    fn: function () {
                        // Submit form to delete resource.
                        targetElement.parentNode.submit();
                    }
                }
            }
        });

        // Prevent default behaviour.
        event.preventDefault();
    }
});