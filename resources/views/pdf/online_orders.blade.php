<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Online Orders</title>
    <style>
        body {
            font-family: "Urbanist", sans-serif;
            color: #1F1F39;
        }

        .container {
            width: 100%;
            height: 100vh;
            margin: auto;
            position: relative;

        }

        .report {
            width: 100%;
            text-align: center;
        }
        img {
            margin: 0px 0px 8px 0px;
            font-size: 16px;
            font-weight: 600;
        }

        p {
            margin: 0px 0px 16px 0px;
        }
        th,
        td {
            border-collapse: collapse;
            border: 1px solid #EFF0F6;
            padding: 12px 11px;
            text-align: left;
            font-size: 12px;
            font-weight: 400;
        }

        table {
            border-radius: 8px;
            outline: 1px solid #EFF0F6;
            outline-offset: -1px;
            overflow: hidden;
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #F8FBFB;
        }

        th:first-child {
            text-wrap: nowrap;
        }

        tbody {
            color: #6E7191;
        }

        .date,
        .time {
            text-wrap: nowrap;
        }

        .total {
            color: #1F1F39;
        }

        .footer {
            position: absolute;
            width: 100%;
            text-align: center;
            font-size: 12px;
            font-weight: 400;
            bottom: 20px;
        }
    </style>
</head>

<body>
    @php 
         $total = 0;
    @endphp 
    <div class="container">
        <div class="report">
            <p style="margin: 0px 0px 8px 0px;font-size: 16px;font-weight: bold">{{ App\Libraries\AppLibrary::textShortener($company['company_name'], 60) }}</p>
            <p>{{ App\Libraries\AppLibrary::textShortener($company['company_address'],60) }}</p>
            <p  style="color: #ff006b;margin: 0px 0px 8px 0px;font-size: 16px;font-weight: bold;">{{ trans('all.label.online_orders', [], 'en') }}</p>
            <table>
                <thead>
                    <tr>
                        <th>{{ trans('all.label.order_serial_no', [], 'en') }}</th>
                        <th>{{ trans('all.label.order_type', [], 'en') }}</th>
                        <th>{{ trans('all.label.customer', [], 'en') }}</th>
                        <th>{{ trans('all.label.date', [], 'en') }}</th>
                        <th>{{ trans('all.label.status', [], 'en') }}</th>
                        <th>{{ trans('all.label.amount', [], 'en') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php $total+= $order->total @endphp
                        <tr>
                            <td>{{$order->order_serial_no}}</td>
                            <td>{{trans('orderType.' . $order->order_type, [], 'en')}}</td>
                            <td>{{$order->user?->name}}</td>
                            <td>{{ App\Libraries\AppLibrary::datetime($order->order_datetime) }}</td>
                            <td>{{ trans('orderStatus.' . $order->status, [], 'en') }}</td>
                            <td>{{ App\Libraries\AppLibrary::reportCurrencyAmountFormat($order->total) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total">
                        <td colspan="5">{{ trans('all.label.total', [], 'en') }}</td>
                        <td>{{ App\Libraries\AppLibrary::reportCurrencyAmountFormat($total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            {{ $copyright }}
        </div>
    </div>
</body>

</html>