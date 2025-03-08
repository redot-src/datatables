<?php

namespace Redot\Datatables\Adapters\PDF;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DomPdf extends Adabter
{
    /**
     * Download the PDF file.
     */
    public function download(string $template, array $headings, Collection $rows, array $options = []): StreamedResponse|Response
    {
        $filename = sprintf('export-%s.pdf', now()->format('Y-m-d_H-i-s'));

        // Set options.
        PDF::setOptions($options);

        // Generate the PDF file.
        $pdf = PDF::loadView($template, compact('headings', 'rows'), []);

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Check if the adapter is supported.
     */
    public function supported(): bool
    {
        return class_exists(PDF::class);
    }
}
