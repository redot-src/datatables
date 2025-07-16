<?php

namespace Redot\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Js;
use Illuminate\Support\Traits\Macroable;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Redot\Datatables\Actions\Action;
use Redot\Datatables\Actions\ActionGroup;
use Redot\Datatables\Adapters\PDF\Adabter;
use Redot\Datatables\Columns\Column;
use Redot\Datatables\Filters\Filter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Datatable extends Component
{
    use Macroable;
    use WithPagination;

    /**
     * Unique identifier for the datatable.
     */
    public string $id;

    /**
     * Model bound to the datatable.
     */
    protected string $model;

    /**
     * The default per page options.
     */
    public array $perPageOptions = [5, 10, 25, 50, 100, 250, 500];

    /**
     * The default per page value.
     */
    #[Url]
    public int $perPage = 10;

    /**
     * Search term for the datatable.
     */
    #[Url(as: 'q')]
    public string $search = '';

    /**
     * Sort column for the datatable.
     */
    #[Url(as: 'sort')]
    public string $sortColumn = '';

    /**
     * Sort direction for the datatable.
     */
    #[Url(as: 'direction')]
    public string $sortDirection = 'desc';

    /**
     * Filters values for the datatable.
     */
    #[Url(as: 'filter')]
    public array $filtered = [];

    /**
     * Set the datatable maximum height.
     */
    public string $height = 'auto';

    /**
     * Determine if the datatable has a sticky header.
     */
    public bool $stickyHeader = true;

    /**
     * Determine if the datatable is bordered.
     */
    public bool $bordered = true;

    /**
     * Allowed export formats.
     */
    public array $allowedExports;

    /**
     * PDF view template.
     */
    public string $pdfTemplate;

    /**
     * PDF adapter class.
     */
    public string $pdfAdapter;

    /**
     * PDF adapter options.
     */
    public array $pdfOptions = [];

    /**
     * Set the datatable empty message.
     */
    public ?string $emptyMessage = null;

    /**
     * JavaScript assets url.
     */
    public string $jsAssetsUrl;

    /**
     * CSS assets url.
     */
    public string $cssAssetsUrl;

    /**
     * Create a new datatable instance.
     */
    public function __construct()
    {
        $this->id ??= uniqid('datatable-');
        $this->emptyMessage ??= __('datatables::datatable.empty');

        // Set the PDF adapter and options
        $this->pdfTemplate ??= config('datatables.export.pdf.template');
        $this->pdfAdapter ??= config('datatables.export.pdf.adapter');
        $this->pdfOptions = array_merge(config('datatables.export.pdf.options'), $this->pdfOptions);

        // Set the assets urls
        $this->cssAssetsUrl = route(config('datatables.assets.css.route'), ['v' => md5(filemtime(config('datatables.assets.css.file')))]);
        $this->jsAssetsUrl = route(config('datatables.assets.js.route'), ['v' => md5(filemtime(config('datatables.assets.js.file')))]);

        // Set the allowed export formats
        $this->allowedExports = array_keys(array_filter(config('datatables.export'), fn($export) => $export['enabled']));
    }

    /**
     * Get the query source of the datatable.
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
     */
    abstract public function columns(): array;

    /**
     * Get the actions for the datatable.
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Get the default action group for the datatable.
     */
    public static function defaultActionGroup(array $actions, ?string $label = null, ?string $icon = null): array
    {
        // Display first two actions directly, group the rest if there are more than 3 total
        $mainActions = array_slice($actions, 0, 2);
        $remainingActions = array_slice($actions, 2);

        // If we have 3 actions total, just show all of them directly
        if (count($actions) <= 3) {
            return $actions;
        }

        // Otherwise, show first two and group the rest
        return array_merge(
            $mainActions,
            [ActionGroup::make($label, $icon ?? 'fas fa-ellipsis-v')->actions($remainingActions)]
        );
    }

    /**
     * Get the filters for the datatable.
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Sort the datatable by the given column.
     */
    public function sort(?string $column = null): void
    {
        if ($column === null) {
            $this->sortColumn = '';
            $this->sortDirection = 'asc';

            return;
        }

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
     * Export the datatable to a XLSX file.
     */
    public function toXlsx(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (! class_exists('Maatwebsite\Excel\Excel')) {
            throw new Exceptions\MissingDependencyException('Please install the "maatwebsite/excel" package to use the toXlsx method.');
        }

        [$headings, $rows] = $this->getExportData();

        $filename = sprintf('export-%s.xlsx', now()->format('Y-m-d_H-i-s'));
        $rows->prepend($headings)->storeExcel($filename, null, 'Xlsx');

        return response()->download(storage_path('app/' . $filename))->deleteFileAfterSend(true);
    }

    /**
     * Export the datatable to a CSV file.
     */
    public function toCsv(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (! class_exists('Maatwebsite\Excel\Excel')) {
            throw new Exceptions\MissingDependencyException('Please install the "maatwebsite/excel" package to use the toCsv method.');
        }

        [$headings, $rows] = $this->getExportData();

        $filename = sprintf('export-%s.csv', now()->format('Y-m-d_H-i-s'));
        $rows->prepend($headings)->storeExcel($filename, null, 'Csv');

        return response()->download(storage_path('app/' . $filename))->deleteFileAfterSend(true);
    }

    /**
     * Export the datatable to a JSON file.
     */
    public function toJson(): StreamedResponse
    {
        [$headings, $rows] = $this->getExportData();

        $items = $rows->map(fn($row) => array_combine($headings, $row))->toArray();
        $filename = sprintf('export-%s.json', now()->format('Y-m-d_H-i-s'));
        $flags = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(fn() => print Js::encode($items, $flags), $filename, $headers);
    }

    /**
     * Export the datatable to a PDF file.
     */
    public function toPdf(): StreamedResponse|Response
    {
        $pdfAdapter = new $this->pdfAdapter;

        if (! $pdfAdapter instanceof Adabter || ! $pdfAdapter->supported()) {
            throw new Exceptions\MissingDependencyException(sprintf('The PDF adapter "%s" is not supported.', $this->pdfAdapter));
        }

        [$headings, $rows] = $this->getExportData();

        return $pdfAdapter->download($this->pdfTemplate, $headings, $rows, $this->pdfOptions);
    }

    /**
     * Get export data.
     */
    protected function getExportData(): array
    {
        $columns = array_filter($this->columns(), fn(Column $column) => $column->exportable && $column->visible);
        $headings = array_column($columns, 'label');

        $rows = $this->getQueryBuilder($columns, $this->filters())->get();
        $rows = $rows->map(fn($row) => array_map(fn(Column $column) => $column->get($row), $columns));

        return [$headings, $rows];
    }

    /**
     * Refresh the datatable.
     */
    public function refresh(): void
    {
        $this->resetPage();
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('datatables::datatable', $this->viewData());
    }

    /**
     * Get the view parameters.
     */
    public function viewData(): array
    {
        // Reset filters counter for each render
        Filter::$counter = 0;

        $columns = $this->getVisibleColumns();
        $actions = $this->getVisibleActions();
        $filters = $this->filters();

        // Build the query and get the rows
        $query = $this->getQueryBuilder($columns, $filters);
        $rows = $query->paginate($this->perPage);

        return [
            'columns' => $columns,
            'filters' => $filters,
            'actions' => $actions,

            'colspan' => $this->getColspanForColumns($columns, $actions),
            'filtersOpen' => count($this->filtered) > 0,

            'filterable' => count($filters) > 0,
            'searchable' => count(array_filter($columns, fn(Column $column) => $column->searchable)) > 0,
            'exportable' => count($this->allowedExports) > 0 && count(array_filter($columns, fn(Column $column) => $column->exportable)) > 0,

            'rows' => $rows,
        ];
    }

    /**
     * Get the visible columns.
     */
    protected function getVisibleColumns(): array
    {
        return array_filter($this->columns(), fn(Column $column) => $column->visible);
    }

    /**
     * Get the visible actions.
     */
    protected function getVisibleActions(): array
    {
        return array_filter($this->actions(), function (Action|ActionGroup $action) {
            if ($action->isActionGroup) {
                $action->actions = array_filter($action->actions, fn(Action $action) => $action->visible);

                return $action->visible && count($action->actions) > 0;
            }

            return $action->visible;
        });
    }

    /**
     * Get the colspan for the columns.
     */
    protected function getColspanForColumns(array $columns, array $actions): int
    {
        $colspan = count(array_filter($columns, fn(Column $column) => $column->visible));

        // Add one for the actions column
        if (count($actions) > 0) {
            $colspan++;
        }

        return $colspan;
    }

    /**
     * Get eloquent query builder.
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
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        $globalFilters = [];
        $nestedFilters = [];

        foreach ($filters as $filter) {
            if ($filter->global) {
                $globalFilters[] = $filter;
            } else {
                $nestedFilters[] = $filter;
            }
        }

        if (count($globalFilters) > 0) {
            foreach ($globalFilters as $filter) {
                $this->applyFilter($query, $filter);
            }
        }

        $query->where(function ($query) use ($nestedFilters) {
            foreach ($nestedFilters as $filter) {
                $this->applyFilter($query, $filter);
            }
        });
    }

    protected function applyFilter(Builder $query, Filter $filter): void
    {
        $value = $this->filtered[$filter->index] ?? null;

        // Early return if the filter value is empty
        if (is_null($value) || $value === '') {
            return;
        }

        if ($filter->query) {
            call_user_func($filter->query, $query, $value);
        } else {
            $filter->apply($query, $value);
        }
    }

    /**
     * Apply global search to the query.
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
     */
    protected function searchWithinRelation(Builder $query, string $column): void
    {
        $relation = \Illuminate\Support\Str::beforeLast($column, '.');
        $column = \Illuminate\Support\Str::afterLast($column, '.');

        $query->orWhereHas($relation, function ($query) use ($column) {
            $query->where($column, 'like', '%' . $this->search . '%');
        });
    }

    /**
     * Apply sorting to the query.
     */
    protected function applySorting(Builder $query): void
    {
        if (! $this->sortColumn) {
            $this->sortColumn = $this->query()->getModel()->getKeyName();
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
     */
    protected function sortWithinRelation(Builder $query, string $column): void
    {
        $relations = explode('.', $column);
        $field = array_pop($relations);

        // The name of the aggregate column
        $name = \Illuminate\Support\Str::snake(implode('', $relations)) . '_' . $field;

        $query->withAggregate($relations, $field);
        $query->orderBy($name, $this->sortDirection);
    }
}
