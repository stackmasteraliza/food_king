<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>سند إرجاع مبيعات - {{ $salesReturn->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .footer {
            margin-top: 50px;
        }

        .signature {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>سند إرجاع مبيعات</h2>
        <p>رقم السند: {{ $salesReturn->id }}</p>
    </div>

    <div class="info">
        <p>رقم الفاتورة الأصلية: {{ $salesReturn->invoice->invoice_number }}</p>
        <p>تاريخ الإرجاع: {{ $salesReturn->return_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>كود الصنف</th>
                <th>اسم الصنف</th>
                <th>الكمية المرتجعة</th>
                <th>السعر</th>
                <th>الإجمالي</th>
                <th>سبب الإرجاع</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesReturn->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->invoiceItem->product->code }}</td>
                    <td>{{ $item->invoiceItem->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->quantity * $item->price }}</td>
                    <td>{{ $item->reason }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: left;">الإجمالي</td>
                <td>{{ $salesReturn->total_amount }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">توقيع المحصل</div>
        <div class="signature">توقيع العميل</div>
        <div class="signature">توقيع المدير</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
