<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;

class TestPdfExport
{
    public function export()
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Test PDF</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; }
            </style>
        </head>
        <body>
            <h1>Test PDF Export</h1>
            <p>This is a simple test to verify PDF generation works.</p>
            <p>Generated on: ' . now()->format('Y-m-d H:i:s') . '</p>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html);
        
        $pdf->getDomPDF()->setOptions(new Options([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Arial',
        ]));
        
        return $pdf->download('test-export-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }
} 