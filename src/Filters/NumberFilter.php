<?php

namespace Redot\Datatables\Filters;

use Illuminate\Database\Eloquent\Builder;

class NumberFilter extends Filter
{
    /**
     * Filter operations.
     */
    public array $operators = [];

    /**
     * The filter's view.
     */
    public string $view = 'datatables::filters.number';

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
        ];
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, mixed $value): void
    {
        $operator = isset($value['operator']) ? $value['operator'] : 'equals';
        $value = isset($value['value']) ? $value['value'] : '';

        // Early return if the value is empty.
        if (empty($value)) {
            return;
        }

        match ($operator) {
            'equals' => $query->where($this->column, $value),
            'not_equals' => $query->where($this->column, '!=', $value),
            'greater_than' => $query->where($this->column, '>', $value),
            'greater_than_or_equals' => $query->where($this->column, '>=', $value),
            'less_than' => $query->where($this->column, '<', $value),
            'less_than_or_equals' => $query->where($this->column, '<=', $value),
        };
    }
}
