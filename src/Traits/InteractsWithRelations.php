<?php

namespace Redot\Datatables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait InteractsWithRelations
{
    /**
     * Apply a constraint either on the base query or within a related query using whereHas.
     */
    protected function withRelation(string $column, Builder $query, Closure $callback): Builder
    {
        return $this->handleRelation($column, $query, $callback, false);
    }

    /**
     * Apply a constraint either on the base query or within a related query using orWhereHas.
     */
    protected function orWithRelation(string $column, Builder $query, Closure $callback): Builder
    {
        return $this->handleRelation($column, $query, $callback, true);
    }

    /**
     * Centralized handler to reduce duplication between withRelation and orWithRelation.
     */
    private function handleRelation(string $column, Builder $query, Closure $callback, bool $useOr): Builder
    {
        if (! Str::contains($column, '.')) {
            if ($useOr) {
                $query->orWhere(fn (Builder $query) => $callback($query, $column));
            } else {
                $callback($query, $column);
            }

            return $query;
        }

        [$relation, $field] = $this->splitRelationColumn($column);

        $constraint = function (Builder $relationQuery) use ($field, $callback) {
            $callback($relationQuery, $field);
        };

        return $useOr
            ? $query->orWhereHas($relation, $constraint)
            : $query->whereHas($relation, $constraint);
    }

    /**
     * Split a relation column (e.g. users.profile.name) into relation path and field.
     */
    private function splitRelationColumn(string $column): array
    {
        return [
            Str::beforeLast($column, '.'),
            Str::afterLast($column, '.'),
        ];
    }
}
