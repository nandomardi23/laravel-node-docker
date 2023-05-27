<?php

namespace App\Http\Controllers\Backend;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PurchaseController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $purchases = Purchase::query();

            return DataTables::of($purchases)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    return $row->price;
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href="javascript:void(0)" class="btn btn-outline-yellow btn-sm btn-edit me-1" data-detail="' . htmlspecialchars($row) . '" data-id=' . $row->id . '>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                        <path d="M16 5l3 3"></path>
                    </svg>Edit</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info btn-detail me-1" data-detail="' . htmlspecialchars($row) . '" data-id=' . $row->id . ' data-bs-toggle="modal" data-bs-target="#detailModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="2"></circle>
                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                    </svg>Detail</a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm btn-delete" data-id=' . $row->id . '>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="4" y1="7" x2="20" y2="7"></line>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                    </svg>Delete</a>
            ';
                    return $btn;
                })
                ->rawColumns(['action', 'price'])
                ->make(true);
        }

        $data['title'] = 'Pengeluaran';

        return view('backend.purchase.index', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'name_item' => 'required',
            'quantity' => 'required',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('photo_invoice')) {
            $request->validate([
                'photo_invoice' => 'image|mimes:jpg,png,jpeg|max:4096'
            ]);
        }

        $save = Purchase::create([
            'date' => $request->date,
            'name_item' => $request->name_item,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'desc' => $request->desc,
            'photo_invoice' => '',
            'user_id' => auth()->user()->id
        ]);

        if ($save && $request->hasFile('photo_invoice')) {
            $imageName = time() . "_" . $request->file('photo_invoice')->getClientOriginalName();
            $path = 'images/purchase_invoices/' . $imageName;
            Storage::disk('public')->put($path, File::get($request->file('photo_invoice')));

            Purchase::find($save->id)->update(['photo_invoice' => $path]);
        }

        $response = [
            "message" => "Data berhasil dibuat",
            "status" => true
        ];

        return Response::json($response, 201);
    }

    public function show($id)
    {
        $purchase = Purchase::where('id', $id)->first();

        $response = [
            "message" => "",
            "status" => true,
            "data" => $purchase
        ];

        return Response::json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'name_item' => 'required',
            'quantity' => 'required',
            'price' => 'required|numeric',
        ]);

        $purchaseOld = Purchase::find($id);

        if ($request->hasFile('photo_invoice')) {
            $request->validate([
                'photo_invoice' => 'image|mimes:jpg,png,jpeg|max:4096'
            ]);
        }

        $save = Purchase::where('id', $id)->update([
            'date' => $request->date,
            'name_item' => $request->name_item,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'desc' => $request->desc,
            'user_id' => auth()->user()->id
        ]);

        if ($request->hasFile('photo_invoice')) {
            $imageName = time() . "_" . $request->file('photo_invoice')->getClientOriginalName();
            $path = 'images/purchase_invoices/' . $imageName;
            Storage::disk('public')->put($path, File::get($request->file('photo_invoice')));

            Purchase::find($id)->update(['photo_invoice' => $path]);

            // delete old image
            if (file_exists(public_path() . '/' . 'storage/' . $purchaseOld->photo_invoice_raw)) {
                unlink(public_path() . '/' . 'storage/' . $purchaseOld->photo_invoice_raw);
            }
        }

        $response = [
            "message" => "Data berhasil diubah",
            "status" => true
        ];

        return Response::json($response, 201);
    }

    public function delete($id)
    {
        $invoice = Purchase::where('id', $id)->first();

        if (file_exists(public_path() . '/' . 'storage/' . $invoice->photo_invoice_raw)) {
            unlink(public_path() . '/' . 'storage/' . $invoice->photo_invoice_raw);
            Purchase::destroy($id);
        }

        $response = [
            "message" => "Data berhasil dihapus",
            "status" => true
        ];

        return Response::json($response, 201);
    }
}
