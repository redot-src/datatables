<?php

namespace Redot\LivewireDatatable\Exceptions;

use Exception;

class CannotSortRelationalColumnException extends Exception
{
    protected $message = 'Cannot sort relational column.';
}