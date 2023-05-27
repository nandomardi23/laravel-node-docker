<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class IncomesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $income = Income::with('typeincome')->latest()->get();
        if ($request->ajax()) {
            $data = Income::with('typeincome')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return Blade::render(
                        '
                <a href="javascript:void(0)" class="btn btn-sm btn-outline-yellow btn-edit me-1" data-bs-toggle="modal" data-bs-target="#incomeModal" data-detail="' . htmlspecialchars($row) . '" data-id=' . $row->id . '>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                    <path d="M16 5l3 3"></path>
                </svg>Edit</a>
                <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm btn-delete" data-detail="' . htmlspecialchars($row) . '"  data-id= ' . $row->id . ' >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="4" y1="7" x2="20" y2="7"></line>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                </svg>Delete</a>',
                        ['row' => $row]
                    );
                })
                ->addColumn('price', function ($row) {
                    return "Rp " . number_format($row->price, 0, ',', '.');
                })
                ->addColumn('typeincome', function ($row) {
                    return $row->typeincome->name;
                })
                ->rawColumns(['action', 'typeincome'])
                ->make(true);
        }
        // dd($income);
        $data['title'] = 'Pemasukan Dari luar order';
        return view('backend.income.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required',
            'typeincome_id' => 'required',
            'price' => 'required',
        ]);
        $income = Income::create([
            'date' => $request->date,
            'name' => $request->name,
            'typeincome_id' => $request->typeincome_id,
            'price' => $request->price,
            'desc' => $request->desc,
        ]);

        $response = [
            'message' => "Data Berhasil Ditambahkan",
            'status' => true,
        ];
        return Response::json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $income = Income::with('typeincome')->find($id)->get();

        $response = [
            "message" => "",
            "status" => true,
            "data" => $income
        ];

        return Response::json($response, 201);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $income = Income::find($id);

        $response = [
            'message' => "",
            'status' => true,
            'data' => $income
        ];

        return Response::json($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required',
            'typeincome_id' => 'required',
            'price' => 'required',
            'desc' => 'required'
        ]);

        $income = Income::find($id);
        $income->date = $request->date;
        $income->name = $request->name;
        $income->typeincome_id = $request->typeincome_id;
        $income->price = $request->price;
        $income->desc = $request->desc;
        $income->save();

        $response = [
            'message' => 'Data berhasil diubah',
            'status' => true,
        ];

        return Response::json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Income::destroy($id);
        $response = [
            'message' => "Data berhasil dihapus",
            'status' => true,
        ];
        return Response::json($response, 201);
    }
}
