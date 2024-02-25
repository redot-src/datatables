<?php

namespace Redot\LivewireDatatable\Exceptions;

use Exception;

class CannotSortNullColumnException extends Exception
{
    protected $message = 'Cannot sort null column.';
}

