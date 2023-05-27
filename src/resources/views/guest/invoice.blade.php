<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>{{ 'Admin - ' . $title ?? 'Cashier App' }}</title>
    <!-- <link rel="stylesheet" href="{{ url('/css/bootstrap.min.css') }}"> -->

    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet"> -->

    <style>
        * {
            font-family: 'Arial', sans-serif;
        }

        tr {
            /* height: 20px !important;
            background-color: green; */
        }
    </style>
</head>

<body class="theme-light">
    <div class="card card-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center" style="text-align: center;"><strong>{{ $company }}<strong></h2>
                </div>
                <div class="col-12 mt-4 mb-5">
                    <h2>Invoice {{ $orders->order->order_number }}</h2>
                </div>
            </div>
            <table class="table table-transparent table-responsive" width="100%">
                <!-- <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center" style="width: 1%">Qty</th>
                        <th class="text-end" style="width: 1%">Unit</th>
                        <th class="text-end" style="width: 1%">Amount</th>
                    </tr>
                </thead> -->
                @foreach ($orders as $d)
                <tr>
                    <td>{{ $d->menu_name }}</td>
                    <td class="text-center">{{ $d->quantity }}</td>
                    <td class="text-end">{{ number_format($d->price, 0,',','.')  }}</td>
                    <td class="text-end">{{ number_format(($d->price * $d->quantity), 0,',','.')  }}</td>
                </tr>
                @endforeach

                <!-- <tr>
                    <td>{{ count($orders) }}</td>
                    <td colspan="3" class="strong text-end">Total harga</td>
                    <td class="text-end">$25.000,00</td>
                </tr> -->
                <tr>
                    <td></td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="3" class="font-weight-bold text-uppercase text-end">Total Harga</td>
                    <td class="font-weight-bold text-end">{{ number_format($d->total_price, 0,',','.')  }}</td>
                </tr>
            </table>
            <h3 class="text-muted text-uppercase text-center mt-4 h2" style="text-align: center;">Terima kasih</h3>
        </div>
    </div>

</body>

</html>