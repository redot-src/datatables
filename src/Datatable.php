<?php

namespace Redot\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Redot\Datatables\Contracts\Datatable as DatatableContract;
use Redot\Datatables\Columns\Column;
use Redot\Datatables\Filters\Filter;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Actions\ActionGroup;

abstract class Datatable extends Component implements DatatableContract
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

        $colspan = count(array_filter($columns, fn (Column $column) => $column->visible));
        $colspan += empty($actions) ? 0 : 1;

        $searchable = array_filter($columns, fn (Column $column) => $column->searchable);
        $searchable = empty($searchable) ? false : true;

        $exportable = array_filter($columns, fn (Column $column) => $column->exportable);
        $exportable = empty($exportable) ? false : true;

        $query = $this->query();
        $filters = $this->filters();

        // Apply filters
        foreach ($filters as $filter) {
            if (! $filter->isActive) {
                continue;
            }

            $query = $filter->apply($query);
        }

        // Apply global search
        $fields = array_map(fn (Column $column) => $column->searchable ? $column->name : null, $columns);
        $fields = array_filter($fields);

        $query->when($this->search, fn ($query, $search) => search_model($query, $fields, $search));

        // Get the rows with pagination
        $rows = $query->paginate($this->perPage);

        return [
            'columns' => $columns,
            'colspan' => $colspan,

            'searchable' => $searchable,
            'exportable' => $exportable,

            'filters' => $filters,
            'rows' => $rows,

            'actions' => $actions,
        ];
    }
}
