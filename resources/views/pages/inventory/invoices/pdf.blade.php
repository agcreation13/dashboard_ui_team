<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->status === 'cancelled' ? 'CANCELLED INVOICE' : 'INVOICE' }} #{{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 10px;
        }
        .invoice-container {
            border: 1px solid #000;
            padding: 10px;
        }
        .header-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .seller-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            padding-right: 10px;
        }
        .invoice-details-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: left;
            padding: 0 10px;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .buyer-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-transform: capitalize;
            padding-left: 10px;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .gst-invoice-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .summary-section {
            float: right;
            width: 100%;
            margin-top: 10px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }
        .summary-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .summary-table .text-right {
            text-align: right;
        }
        .summary-table .text-left {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
        }
        .total-row-left {
            text-align: right !important;
            padding-right: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="gst-invoice-title text-center">{{ $invoice->status === 'cancelled' ? 'CANCELLED INVOICE' : 'INVOICE' }}</div>
              
        <!-- Header Section -->
        <div class="header-section">
            <div class="seller-section">
                <div class="section-title">{{ config('seller.name') }}</div>
                <div>{{ config('seller.address') }}</div>
                <div><b>EMAIL:-</b> {{ config('seller.email') }}</div>
                <div><b>Phone no. :-</b>{{ config('seller.phone') }}</div>
                <div><b>GSTIN:</b> {{ config('seller.gstin') }}</div>
            </div>
            
            <div class="invoice-details-section" style="text-align: left;">
                <div><strong>Invoice No.:</strong> {{ $invoice->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</div>
                <div><strong>E-way Bill:</strong> {{ $invoice->eway_bill ?? '' }}</div>
                <div><strong>MR NO.:</strong> {{ $invoice->mr_no ?? '' }}</div>
                <div><strong>S. MAN:</strong> {{ $invoice->s_man ?? '' }}</div>
            </div>
            
            <div class="buyer-section">
                <div class="section-title">Buyer</div>
                <div><b>Name:</b> <strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></div>
                <div><b>Address:</b> {{ $invoice->customer->address ?? '' }}</div>
                <div><b>Phone No.:</b> {{ $invoice->customer->phone ?? '' }}</div>
                <div><b>Email:</b> {{ $invoice->customer->email ?? '' }}</div>
                <div><b>GST NO.:</b> {{ $invoice->customer->gstin ?? '' }}</div>
            </div>
        </div>

        <!-- Product Details Table -->
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>HSN</th>
                    <th class="text-left" style="text-align: left;">Product Name</th>
                    <th>PACK</th>
                    <th>QTY</th>
                    <th>FREE</th>
                    <th>MRP</th>
                    <th>RATE</th>
                    <th>DIS%</th>
                    <th>GST%</th>
                    <th>G.AMT</th>
                    <th>NET AMT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr class="text-center">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->product->hsn ?? '' }}</td>
                    <td class="text-left">{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->product->pack ?? '' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->free_quantity ?? 0 }}</td>
                    <td class="text-center">{{ $item->product->mrp ? number_format($item->product->mrp, 2) : '' }}</td>
                    <td class="text-center">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-center">{{ number_format($item->discount_percentage ?? 0, 2) }}%</td>
                    <td class="text-center">{{ number_format($item->product->gst_percentage ?? 0, 2) }}%</td>
                    <td class="text-center">{{ number_format((($item->rate * $item->quantity) * ($item->product->gst_percentage ?? 0) / 100), 2) }}</td>
                    <td class="text-center">{{ number_format($item->net_amount, 2) }}</td>
                </tr>
                @endforeach
                @for($i = count($invoice->items); $i < 10; $i++)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
 
                <tr>
                    <th colspan="5" rowspan="4"></th>
                    <th>CGST</th>
                    <th>CGST AMT</th>
                    <th>SGST</th>
                    <th>SGST AMT</th>
                    <th>TOTALAM</th>
                    <th>TOTAL AMT</th>
                    <th>AD/LS AMT</th>
                </tr>
                <tr class="text-center">
                    <td class="text-center">{{ number_format($invoice->cgst_percentage ?? 0, 2) }}%</td>
                    <td class="text-center">{{ number_format($invoice->cgst_amount ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($invoice->sgst_percentage ?? 0, 2) }}%</td>
                    <td class="text-center">{{ number_format($invoice->sgst_amount ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($invoice->subtotal, 2) }}</td>
                    <td class="text-center">{{ number_format($invoice->subtotal, 2) }}</td>
                    <td class="text-center">{{ number_format($invoice->additional_amount ?? 0, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="6" class="total-row-left"><strong>ROUND OFF:</strong></td>
                    <td class="text-center"><strong>{{ number_format($invoice->round_off ?? 0, 2) }}</strong></td>
                </tr>
                <tr class="total-row">
                    <td colspan="6" class="total-row-left"><strong>GRAND TOTAL:</strong></td>
                    <td class="text-center"><strong>{{ number_format($invoice->grand_total, 2) }}</strong></td>
                </tr>
            </table>
    </div>
</body>
</html>
