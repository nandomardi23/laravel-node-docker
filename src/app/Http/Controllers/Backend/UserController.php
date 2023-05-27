<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = User::select('users.*', 'role_users.*')
                ->join('role_users', 'role_users.user_id', '=', 'users.id')
                // ->join('roles', 'roles.id', '=', 'users.id')
                ->get();

            $users->map(function ($user) {
                $user->role = $user->role;
                return $user;
            });

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function($row) {
                    $role = '';

                    if ($row->role->name == 'admin') {
                        $role = '<span class="badge rounded-pill bg-warning shadow-sm"><strong>' . ucwords($row->role->name) . '</strong></span>';
                    } else if ($row->role->name == 'cashier') {
                        $role = '<span class="badge rounded-pill bg-gray shadow-sm"><strong>' . ucwords($row->role->name) . '</strong></span>';
                    }

                    return $role;
                })
                ->addColumn('action', function($row) {
                    $btn = '
            <a href="javascript:void(0)" class="btn btn-sm btn-outline-yellow btn-edit me-1" data-detail="' . htmlspecialchars($row) . '"  data-id=' . $row->id . '>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                <path d="M16 5l3 3"></path>
            </svg>Edit</a>
            <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm btn-delete" data-detail="' . htmlspecialchars($row) . '"  data-id=' . $row->id . '>
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
                ->rawColumns(['action', 'role'])
                ->make(true);
        }

        $data['roles'] = Role::all();
        $data['title'] = 'User';

        return view('backend.user.index', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => ''
        ]);

        if ($user) {
            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id
            ]);
        }

        $response = [
            "message" => "Data berhasil dibuat",
            "status" => true
        ];

        return Response::json($response, 201);
    }

    public function show($id)
    {
        $user = User::select('users.*', 'role_users.*')
            ->join('role_users', 'role_users.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->first();

        $response = [
            "status" => true,
            "message" => "",
            "data" => $user
        ];

        return Response::json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required'
        ]);

        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != "") {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        if ($user) {
            RoleUser::where('user_id', $id)->update([
                'user_id' => $id,
                'role_id' => $request->role_id
            ]);
        }

        $response = [
            "message" => "Data berhasil diubah",
            "status" => true
        ];

        return Response::json($response, 201);
    }

    public function delete($id)
    {
        User::destroy($id);

        $response = [
            "message" => "Data berhasil dihapus",
            "status" => true
        ];

        return Response::json($response, 201);
    }

    public function emailValidator(Request $request)
    {
        // $request->validate([
        //     'email' => 'email'
        // ]);

        $user = User::where('email', $request->email)->get();

        $request = Array();

        if (count($user) >= 1) {
            $response = [
                "message" => "Email tersebut telah dipakai. Coba dengan email lain.",
                "status" => false
            ];
        } else {
            $response = [
                "message" => "Email tersebut dapat digunakan",
                "status" => true
            ];
        }

        return Response::json($response, 201);
    }
}
