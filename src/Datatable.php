<?php

namespace Redot\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Redot\Datatables\Columns\Column;
use Redot\Datatables\Filters\Filter;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Actions\ActionGroup;

abstract class Datatable extends Component
{
    use Macroable;
    use WithPagination;

    /**
     * Unique identifier for the datatable.
     *
     * @var string
     */
    public string $id;

    /**
     * Model bound to the datatable.
     *
     * @var string
     */
    public string $model;

    /**
     * The default per page options.
     *
     * @var array
     */
    public array $perPageOptions = [5, 10, 25, 50, 100, 250, 500];

    /**
     * The default per page value.
     *
     * @var int|string
     */
    #[Url]
    public int $perPage = 10;

    /**
     * Search term for the datatable.
     *
     * @var string
     */
    #[Url]
    public string $search = '';

    /**
     * Sort column for the datatable.
     *
     * @var string
     */
    #[Url]
    public string $sortColumn = '';

    /**
     * Sort direction for the datatable.
     *
     * @var string
     */
    #[Url]
    public string $sortDirection = 'asc';

    /**
     * Set the datatable maximum height.
     *
     * @var string
     */
    public string $height = 'auto';

    /**
     * Determine if the datatable has a sticky header.
     *
     * @var boolean
     */
    public bool $stickyHeader = true;

    /**
     * Create a new datatable instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->id ??= uniqid('datatable-');
    }

    /**
     * Get the query source of the datatable.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        if (isset($this->model)) {
            return app($this->model)->query();
        }

        throw new Exceptions\ResourceNotFoundException('Resource not found. Please set the model property in your datatable class.');
    }

    /**
     * Get the columns for the datatable.
     *
     * @return Column[]
     */
    abstract public function columns(): array;

    /**
     * Get the filters for the datatable.
     *
     * @return Filter[]
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Get the actions for the datatable.
     *
     * @return array<Action|ActionGroup>
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Sort the datatable by the given column.
     *
     * @param string $column
     * @return void
     */
    public function sort(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Get the pagination view.
     */
    public function paginationView(): string
    {
        return 'datatables::pagination.default';
    }

    /**
     * Get the pagination simple view.
     */
    public function paginationSimpleView(): string
    {
        return 'datatables::pagination.simple';
    }

    /**
     * Export the datatable to a XLSX file.
     *
     * @return void
     */
    public function toXlsx(): void
    {
        dd('Export to XLSX');
    }

    /**
     * Export the datatable to a CSV file.
     *
     * @return void
     */
    public function toCsv(): void
    {
        dd('Export to CSV');
    }

    /**
     * Export the datatable to a PDF file.
     *
     * @return void
     */
    public function toPdf(): void
    {
        dd('Export to PDF');
    }

    /**
     * Export the datatable to a JSON file.
     *
     * @return void
     */
    public function toJson(): void
    {
        dd('Export to JSON');
    }

    /**
     * Refresh the datatable.
     *
     * @return void
     */
    public function refresh(): void
    {
        $this->resetPage();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('datatables::datatable', $this->viewData());
    }

    /**
     * Get the view parameters.
     *
     * @return array
     */
    public function viewData(): array
    {
        $actions = $this->actions();
        $columns = $this->columns();
        $filters = $this->filters();

        $query = $this->getQueryBuilder($columns, $filters);
        $rows = $query->paginate($this->perPage);

        return [
            'columns' => $columns,
            'filters' => $filters,
            'actions' => $actions,

            'colspan' => $this->getColspanForColumns($columns),

            'searchable' => count(array_filter($columns, fn (Column $column) => $column->searchable)) > 0,
            'exportable' => count(array_filter($columns, fn (Column $column) => $column->exportable)) > 0,

            'rows' => $rows,
        ];
    }

    /**
     * Get the colspan for the columns.
     *
     * @param array $columns
     * @return int
     */
    protected function getColspanForColumns(array $columns): int
    {
        $colspan = count(array_filter($columns, fn (Column $column) => $column->visible));

        // Add one for the actions column
        if (count($this->actions()) > 0) {
            $colspan++;
        }

        return $colspan;
    }

    /**
     * Get eloquent query builder.
     *
     * @param array $columns
     * @param array $filters
     * @return Builder
     */
    protected function getQueryBuilder(array $columns, array $filters): Builder
    {
        $query = $this->query();

        $this->applyFilters($query, $filters);
        $this->applyGlobalSearch($query, $columns);
        $this->applySorting($query);

        return $query;
    }

    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter) {
            if (! $filter->isActive) {
                continue;
            }

            $query = $filter->apply($query);
        }

        return $query;
    }

    /**
     * Apply global search to the query.
     *
     * @param Builder $query
     * @param array $columns
     * @return void
     */
    protected function applyGlobalSearch(Builder $query, array $columns): void
    {
        if (! $this->search) {
            return;
        }

        $query->where(function ($query) use ($columns) {
            foreach ($columns as $column) {
                if (! $column->searchable) {
                    continue;
                }

                if (is_callable($column->searcher)) {
                    call_user_func($column->searcher, $query, $this->search);

                    continue;
                }

                if ($column->relationship) {
                    $this->searchWithinRelation($query, $column->name);
                } else {
                    $query->orWhere($column->name, 'like', '%' . $this->search . '%');
                }
            }
        });
    }

    /**
     * Search within relation.
     *
     * @param Builder $query
     * @param string $field
     * @return void
     */
    protected function searchWithinRelation(Builder $query, string $column): void
    {
        $relations = explode('.', $column);
        $column = array_pop($relations);

        foreach ($relations as $relation) {
            $query->orWhereHas($relation, function ($query) use ($column) {
                $query->where($column, 'like', '%' . $this->search . '%');
            });
        }
    }

    /**
     * Apply sorting to the query.
     *
     * @param Builder $query
     * @return void
     */
    protected function applySorting(Builder $query): void
    {
        if (! $this->sortColumn) {
            return;
        }

        // Find the column to sort by
        $column = Arr::first($this->columns(), function ($column) {
            return $column->sortable && $column->name === $this->sortColumn;
        });

        if (! $column) {
            throw new Exceptions\InvalidColumnException(sprintf('Could not find column with name "%s"', $this->sortColumn));
        }

        if ($column->sorter) {
            call_user_func($column->sorter, $query, $this->sortDirection);

            return;
        }

        if ($column->relationship) {
            $this->sortWithinRelation($query, $column->name);
        } else {
            $query->orderBy($column->name, $this->sortDirection);
        }
    }

    /**
     * Sort within relation.
     *
     * @param Builder $query
     * @param string $column
     * @return void
     */
    protected function sortWithinRelation(Builder $query, string $column): void
    {
        $relations = explode('.', $column);
        $field = array_pop($relations);

        $query->withAggregate($relations, $field);
        $query->orderBy(implode('_', $relations) . '_' . $field, $this->sortDirection);
    }
}
