<?php

namespace Redot\Datatables\Adaptors\PDF;

use Illuminate\Support\Collection;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaravelMpdf extends Adabtor
{
    /**
     * Download the PDF file.
     */
    public function download(string $template, array $headings, Collection $rows, array $options = []): StreamedResponse|Response
    {
        $filename = sprintf('export-%s.pdf', now()->format('Y-m-d_H-i-s'));

        // Generate the PDF file.
        $pdf = PDF::loadView($template, compact('headings', 'rows'), [], $options);

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Check if the adaptor is supported.
     */
    public function supported(): bool
    {
        return class_exists(PDF::class);
    }
}
