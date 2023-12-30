<?php

namespace Redot\LivewireDatatable;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Datatable extends Component
{
    use WithPagination;

    /**
     * Datatable title.
     */
    public string $title = '';

    /**
     * Datatable subtitle.
     */
    public string $subtitle = '';

    /**
     * Fixed header.
     */
    public bool $fixedHeader = false;

    /**
     * Datatable max-height.
     */
    public string $maxHeight = '100%';

    /**
     * With trashed records.
     */
    public bool $withTrashed = false;

    /**
     * Only trashed records.
     */
    public bool $onlyTrashed = false;

    /**
     * Search term.
     */
    #[Url]
    public string $search = '';

    /**
     * Sort field.
     */
    #[Url]
    public string $sortField = 'id';

    /**
     * Sort direction.
     */
    #[Url]
    public string $sortDirection = 'desc';

    /**
     * Per page.
     */
    #[Url]
    public int $perPage = 10;

    /**
     * Per page options.
     */
    public array $perPageOptions = [10, 25, 50, 100];

    /**
     * Query builder.
     */
    abstract public function query(): Builder;

    /**
     * Data table columns.
     *
     * @return Column[]
     */
    abstract public function columns(): array;

    /**
     * Data table actions.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Data table header buttons.
     *
     * @return HeaderButton[]
     */
    public function headerButtons(): array
    {
        return [];
    }

    /**
     * Pagination view.
     */
    public function paginationView(): string
    {
        return config('livewire-datatable.templates.pagination.default');
    }

    /**
     * Simple pagination view.
     */
    public function paginationSimpleView(): string
    {
        return config('livewire-datatable.templates.pagination.simple');
    }

    /**
     * Datatable template.
     */
    public function template(): string
    {
        return config('livewire-datatable.templates.datatable');
    }

    /**
     * Reset page number when searching.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset page number when sorting.
     */
    public function updatingSortField(): void
    {
        $this->resetPage();
    }

    /**
     * Reset page number when changing per page.
     */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Apply trashed to the query.
     */
    public function applyTrashed(Builder $query): void
    {
        if ($this->withTrashed) {
            $query->withTrashed();
        }

        if ($this->onlyTrashed) {
            $query->onlyTrashed();
        }
    }

    /**
     * Apply search and sort to the query.
     */
    public function applySearch(Builder $query): void
    {
        if ($this->search === '') {
            return;
        }

        $query->where(function ($query) {
            foreach ($this->columns() as $column) {
                if ($column->searchable === true && $column->field !== null) {
                    if (! is_callable($column->where)) {
                        $query->orWhere($column->field, 'like', '%'.$this->search.'%');

                        continue;
                    }

                    $callable = $column->where;
                    $callable($query, $this->search);
                }
            }
        });
    }

    /**
     * Sort the query.
     */
    public function applySort(Builder $query): void
    {
        if ($this->sortField === '') {
            return;
        }

        $query->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Toggle sort direction.
     */
    public function sort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Build the component params.
     */
    public function params(): array
    {
        $params['columns'] = $this->columns();
        $params['actions'] = array_filter($this->actions(), fn ($action) => $action->allowed);
        $params['headerButtons'] = array_filter($this->headerButtons(), fn ($button) => $button->allowed);

        $query = $this->query();

        $this->applyTrashed($query);
        $this->applySearch($query);
        $this->applySort($query);

        $params['rows'] = $query->paginate($this->perPage);

        $searchables = array_filter($this->columns(), fn ($column) => $column->searchable === true);
        $params['searchable'] = count($searchables) > 0;

        // If there is a title, subtitle, search or header buttons, then the header is visible.
        $params['headerable'] = $this->title || $this->subtitle || $params['searchable'] || count($params['headerButtons']) > 0;

        return $params;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view($this->template(), $this->params());
    }
}
