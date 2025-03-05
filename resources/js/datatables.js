document.addEventListener('DOMContentLoaded', function () {
    // Deattach dropdown menu from datatable-actions dropdown
    $(document).on('show.bs.dropdown', '.datatable-actions .dropdown', (event) => {
        const $dropdown = $(event.target).closest('.dropdown');
        const $menu = $dropdown.find('.dropdown-menu');

        $dropdown.attr('unique-id', `dropdown-${Date.now()}`);
        $menu.attr('unique-id', $dropdown.attr('unique-id'));

        // Append the dropdown menu to the body
        $menu.appendTo('body');
    });

    // Reattach dropdown menu to datatable-actions dropdown
    $(document).on('hidden.bs.dropdown', '.datatable-actions .dropdown', (event) => {
        const $dropdown = $(event.target).closest('.dropdown');
        const $menu = $(`.dropdown-menu[unique-id="${$dropdown.attr('unique-id')}"]`);

        $menu.appendTo($dropdown);
    });

    // Handle datatable action click
    $(document).on('click', '.datatable-action[method]:not([method="get"])', (event) => {
        event.preventDefault();

        // Get the action element
        const $action = $(event.target).closest('.datatable-action');

        // Define the callback function
        const callback = function () {
            const $form = $(`<form action="${$action.attr('href')}" method="POST"></form>`);

            // Spoof the form method
            $form.append(`<input type="hidden" name="_method" value="${$action.attr('method')}">`);
            $form.append(`<input type="hidden" name="_token" value="${$action.attr('token')}">`);

            $form.appendTo('body').submit();
        };

        if ($action.hasAttr('confirm')) {
            warnBeforeAction(callback, { content: $action.attr('confirm') });
        } else {
            callback();
        }
    });
});