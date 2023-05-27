<?php

namespace App\Http\Controllers\Backend;

use App\Exports\RecapitulationExport;
use PDF;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Income;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class RecapitulationController extends Controller
{
    public function index()
    {
        $data['title'] = 'Rekap data';
        $data['year_start'] = 2019;
        return view('backend.recapitulation.index', $data);
    }

    public function recap($by, $type)
    {
        if ($by == 'year') {
            $yearSelected = request('at');

            return $this->_recapYear($yearSelected, $type);
        } else if ($by == 'month') {
            $monthSelected = request('on_month');
            $yearSelected = request('at_year');

            return $this->_recapMonth($monthSelected, $yearSelected, $type);
        } else if ($by == 'custom') {
            $dateFrom = request('date_from');
            $dateTo = request('date_to');

            return $this->_recapCustom($dateFrom, $dateTo, $type);
        }
    }

    private function _recapYear($year, $exportTo)
    {
        $orders = Order::where('status_payment', 'complete')->whereYear('created_at', $year)->get();
        $purchases = Purchase::whereYear('date', $year)->get();
        $incomes = Income::whereYear('date', $year)->get();

        $date = $year;

        $data = $this->_generateRecapData($orders, $purchases, $incomes);
        $data['title'] = 'Rekap_' . $year;

        if ($exportTo == 'pdf') {
            return $this->_generatePdf($data);
        } else if ($exportTo == 'xlsx') {
            return $this->_generateXlsx($data);
        }
    }

    private function _recapMonth($month, $year, $exportTo)
    {
        $orders = Order::where('status_payment', 'complete')->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
        $purchases = Purchase::whereYear('date', $year)->whereMonth('date', $month)->get();
        $incomes = Income::whereYear('date', $year)->whereMonth('date', $month)->get();

        $month = Carbon::parse(mktime(0, 0, 0, $month))->locale('id');
        $month->settings(['formatFunction' => 'translatedFormat']);
        $date = $month->format('F') . " " . $year;

        $data = $this->_generateRecapData($orders, $purchases, $incomes);
        $data['title'] = 'Rekap_' . $date;

        if ($exportTo == 'pdf') {
            return $this->_generatePdf($data);
        } else if ($exportTo == 'xlsx') {
            return $this->_generateXlsx($data);
        }
    }

    public function _recapCustom($dateFrom, $dateTo, $exportTo)
    {
        $orders = Order::where('status_payment', 'complete')->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])->get();
        $purchases = Purchase::whereBetween(DB::raw('DATE(date)'), [$dateFrom, $dateTo])->get();
        $incomes = Income::whereBetween(DB::raw('DATE(date)'), [$dateFrom, $dateTo])->get();

        $date = $dateFrom . "-" . $dateTo;

        $data = $this->_generateRecapData($orders, $purchases, $incomes);
        $data['title'] = $date;

        if ($exportTo == 'pdf') {
            return $this->_generatePdf($data);
        } else if ($exportTo == 'xlsx') {
            return $this->_generateXlsx($data);
        }
    }

    private function _generateRecapData($orders, $purchases, $incomes, $dateTitle = null)
    {
        $newOrders = [];
        $newPurchases = [];
        $newIncomes = [];

        foreach ($orders as $key => $order) {
            $newOrders[$key]['date'] = strtotime($order->created_at);
            $newOrders[$key]['name'] = 'Pemesanan makanan/minuman, order number: ' . $order->order_number;
            $newOrders[$key]['debit'] = $order->total_price;
            $newOrders[$key]['credit'] = '';
        }

        foreach ($purchases as $key => $purchase) {
            $newPurchases[$key]['date'] = strtotime($purchase->date);
            $newPurchases[$key]['name'] = $purchase->name_item . ' - ' . $purchase->quantity;
            $newPurchases[$key]['debit'] = '';
            $newPurchases[$key]['credit'] = $purchase->price;
        }

        foreach ($incomes as $key => $income) {
            $newIncomes[$key]['date'] = strtotime($income->date);
            $newIncomes[$key]['name'] = $income->name;
            $newIncomes[$key]['debit'] =  $income->price;
            $newIncomes[$key]['credit'] = '';
        }

        // merge all arrays
        $mergingData = array_merge($newOrders, $newPurchases, $newIncomes);

        $newData = [];
        foreach ($mergingData as $key => $row) {
            $newData[$key]['date'] = $row['date'];
            $newData[$key]['name'] = $row['name'];
            $newData[$key]['debit'] = floatval($row['debit']);
            $newData[$key]['credit'] = floatval($row['credit']);
        }
        array_multisort($newData, SORT_ASC, $mergingData);

        // sorting date and calculate tatal debit, credit, and total profit
        $total_debit = 0;
        $total_credit = 0;
        $month = 0;
        foreach ($newData as $key => $d) {
            if (date('m', $d['date']) == $month) {
                $newData[$key]['month_separator'] = '';
            } else {
                $month += 1;
                $date = Carbon::parse(mktime(0, 0, 0, $month))->locale('id');
                $date->settings(['formatFunction' => 'translatedFormat']);
                $newData[$key]['month_separator'] = $date->format('F');
            }

            $date = Carbon::parse(date('d F Y', $d['date']))->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);
            $newData[$key]['date'] = $date->format('d F Y');

            $total_debit += floatval($newData[$key]['debit']);
            $total_credit += floatval($newData[$key]['credit']);
        }

        $data['recaps'] = json_decode(json_encode($newData), FALSE);
        $data['total_debit'] =  $total_debit;
        $data['total_credit'] = $total_credit;
        $data['total_profit'] = $data['total_debit'] - $data['total_credit'];

        return $data;
    }

    private function _generatePdf($data)
    {
        $pdf = PDF::loadView('guest.recap.pdf', $data)->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    private function _generateXlsx($data)
    {
        $xlsx = new RecapitulationExport($data);
        return Excel::download($xlsx, $data['title'] . ".xlsx");
    }
}
