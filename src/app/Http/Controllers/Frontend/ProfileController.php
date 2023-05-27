<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('Profile', [
            "title" => "Profil",
            "settings" => Settings::first(),
            "userLoggedIn" => auth()->user()
        ]);
    }

    public function me()
    {
        return Response::json([
            'status' => true,
            'data' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => [
                    'name' => 'Kasir',
                ],
                'joined_at' => date('Y', strtotime(auth()->user()->created_at)),
                'photo' => auth()->user()->photo == null ? URL::to('img/avatar.png') : auth()->user()->photo
            ]
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $userOld = User::find($request->id);
        $user = User::find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != "") {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        if ($request->hasFile('photo')) {
            $imageName = time() . "_" . $request->file('photo')->getClientOriginalName();
            $path = 'images/photo_profile/' . $imageName;
            Storage::disk('public')->put($path, File::get($request->file('photo')));

            User::find($request->id)->update(['photo' => $path]);
            
            if (file_exists(public_path() . '/' . 'storage/' . $userOld->photo_raw)) {
                unlink(public_path() . '/' . 'storage/' . $userOld->photo_raw);
            }
        }

        $response = [
            "message" => "Data berhasil diubah",
            "status" => true
        ];

        return Response::json($response, 201);
    }
}
