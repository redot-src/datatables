<?php

namespace Redot\Datatables\Adaptors\PDF;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Adabtor
{
    /**
     * Download the PDF file.
     */
    abstract public function download(string $template, array $headings, Collection $rows, array $options = []): StreamedResponse|Response;

    /**
     * Check if the adaptor is supported.
     */
    public function supported(): bool
    {
        return true;
    }
}
