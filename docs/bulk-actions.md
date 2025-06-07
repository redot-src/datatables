# Bulk Actions

Bulk actions allow users to perform operations on multiple selected rows at once. This feature is implemented following the same patterns as individual row actions.

## Basic Usage

To add bulk actions to your datatable, define the `bulkActions()` method in your datatable class:

```php
use Redot\Datatables\Actions\BulkAction;

public function bulkActions(): array
{
    return [
        BulkAction::delete('users.bulk-delete'),
        BulkAction::export('users.bulk-export'),
        BulkAction::update('users.bulk-update'),
    ];
}
```

## Available Methods

### Static Factory Methods

- `BulkAction::delete()` - Creates a bulk delete action
- `BulkAction::export()` - Creates a bulk export action  
- `BulkAction::update()` - Creates a bulk update action
- `BulkAction::make()` - Creates a custom bulk action

### Configuration Methods

#### Basic Configuration
```php
BulkAction::make('Custom Action', 'fas fa-icon')
    ->label('Custom Label')           // Set action label
    ->icon('fas fa-custom-icon')      // Set action icon
    ->route('route.name')             // Set route
    ->method('post')                  // Set HTTP method (get, post, put, patch, delete)
    ->variant('outline-primary')      // Set button variant
```

#### Selection Constraints
```php
BulkAction::make('Send Notifications')
    ->minSelection(3)                 // Require minimum 3 items
    ->maxSelection(10)                // Allow maximum 10 items
```

#### Confirmation
```php
BulkAction::make('Delete Users')
    ->confirmable()                   // Enable confirmation
    ->confirmMessage('Delete :count users?') // Custom message (:count placeholder)
```

#### Visibility & Conditions
```php
BulkAction::make('Admin Action')
    ->visible(true)                   // Set visibility
    ->condition(fn() => auth()->user()->isAdmin()) // Conditional display
```

#### Additional Options
```php
BulkAction::make('Export')
    ->newTab()                        // Open in new tab
    ->parameters(['format' => 'xlsx']) // Additional route parameters
    ->body(['extra' => 'data'])       // Extra form data
```

## Button Variants

Available button variants:
- `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`
- `outline-primary`, `outline-secondary`, etc.

## Frontend Integration

### Selected Items Array

The selected items are automatically passed to your route handler as a `selected` array parameter:

```php
// Controller method
public function bulkDelete(Request $request)
{
    $selectedIds = $request->get('selected', []);
    
    User::whereIn('id', $selectedIds)->delete();
    
    return redirect()->back()->with('success', 'Users deleted successfully');
}
```

### JavaScript Events

The bulk actions integrate with the existing JavaScript confirmation system:

```javascript
// Custom confirmation handler (optional)
window.warnBeforeAction = function(callback, options) {
    // Your custom confirmation logic
    if (customConfirm(options.content)) {
        callback();
    }
};
```

## UI Features

### Selection UI
- Checkbox in table header for "select all" functionality
- Individual checkboxes for each row
- Selection counter showing number of selected items
- Clear selection button

### Bulk Actions Bar
- Appears when items are selected
- Shows selection count
- Displays bulk action buttons
- Includes clear selection button

## Complete Example

```php
<?php

namespace App\Datatables;

use App\Models\User;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Actions\BulkAction;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Datatable;

class UserDatatable extends Datatable
{
    protected string $model = User::class;

    public function columns(): array
    {
        return [
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable()->sortable(),
            // ... other columns
        ];
    }

    public function actions(): array
    {
        return [
            Action::view('users.show'),
            Action::edit('users.edit'),
            Action::delete('users.destroy'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            // Basic actions
            BulkAction::delete('users.bulk-delete')
                ->confirmMessage('Delete :count selected users?'),
                
            BulkAction::export('users.bulk-export'),

            // Custom actions
            BulkAction::make('Activate', 'fas fa-check')
                ->route('users.bulk-activate')
                ->method('patch')
                ->variant('outline-success'),

            BulkAction::make('Send Email', 'fas fa-envelope')
                ->route('users.bulk-email')
                ->minSelection(2)
                ->maxSelection(50)
                ->confirmable(),

            // Conditional action
            BulkAction::make('Admin Action', 'fas fa-crown')
                ->route('users.admin-action')
                ->condition(fn() => auth()->user()->isAdmin())
                ->variant('outline-warning'),
        ];
    }
}
```

## Controller Example

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function bulkDelete(Request $request)
    {
        $selectedIds = $request->get('selected', []);
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No users selected');
        }

        $deletedCount = User::whereIn('id', $selectedIds)->delete();
        
        return redirect()->back()->with('success', "Deleted {$deletedCount} users successfully");
    }

    public function bulkActivate(Request $request)
    {
        $selectedIds = $request->get('selected', []);
        
        $updatedCount = User::whereIn('id', $selectedIds)
            ->update(['status' => 'active']);
            
        return redirect()->back()->with('success', "Activated {$updatedCount} users successfully");
    }
}
```

## Routes Example

```php
// routes/web.php
Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
Route::patch('users/bulk-activate', [UserController::class, 'bulkActivate'])->name('users.bulk-activate');
Route::post('users/bulk-export', [UserController::class, 'bulkExport'])->name('users.bulk-export');
```

## Internationalization

Bulk action labels and messages support Laravel's localization system:

```php
// lang/en/datatables.php
'bulk_actions' => [
    'delete' => 'Bulk Delete',
    'export' => 'Bulk Export', 
    'update' => 'Bulk Update',
    'confirm' => 'Are you sure you want to perform this action on the selected items?',
    'select_all' => 'Select All',
    'deselect_all' => 'Deselect All',
    'selected_count' => ':count item(s) selected',
    'clear_selection' => 'Clear Selection',
],
```

Use translation keys in your bulk actions:

```php
BulkAction::make(__('Custom Action'))
    ->confirmMessage(__('Custom confirmation for :count items'))
``` 