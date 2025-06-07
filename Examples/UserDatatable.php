<?php

namespace App\Datatables;

use App\Models\User;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Actions\BulkAction;
use Redot\Datatables\Columns\TextColumn;
use Redot\Datatables\Columns\DateColumn;
use Redot\Datatables\Columns\BadgeColumn;
use Redot\Datatables\Datatable;

class UserDatatable extends Datatable
{
    protected string $model = User::class;

    public function columns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable(),

            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'active' => 'success',
                    'inactive' => 'danger',
                ]),

            DateColumn::make('created_at')
                ->label('Created At')
                ->sortable(),
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
            // Basic bulk delete action
            BulkAction::delete('users.bulk-delete')
                ->confirmMessage('Are you sure you want to delete :count selected users?'),

            // Bulk export action
            BulkAction::export('users.bulk-export')
                ->variant('outline-success'),

            // Custom bulk action to activate users
            BulkAction::make('Activate Users', 'fas fa-check-circle')
                ->route('users.bulk-activate')
                ->method('patch')
                ->variant('outline-success')
                ->confirmMessage('Are you sure you want to activate :count selected users?'),

            // Custom bulk action to deactivate users
            BulkAction::make('Deactivate Users', 'fas fa-ban')
                ->route('users.bulk-deactivate')
                ->method('patch')
                ->variant('outline-warning')
                ->confirmMessage('Are you sure you want to deactivate :count selected users?'),

            // Bulk action with minimum selection requirement
            BulkAction::make('Send Notifications', 'fas fa-envelope')
                ->route('users.bulk-notify')
                ->method('post')
                ->variant('outline-info')
                ->minSelection(3)
                ->confirmMessage('Are you sure you want to send notifications to :count selected users?'),

            // Bulk action with maximum selection limit
            BulkAction::make('Assign to Project', 'fas fa-project-diagram')
                ->route('users.bulk-assign-project')
                ->method('post')
                ->variant('outline-primary')
                ->maxSelection(10)
                ->confirmMessage('Are you sure you want to assign :count selected users to the project?'),

            // Conditional bulk action
            BulkAction::make('Archive Users', 'fas fa-archive')
                ->route('users.bulk-archive')
                ->method('patch')
                ->variant('outline-secondary')
                ->condition(fn() => auth()->user()->can('archive-users'))
                ->confirmMessage('Are you sure you want to archive :count selected users?'),
        ];
    }
} 