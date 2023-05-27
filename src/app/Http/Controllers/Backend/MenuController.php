<?php

namespace App\Http\Controllers\Backend;

use App\Models\Menu;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::with('categories')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $label = $row->status ? 'Tersedia' : 'Tidak Tersedia';
                    $class = $row->status ? 'btn-success' : 'btn-danger';
                    return Blade::render(
                        '<a href="{{ route("backend.menu.change-status", $row->id) }}"  class="btn {{ $class }} btn-sm" >{{ $label }}</a>',
                        ['row' => $row, 'class' => $class, 'label' => $label]
                    );
                })
                ->addColumn('action', function ($row) {
                    return Blade::render(
                        '
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-yellow btn-edit me-1" data-bs-toggle="modal" data-bs-target="#tambah-data-modal" data-detail="' . htmlspecialchars($row) . '" data-id=' . $row->id . '>
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
                ->addColumn('categories', function ($row) {
                    return $row->categories->name;
                })
                ->rawColumns(['action', 'categories', 'status'])
                ->make(true);
        }
        $data['title'] = 'Menu';
        return view('backend.menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Menu';
        $data['category'] = Categories::get();
        return view('backend.menu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|integer',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'status' => '1',
            'price' => $request->price,
            'desc' => $request->desc
        ]);
        // toastr()->success('Menambahkan Data Menu Berhasil !!!');
        $response = [
            "message" => "Data berhasil dibuat",
            "status" => true
        ];
        // return redirect()->route('backend.menu');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = 'edit data menu';
        $data['menu'] = Menu::findorFail($id);
        $data['category'] = Categories::all();
        return view('backend.menu.edit', $data);
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
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|integer',
            // 'desc'=>'required'
        ]);
        $menu = Menu::find($id);

        $menu->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'desc' => $request->desc,
        ]);
        // toastr()->success('Data Menu Berhasil Diupdate !!!');
        // return redirect()->route('backend.menu');
        $response = [
            "message" => "Data berhasil diubah",
            "status" => true
        ];
        return Response::json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::destroy($id);
        $response = [
            "message" => "Data berhasil dihapus",
            "status" => true
        ];
        return Response::json($response, 201);
    }

    public function changeStatus($id)
    {
        $menu = Menu::find($id);
        $menu->status = ($menu->status == 1) ? 0 : 1;
        $menu->save();
        return back();
    }
}
