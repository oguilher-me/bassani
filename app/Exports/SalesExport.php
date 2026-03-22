<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer ID',
            'Issue Date',
            'Expected Delivery Date',
            'Actual Delivery Date',
            'Sales Responsible',
            'Representative ID',
            'Sales Division',
            'Carrier ID',
            'Payment Term ID',
            'Currency',
            'Contact Name',
            'Contact Email',
            'Contact Phone',
            'Purchase Order',
            'Notes',
            'ERP Code',
            'Total Items',
            'Total Discounts',
            'Total Freight',
            'Total IPI',
            'Total ICMS',
            'Total ICMS ST',
            'Total DIFAL',
            'Grand Total',
            'Gross Weight',
            'Net Weight',
            'Cubic Volume',
            'Packages',
            'Order Status',
            'Delivery Status',
            'Shipping Method',
            'Tracking Code',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }
}