# Livewire Datatable

Livewire Datatable is a package that allows you to create a table with sorting, searching, and pagination without having to write a lot of code. It is built on top of [Livewire](https://laravel-livewire.com/) and uses [Tabler](https://tabler.io/) for the UI.

**Note:** This package doesn't include any CSS or JS files. You need to install [Tabler](https://tabler.io/) yourself, or publish the views and modify them to use your own CSS and JS files.

**Another Note:** This packages designed to work with [Redot Dashboard](https://github.com/redot-src/laravel-dashboard), but it can be used as a standalone package.

## Installation

You can install the package via composer:

```bash
composer require redot/livewire-datatable
```

Now you can run the following command to scaffold a new Datatable:

```bash
php artisan make:datatable UsersTable
```

## Configuration

If you wish, you can publish the views and modify them to use your own CSS and JS files:

```bash
php artisan vendor:publish --tag=livewire-datatable-views
```

You can also publish the language files:

```bash
php artisan vendor:publish --tag=livewire-datatable-lang
```

## Usage

Let's say you have a `User` model and you want to display a table of users. First, you need to create a Livewire component that extends the `Redot\LivewireDatatable\Datatable` class:

```php
<?php

namespace App\Http\Livewire;

use Redot\LivewireDatatable\Datatable;

class UsersTable extends Datatable
{
    // ...
}
```

Next, you need to define the columns that you want to display in the table. You can do this by overriding the `columns` method:

```php
use Redot\LivewireDatatable\Column;

class UsersTable extends Datatable
{
    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Username')
                ->resolve(fn ($row) => Str::slug($row->name)),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => $value->format('d/m/Y H:i:s'))
        ];
    }
}
```

The `Column` class has a `make` method that accepts two arguments: the column name and the column key. The column key is the name of the column in the database.

Each column has a `sortable`, `searchable`, and `format` method. The `sortable` method adds a sort button to the column header. The `searchable` method adds the column to the search query. The `format` method accepts a closure that receives the value of the column and should return the formatted value.

You can also define a column without a column key. In this case, you need to define a `resolve` method that accepts a closure. The closure receives the current row as an argument and should return the value of the column.

Next, you need to define the query that will be used to fetch the data. You can do this by overriding the `query` method:

```php
use App\Models\User;

class UsersTable extends Datatable
{
    // ...

    public function query(): Builder
    {
        return User::query();
    }
}
```

(Optional) You can also define the actions that will be displayed in the table. You can do this by overriding the `actions` method:

```php
use Redot\LivewireDatatable\Action;

class UsersTable extends Datatable
{
    // ...

    public function actions(): array
    {
        return [
            Action::view('users.show'),
            Action::edit('users.edit'),
            Action::delete('users.destroy'),
        ];
    }
}
```

The `Action` class has a `view`, `edit`, and `delete` method that accepts the route name as an argument. The route name should contain the model key as a route parameter.

Finally, you need to render the table in your view:

```html
<div>
    <livewire:users-table />
</div>
```

## Dependencies

This package depends on the following packages:

- [Livewire](https://laravel-livewire.com/) - Laravel Livewire is a full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel.
- [Tabler](https://tabler.io/) - Tabler is free and open-source HTML Dashboard UI Kit built on Bootstrap 5.

## Conclusion

That's it! You now have a table with sorting, searching, and pagination. You can check out the code to see how it works, until we have proper documentation. If you have any questions or suggestions, feel free to open an issue on GitHub.
