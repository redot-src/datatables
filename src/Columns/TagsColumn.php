<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class TagsColumn extends Column
{
    /**
     * The column's type.
     */
    public ?string $type = 'tags';

    /**
     * Tags limit per row.
     */
    public int $limit = 3;

    /**
     * Tags ellipsis text.
     */
    public string $ellipsis = '...';

    /**
     * Set the tags limit per row.
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set the tags ellipsis text.
     */
    public function ellipsis(string $ellipsis): self
    {
        $this->ellipsis = $ellipsis;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        if (! $value || empty($value)) {
            return null;
        }

        $count = count($value);
        $tags = collect($value)->take($this->limit)->when($count > $this->limit, fn ($collection) => $collection->push($this->ellipsis));
        $tags = $tags->map(fn ($tag) => sprintf('<span class="tag">%s</span>', $tag))->join('');

        return sprintf('<div class="tags-list">%s</div>', $tags);
    }
}
