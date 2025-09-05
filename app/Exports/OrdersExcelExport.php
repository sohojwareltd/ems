<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class OrdersExcelExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
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

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Order Status',
            'Payment Status',
            'Payment Method',
            'Order Total',
            'Currency',
            'Shipping Method',
            'Tracking Number',
            'Shipping Address',
            'Billing Address',
            'Order Notes',
            'Items Count',
            'Order Date',
            'Last Updated',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name ?? 'Guest',
            $order->user->email ?? 'N/A',
            ucfirst($order->status),
            ucfirst($order->payment_status),
            $order->payment_method ?? 'N/A',
            '$' . number_format($order->total, 2),
            $order->currency,
            $order->shipping_method ?? 'N/A',
            $order->tracking ?? 'N/A',
            $this->formatAddress($order->shipping_address),
            $this->formatAddress($order->billing_address),
            $order->notes ?? 'N/A',
            $order->lines->count(),
            $order->created_at->format('Y-m-d H:i:s'),
            $order->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    protected function formatAddress($address)
    {
        if (!is_array($address)) {
            return 'N/A';
        }
        
        $parts = [];
        if (isset($address['first_name'])) $parts[] = $address['first_name'];
        if (isset($address['last_name'])) $parts[] = $address['last_name'];
        if (isset($address['address_line_1'])) $parts[] = $address['address_line_1'];
        if (isset($address['address_line_2'])) $parts[] = $address['address_line_2'];
        if (isset($address['city'])) $parts[] = $address['city'];
        if (isset($address['state'])) $parts[] = $address['state'];
        if (isset($address['postal_code'])) $parts[] = $address['postal_code'];
        if (isset($address['country'])) $parts[] = $address['country'];
        
        return implode(', ', array_filter($parts));
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add summary information
        $row = $this->orders->count() + 3;
        $sheet->setCellValue("A{$row}", 'Export Summary');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        
        $row++;
        $sheet->setCellValue("A{$row}", "Total Orders: {$this->totalOrders}");
        $sheet->setCellValue("B{$row}", "Total Revenue: $" . number_format($this->totalRevenue, 2));
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Status Breakdown:');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        
        $row++;
        foreach ($this->statusBreakdown as $status => $count) {
            $sheet->setCellValue("A{$row}", ucfirst($status) . ": {$count}");
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Order ID
            'B' => 20,  // Customer Name
            'C' => 25,  // Customer Email
            'D' => 15,  // Order Status
            'E' => 15,  // Payment Status
            'F' => 15,  // Payment Method
            'G' => 15,  // Order Total
            'H' => 10,  // Currency
            'I' => 20,  // Shipping Method
            'J' => 20,  // Tracking Number
            'K' => 40,  // Shipping Address
            'L' => 40,  // Billing Address
            'M' => 30,  // Order Notes
            'N' => 12,  // Items Count
            'O' => 20,  // Order Date
            'P' => 20,  // Last Updated
        ];
    }

    public function title(): string
    {
        return 'Orders Export';
    }
} 