<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use InvertionImage;

class SettingController extends Controller
{
    public function index()
    {
        $data['title'] = 'Settings';
        $data['setting'] = Settings::first();
        return view('backend.setting.index', $data);
    }

    public function updateGeneralData(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $settings = Settings::first();

        $settings->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email
        ]);

        return back();
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'icons' => 'required|image|mimes:png,jpg,jpeg,gif',
        ]);

        $path = storage_path('app/public/icons');

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $icons = $request->icons;
        $filename = $icons->getClientOriginalName();
        $extention = explode(".", $filename);
        $newFileName = uniqid() . "." . $extention[1];

        $logoResize = InvertionImage::make($icons->getRealPath());
        $logoResize->resize(256, 256);
        $logoResize->save(storage_path('app/public/icons/' . $newFileName));

        $settings = Settings::first();
        if ($settings->icons != null && Storage::disk('public')->exists($settings->icons)) {
            Storage::disk('public')->delete($settings->icons);
        }
        $settings->icons = 'icons/' . $newFileName;
        $settings->save();

        return back();
    }
}
