document.addEventListener('alpine:init', () => {
    Alpine.data('datatables', () => ({
        // Any existing AlpineJS data and methods would go here
        // For now, we are adding Livewire event listeners outside Alpine component,
        // or they can be within if controlling Alpine-specific elements.
    }));
});

// Listener for opening a URL in a new tab
document.addEventListener('open-new-tab', event => {
    const url = event.detail.url;
    const confirmMessage = event.detail.confirmMessage;

    if (!url) {
        console.error('No URL provided for open-new-tab event.');
        return;
    }

    if (confirmMessage) {
        if (confirm(confirmMessage)) {
            window.open(url, '_blank');
        }
    } else {
        window.open(url, '_blank');
    }
});

// Listener for executing a bulk action
document.addEventListener('bulk-action-execute', event => {
    const { url, method, body, token, confirmMessage, newTab } = event.detail;

    if (!url || !method) {
        console.error('URL or method not provided for bulk-action-execute event.');
        return;
    }

    const performAction = () => {
        if (method.toLowerCase() === 'get') {
            // GET requests are typically handled by redirect or open-new-tab directly.
            // This path might be for GETs that needed confirmation first.
            if (newTab) {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
            return;
        }

        // For non-GET methods, create a dynamic form and submit it.
        const form = document.createElement('form');
        form.method = method;
        form.action = url;
        form.style.display = 'none'; // Hide the form

        // Add CSRF token
        if (token) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = token;
            form.appendChild(csrfInput);
        }

        // Add method spoofing for PUT, PATCH, DELETE if not 'POST'
        if (method.toLowerCase() !== 'post') {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = method.toUpperCase();
            form.appendChild(methodInput);
        }

        // Add body data (e.g., selected_ids)
        if (body && typeof body === 'object') {
            for (const key in body) {
                if (Object.prototype.hasOwnProperty.call(body, key)) {
                    const value = body[key];
                    if (Array.isArray(value)) {
                        value.forEach(item => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `${key}[]`; // PHP array convention
                            input.value = item;
                            form.appendChild(input);
                        });
                    } else {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    }
                }
            }
        }

        if (newTab) {
            form.target = '_blank'; // Open form submission in new tab
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form); // Clean up the form
    };

    if (confirmMessage) {
        if (confirm(confirmMessage)) {
            performAction();
        }
    } else {
        performAction();
    }
});

// Initialize action handlers for existing individual actions (if any were using similar JS before)
// This part is more about ensuring compatibility with any pre-existing JS for single actions.
// The original 'action.js' or similar logic might have been:
// document.querySelectorAll('.datatable-action[method="delete"]').forEach(button => { ... });
// For now, we focus on the new bulk action events.

// Ensure Livewire scripts are loaded before this, or wrap in a DOMContentLoaded listener
// if these events are dispatched very early. However, Livewire events are typically fine.

// The Alpine.data part is a placeholder if this file is also used for Alpine components.
// If not, it can be removed. Given it's `datatables.js`, it might be.
// Let's assume it's fine to keep it as a harmless structure.
// If there was prior JS in this file, it should be merged carefully.
// Since it was empty, this new content is fine.
