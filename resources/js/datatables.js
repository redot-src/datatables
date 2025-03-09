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