<?php

namespace App\Exports;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;

class OrdersPdfExport
{
    protected $orders;
    protected $totalOrders;
    protected $totalRevenue;
    protected $statusBreakdown;

    public function __construct($orders, $totalOrders, $totalRevenue, $statusBreakdown)
    {
        $this->orders = $orders;
        $this->totalOrders = $totalOrders;
        $this->totalRevenue = $totalRevenue;
        $this->statusBreakdown = $statusBreakdown;
    }

    public function export()
    {
        $data = [
            'orders' => $this->orders,
            'totalOrders' => $this->totalOrders,
            'totalRevenue' => $this->totalRevenue,
            'statusBreakdown' => $this->statusBreakdown,
            'exportDate' => now()->format('Y-m-d H:i:s'),
        ];

        // Generate HTML content
        $html = view('exports.orders-pdf', $data)->render();
        
        $pdf = Pdf::loadHTML($html);
        
        // Configure PDF with minimal options
        $pdf->getDomPDF()->setOptions(new Options([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Arial',
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'portrait',
        ]));
        
        return $pdf->download('orders-export-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }
} 