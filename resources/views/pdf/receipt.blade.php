<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Money Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info, .footer {
            width: 100%;
            margin-bottom: 20px;
        }
        .info td {
            padding: 4px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items th, table.items td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>{{ $shop->shop_name }}</h2>
    <p>{{ $shop->address }}</p>
    <p>Phone: {{ $shop->contact_number }}</p>
</div>

<table class="info">
    <tr>
        <td><strong>Customer:</strong> {{ $sale->customer_name ?? 'N/A' }}</td>
        <td><strong>Phone:</strong> {{ $sale->customer_phone ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Salesperson:</strong> {{ $sale->salesperson_name ?? 'N/A' }}</td>
        <td><strong>Receipt No:</strong> {{ $sale->receipt_no }}</td>
    </tr>
    <tr>
        <td><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i') }}</td>
    </tr>
</table>

<table class="items">
    <thead>
    <tr>
        <th>Fertilizer</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->fertilizer->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->unit_price, 2) }}</td>
            <td>{{ number_format($item->subtotal, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="totals">
    <p><strong>Gross Amount:</strong> {{ number_format($sale->gross_amount, 2) }}</p>
    <p><strong>Discount ({{ $sale->discount_percent }}%):</strong> -{{ number_format($sale->gross_amount * $sale->discount_percent / 100, 2) }}</p>
    <p><strong>Net Amount:</strong> {{ number_format($sale->net_amount, 2) }}</p>
</div>

<div class="footer">
    <p>Thank you for your purchase!</p>
</div>

</body>
</html>
