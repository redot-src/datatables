# Redot Datatables

Datatables designed for Redot Dashboard, documentation and examples can be found [here](https://docs.redothub.com/).

## Defining Bulk Actions

In addition to per-row actions, you can define bulk actions that operate on multiple selected rows. To do this, implement the `bulkActions()` method in your Datatable component. This method should return an array of `Redot\\Datatables\\Actions\\BulkAction` instances.

```php
use Redot\\Datatables\\Actions\\BulkAction;

// Inside your Datatable class
public function bulkActions(): array
{
    return [
        BulkAction::make(name: 'delete', label: 'Delete Selected', icon: 'fas fa-trash')
            ->route('users.bulk-delete') // Your route name for handling this
            ->method('POST')
            ->confirmable(true, 'Are you sure you want to delete the selected users?'),

        BulkAction::make(name: 'export', label: 'Export Selected', icon: 'fas fa-file-export')
            ->route('users.bulk-export')
            ->method('POST'), // Or GET if your export can handle many IDs in query params
            // ->newTab(true) // If method is GET and should open in new tab
    ];
}
```

Each `BulkAction` can be configured with:
-   `name(string $name)`: A unique identifier for the action (required).
-   `label(string $label)`: The text displayed for the action.
-   `icon(string $icon)`: A CSS class for an icon (e.g., Font Awesome).
-   `route(string $routeName, array $parameters = [])`: The named route to handle this action.
-   `method(string $method)`: The HTTP method to use (e.g., 'POST', 'GET'). Defaults to 'POST'.
-   `confirmable(bool $confirmable = true, ?string $message = null)`: If true, a confirmation dialog will be shown before executing the action. You can provide a custom message.
-   `newTab(bool $newTab = true)`: If true (and typically for GET requests), the action will attempt to open in a new browser tab.
-   `condition(Closure $condition)`: A closure that receives an array of selected row IDs and returns `true` or `false` to determine if the action should be rendered.

When rows are selected in the table, a "Bulk Actions" dropdown will appear, listing the actions you've defined.

## Handling Bulk Action Routes

When a bulk action is triggered, the component will typically make a request to the route you specified. For `POST`, `PUT`, `PATCH`, or `DELETE` methods, the selected row IDs will be included in the request body as an array under the key `selected_ids`.

Here's an example of how you might define a route and controller method in Laravel to handle a bulk delete action:

**1. Define the Route (e.g., in `routes/web.php`):**
```php
// routes/web.php
use App\\Http\\Controllers\\UserController;

Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
```

**2. Create the Controller Method (e.g., in `app/Http/Controllers/UserController.php`):**
```php
// app/Http/Controllers/UserController.php
namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;
use App\\Models\\User; // Assuming you have a User model

class UserController extends Controller
{
    public function bulkDelete(Request $request)
    {
        $selectedIds = $request->input('selected_ids');

        if (empty($selectedIds)) {
            return back()->with('error', 'No items selected for deletion.');
        }

        // Perform the delete operation
        User::whereIn('id', $selectedIds)->delete();

        return back()->with('success', 'Selected users have been deleted.');
    }
}
```
Make sure your route and controller logic match the requirements of your application. For GET requests used in bulk actions, `selected_ids` and any other parameters from the `body()` method of the `BulkAction` will be appended as query parameters to the URL.

## Client-Side Event Handling (Advanced)

The datatable dispatches client-side JavaScript events for further customization:
-   `open-new-tab`: Dispatched when an action (per-row or bulk) configured with `->newTab()` and usually method GET is triggered. Contains `event.detail.url` and `event.detail.confirmMessage`.
-   `bulk-action-execute`: Dispatched for bulk actions. Contains `event.detail` with `url`, `method`, `body` (including `selected_ids`), `token`, `confirmMessage`, and `newTab` properties. The default JavaScript handler in `datatables.js` uses this to perform dynamic form submissions for non-GET requests or handle confirmations. You can listen to this event to implement custom client-side logic.

---
