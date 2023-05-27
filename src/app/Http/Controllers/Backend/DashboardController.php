<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Income;
use App\Models\Purchase;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        $now = Carbon::now();

        //orderan
        $data['OrderWait'] = Order::whereDate('created_at', '=', $now)->where('status_payment', 'waiting')->count();
        $data['OrderFinish'] = Order::whereDate('created_at', '=', $now)->where('status_payment', 'complete')->count();
        $data['OrderTotal'] = Order::whereDate('created_at', '=', $now)->count();
        //orderan

        //pengeluaran
        $nowPurchase = Purchase::whereDate('created_at', '=', $now)->sum('price');
        $data['purchase'] = 'Rp. ' . number_format($nowPurchase, 2, ',', '.');
        //penngeluaran

        //pemasukan
        $nowincome = Income::whereDate('created_at', '=', $now)->sum('price');
        $nowOrder = Order::whereDate('created_at', '=', $now)->where('status_payment', 'complete')->sum('total_price');
        $data['AllIncomeNow'] = 'Rp. ' . number_format($nowincome + $nowOrder, 2, ',', '.');
        //pemasukan

        // order yang belm bayar
        $data['UnpaidOrderNow'] = 'Rp. ' . number_format(Order::whereDate('created_at', '=', $now)->where('status_payment', 'waiting')->sum('total_price'), 2, ',', '.');
        // order yang belm bayar

        //total saldo
        $incomeAll = Income::sum('price');
        $purchaseAll = Purchase::sum('price');
        $orderAll = Order::where('status_payment', 'success')->sum('total_price');
        $data['SaldoTotal'] = 'Rp. ' . number_format($incomeAll + $orderAll - $purchaseAll, 2, ',', '.');
        //total saldo

        $data['purchases'] = $this->ChartPengeluaran();
        $data['incomes'] = $this->ChartMasuk();

        return view('backend.dashboard.index', $data);
    }

    private function ChartPengeluaran()
    {
        $purchases = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $rawPurchases = Purchase::whereYear('created_at', date('Y', time()))->get();
        foreach ($rawPurchases as $purchase) {
            $currentMonth = (int)date('m', strtotime($purchase->date));
            $purchases[$currentMonth - 1] += $purchase->price;
        }
        return $purchases;
    }

    private function ChartMasuk()
    {
        $incomes = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $rawIncomes = Income::whereYear('created_at', date('Y', time()))->get();
        $rawOrders = Order::whereYear('created_at', date('Y', time()))->get();
        foreach ($rawIncomes as $income) {
            $currentMonth = (int)date('m', strtotime($income->date));
            $incomes[$currentMonth - 1] += $income->price;
        }
        foreach ($rawOrders as $order) {
            $currentMonth = (int)date('m', strtotime($order->created_at));
            $incomes[$currentMonth - 1] += $order->total_price;
        }

        return $incomes;
    }
}
