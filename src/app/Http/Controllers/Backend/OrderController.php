<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Order;
use App\Models\OrderedMenu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $orders = Order::latest('created_at');

            if (request('date_from') != "" && request('date_to') != "") {
                $orders = $orders->whereBetween(DB::raw('DATE(created_at)'), [request('date_from'), request('date_to')]);
            }


            $orders = $orders->get();

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('total_price', function ($order) {
                    $element = 'Rp. ' . number_format($order->total_price, 0, ',', '.');

                    if ($order->status_payment == 'canceled') {
                        return '<span class="text-decoration-line-through">' . $element . '</span>';
                    }

                    return $element;
                })
                ->addColumn('order_number', function ($order) {
                    $element = '<code>' . $order->order_number . '</code>';

                    if ($order->status_payment == 'canceled') {
                        return '<span class="text-decoration-line-through">' . $element . '</span>';
                    }

                    return $element;
                })
                ->addColumn('cashier_name', function ($order) {
                    if ($order->status_payment == 'canceled') {
                        return '<span class="text-decoration-line-through">' . $order->cashier_name . '</span>';
                    }
                    
                    return $order->cashier_name;
                })
                ->addColumn('customer_number', function ($order) {
                    if ($order->status_payment == 'canceled') {
                        return '<span class="text-decoration-line-through">' . $order->customer_number . '</span>';
                    }
                    
                    return $order->customer_number;
                })
                ->addColumn('created_at', function ($order) {
                    if ($order->status_payment == 'canceled') {
                        return '<span class="text-decoration-line-through">' . $order->created_at . '</span>';
                    }
                    
                    return $order->created_at;
                })
                ->addColumn('status_payment', function ($order) {
                    switch ($order->status_payment) {
                        case 'complete':
                            return '<span class="text-success">Sudah dibayar 
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                            </span>';
                            break;
                        case 'waiting':
                            return '<span class="text-warning"><strong>Belum dibayar</strong></span>';
                            break;
                        case 'canceled':
                            return '<span class="text-muted"><strong>Pesanan dibatalkan
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wash-dryclean-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M20.048 16.033a9 9 0 0 0 -12.094 -12.075m-2.321 1.682a9 9 0 0 0 12.733 12.723"></path>
                                    <path d="M3 3l18 18"></path>
                                </svg>
                            </strong></span>';
                            break;
                        default:
                            break;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-invoice" data-detail="' . htmlspecialchars($row) . '"  data-id=' . $row->id . '>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-invoice me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                            <line x1="9" y1="7" x2="10" y2="7"></line>
                            <line x1="9" y1="13" x2="15" y2="13"></line>
                            <line x1="13" y1="17" x2="15" y2="17"></line>
                        </svg>

                        Invoice
                        </a>

                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-success btn-modal-update-payment ms-1" data-detail="' . htmlspecialchars($row) . '"  data-id=' . $row->id . '>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <line x1="7" y1="15" x2="7.01" y2="15"></line>
                            <line x1="11" y1="15" x2="13" y2="15"></line>
                        </svg>
                        
                        Pembayaran
                        </a>
            ';
                    return $btn;
                })
                ->rawColumns(['cashier_name', 'customer_number', 'total_price', 'order_number', 'status_payment', 'action', 'created_at'])
                ->make(true);
        }

        $data['title'] = "Order";

        return view('backend.order.index', $data);
    }

    public function invoice($orderNumber)
    {
        $data['title'] = "Invoice";
        $orderID = Order::where('order_number', $orderNumber)->first()->id;
        $data['company'] = Settings::first()->name;
        $data['orders'] = OrderedMenu::join('orders', 'orders.id', '=', 'ordered_menus.order_id')->where('ordered_menus.order_id', $orderID)->get();

        $data['orders']->order = $data['orders'][0]->order;
        
        $pdf = PDF::loadView('guest.invoice', $data)->setPaper('a6', 'potrait');
        return $pdf->stream();
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'status_payment' => 'required'
        ]);

        Order::find($id)->update([
            'status_payment' => $request->status_payment
        ]);

        return Response::json([
            'status' => true,
            'message' => 'Pembyaran berhasil diubah'
        ], 200);
    }

    private function _formatData($d)
    {
        $d->menus = $d->menu;

        return $d;
    }
}
