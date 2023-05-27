<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>{{ 'Admin - ' . $title ?? 'Cashier App' }}</title>
    <style>
        * {
            font-family: 'Arial', sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-style: solid;
        }
    </style>
</head>

<body class="theme-light">
    <table class="table" width="100%">
        <thead>
            <tr>
                <th><strong>Tanggal</strong></th>
                <th><strong>Transaksi</strong></th>
                <th><strong>Debit</strong></th>
                <th><strong>Kredit</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recaps as $recap)
                @if($recap->month_separator != '')
                    <tr>
                        <td colspan="4" align="center"><strong>{{ $recap->month_separator }}</strong></td>
                    </tr>
                @endif

                <tr>
                    <td align="center">{{ $recap->date }}</td>
                    <td>{{ $recap->name }}</td>
                    <td align="center">{{ $recap->debit != 0 ? $recap->debit : '' }}</td>
                    <td align="center">{{ $recap->credit != 0 ? $recap->credit : '' }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="2" align="center"><strong>Total debit</strong></td>
                <td align="center"><strong>{{ "Rp. " . number_format($total_debit, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><strong>Total kredit</strong></td>
                <td></td>
                <td align="center"><strong>{{ "Rp. " . number_format($total_credit, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><strong>Total keuntungan</strong></td>
                <td colspan="2" align="center"><strong>{{ "Rp. " . number_format($total_profit, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>