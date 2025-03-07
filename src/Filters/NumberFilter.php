<?php

namespace Redot\Datatables\Filters;

class NumberFilter extends Filter
{
    /**
     * Filter operations.
     */
    public array $operators = [];

    /**
     * The filter's view.
     */
    public ?string $view = 'datatables::filters.number';

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->operators = [
            'equals' => __('datatables::datatable.filters.number.equals'),
            'not_equals' => __('datatables::datatable.filters.number.not_equals'),
            'greater_than' => __('datatables::datatable.filters.number.greater_than'),
            'greater_than_or_equals' => __('datatables::datatable.filters.number.greater_than_or_equals'),
            'less_than' => __('datatables::datatable.filters.number.less_than'),
            'less_than_or_equals' => __('datatables::datatable.filters.number.less_than_or_equals'),
            'between' => __('datatables::datatable.filters.number.between'),
            'not_between' => __('datatables::datatable.filters.number.not_between'),
        ];

        $this->queryCallback = function ($query, $value) {
            [$operator, $value] = $value;

            match ($operator) {
                'equals' => $query->where($this->column, $value),
                'not_equals' => $query->where($this->column, '!=', $value),
                'greater_than' => $query->where($this->column, '>', $value),
                'greater_than_or_equals' => $query->where($this->column, '>=', $value),
                'less_than' => $query->where($this->column, '<', $value),
                'less_than_or_equals' => $query->where($this->column, '<=', $value),
                'between' => $query->whereBetween($this->column, $value),
                'not_between' => $query->whereNotBetween($this->column, $value),
            };
        };
    }
}
