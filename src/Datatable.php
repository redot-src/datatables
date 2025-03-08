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
use Redot\Datatables\Adaptors\PDF\Adabtor;
use Redot\Datatables\Adaptors\PDF\LaravelMpdf;
use Redot\Datatables\Columns\Column;
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
    #[Url]
    public string $search = '';

    /**
     * Sort column for the datatable.
     */
    #[Url]
    public string $sortColumn = '';

    /**
     * Sort direction for the datatable.
     */
    #[Url]
    public string $sortDirection = 'asc';

    /**
     * Filters values for the datatable.
     */
    #[Url]
    public array $filtered = [];

    /**
     * Toggle filters visibility.
     */
    #[Url]
    public bool $showFilters = false;

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
     * PDF adaptor class.
     */
    public string $pdfAdaptor = LaravelMpdf::class;

    /**
     * PDF adaptor options.
     */
    public array $pdfOptions = [];

    /**
     * PDF view template.
     */
    public string $pdfTemplate = 'datatables::pdf.default';

    /**
     * Create a new datatable instance.
     */
    public function __construct()
    {
        $this->id ??= uniqid('datatable-');
        $this->emptyMessage ??= __('datatables::datatable.pagination.empty');

        $css = config('datatables.assets.css');
        $js = config('datatables.assets.js');

        $this->cssAssetsUrl = route($css['name'], ['v' => md5(filemtime($css['path']))]);
        $this->jsAssetsUrl = route($js['name'], ['v' => md5(filemtime($js['path']))]);
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
        return [
            ActionGroup::make($label, $icon ?? 'fas fa-ellipsis-v')
                ->actions($actions),
        ];
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

        $items = $rows->map(fn ($row) => array_combine($headings, $row))->toArray();
        $filename = sprintf('export-%s.json', now()->format('Y-m-d_H-i-s'));
        $flags = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(fn () => print Js::encode($items, $flags), $filename, $headers);
    }

    /**
     * Export the datatable to a PDF file.
     */
    public function toPdf(): StreamedResponse|Response
    {
        $pdfAdaptor = new $this->pdfAdaptor;

        if (! $pdfAdaptor instanceof Adabtor || ! $pdfAdaptor->supported()) {
            throw new Exceptions\MissingDependencyException(sprintf('The PDF adaptor "%s" is not supported.', $this->pdfAdaptor));
        }

        [$headings, $rows] = $this->getExportData();

        return $pdfAdaptor->download($this->pdfTemplate, $headings, $rows, $this->pdfOptions);
    }

    /**
     * Get export data.
     */
    protected function getExportData(): array
    {
        $columns = array_filter($this->columns(), fn (Column $column) => $column->exportable && $column->visible);
        $headings = array_column($columns, 'label');

        $rows = $this->getQueryBuilder($columns, $this->filters())->get();
        $rows = $rows->map(fn ($row) => array_map(fn (Column $column) => $column->get($row), $columns));

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

            'filterable' => count($filters) > 0,
            'searchable' => count(array_filter($columns, fn (Column $column) => $column->searchable)) > 0,
            'exportable' => count(array_filter($columns, fn (Column $column) => $column->exportable)) > 0,

            'rows' => $rows,
        ];
    }

    /**
     * Get the visible columns.
     */
    protected function getVisibleColumns(): array
    {
        return array_filter($this->columns(), fn (Column $column) => $column->visible);
    }

    /**
     * Get the visible actions.
     */
    protected function getVisibleActions(): array
    {
        return array_filter($this->actions(), function (Action|ActionGroup $action) {
            if ($action->isActionGroup) {
                $action->actions = array_filter($action->actions, fn (Action $action) => $action->visible);

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
        $colspan = count(array_filter($columns, fn (Column $column) => $column->visible));

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
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        $query->where(function ($query) use ($filters) {
            foreach ($filters as $filter) {
                $value = $this->filtered[$filter->index] ?? null;

                if ($value) {
                    $filter->apply($query, $value);
                }
            }
        });

        return $query;
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
     */
    protected function sortWithinRelation(Builder $query, string $column): void
    {
        $relations = explode('.', $column);
        $field = array_pop($relations);

        $query->withAggregate($relations, $field);
        $query->orderBy(implode('_', $relations) . '_' . $field, $this->sortDirection);
    }
}
