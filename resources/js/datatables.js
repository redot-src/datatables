// Deattach dropdown menu from datatable-actions dropdown
$(document).on('show.bs.dropdown', '.datatable-actions .dropdown', (event) => {
    const $dropdown = $(event.target).closest('.dropdown');
    const $menu = $dropdown.find('.dropdown-menu');

    // Append the dropdown menu to the body
    $menu.appendTo('body');
});

// Handle datatable action click
$(document).on('click', '.datatable-action[method]:not([method="get"])', (event) => {
    event.preventDefault();

    // Get the action element
    const $action = $(event.target).closest('.datatable-action');

    // Define the callback function
    const callback = function () {
        const $form = $(`<form action="${$action.attr('href')}" method="POST" disable-validation></form>`);

        // Spoof the form method
        $form.append(`<input type="hidden" name="_method" value="${$action.attr('method')}">`);
        $form.append(`<input type="hidden" name="_token" value="${$action.attr('token')}">`);

        // Get request body
        let body = JSON.parse(atob($action.attr('request-body')));

        // Append request body to the form
        if (body && typeof body === 'object') {
            Object.entries(body).forEach(([key, value]) => {
                if (Array.isArray(value)) {
                    value.forEach((item) => {
                        $form.append(`<input type="hidden" name="${key}[]" value="${item}">`);
                    });
                } else {
                    $form.append(`<input type="hidden" name="${key}" value="${value}">`);
                }
            });
        }

        $form.appendTo('body').submit();
    };

    // Early return if no confirmation is required
    if ($action.hasAttr('confirm') === false) {
        return callback();
    }

    // Use warnBeforeAction if available
    if (typeof warnBeforeAction !== 'undefined') {
        return warnBeforeAction(callback, { content: $action.attr('confirm') });
    }

    // Fallback to native confirm
    if (confirm($action.attr('confirm'))) {
        callback();
    }
});

// Handle bulk action click
$(document).on('click', '.datatable-bulk-action[method]:not([method="get"])', (event) => {
    event.preventDefault();

    // Get the action element
    const $action = $(event.target).closest('.datatable-bulk-action');
    
    // Get the selected items from the Livewire component
    const $datatableComponent = $action.closest('.datatable');
    const livewireComponent = Livewire.find($datatableComponent.attr('wire:id'));
    const selectedItems = livewireComponent.get('selected');

    // Check if items are selected
    if (!selectedItems || selectedItems.length === 0) {
        alert('No items selected');
        return;
    }

    // Check minimum selection
    const minSelection = parseInt($action.attr('min-selection')) || 1;
    if (selectedItems.length < minSelection) {
        alert(`Please select at least ${minSelection} item(s)`);
        return;
    }

    // Check maximum selection
    const maxSelection = parseInt($action.attr('max-selection'));
    if (maxSelection && selectedItems.length > maxSelection) {
        alert(`Please select no more than ${maxSelection} item(s)`);
        return;
    }

    // Define the callback function
    const callback = function () {
        const $form = $(`<form action="${$action.attr('href')}" method="POST" disable-validation></form>`);

        // Spoof the form method
        $form.append(`<input type="hidden" name="_method" value="${$action.attr('method')}">`);
        $form.append(`<input type="hidden" name="_token" value="${$action.attr('token')}">`);

        // Add selected items
        selectedItems.forEach((item) => {
            $form.append(`<input type="hidden" name="selected[]" value="${item}">`);
        });

        // Get request body
        const requestBodyAttr = $action.attr('request-body');
        if (requestBodyAttr) {
            let body = JSON.parse(atob(requestBodyAttr));

            // Append request body to the form
            if (body && typeof body === 'object') {
                Object.entries(body).forEach(([key, value]) => {
                    if (Array.isArray(value)) {
                        value.forEach((item) => {
                            $form.append(`<input type="hidden" name="${key}[]" value="${item}">`);
                        });
                    } else {
                        $form.append(`<input type="hidden" name="${key}" value="${value}">`);
                    }
                });
            }
        }

        $form.appendTo('body').submit();
    };

    // Early return if no confirmation is required
    if ($action.hasAttr('confirm') === false) {
        return callback();
    }

    // Use warnBeforeAction if available
    if (typeof warnBeforeAction !== 'undefined') {
        return warnBeforeAction(callback, { 
            content: $action.attr('confirm').replace(':count', selectedItems.length)
        });
    }

    // Fallback to native confirm
    if (confirm($action.attr('confirm').replace(':count', selectedItems.length))) {
        callback();
    }
});