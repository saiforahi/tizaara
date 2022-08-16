<?php

namespace App\Http\Controllers;

use App\GuestUserSetting;
use Illuminate\Http\Request;
use App\Traits\FileUpload;

class GuestUserSettingController extends Controller
{
    use FileUpload;

    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index']]);
    }

    public function index()
    {
        $setting = GuestUserSetting::all()->first();
        if ($setting) {
            return $setting;
        } else {
            $setting = new GuestUserSetting();
            $setting->id = 1;
            $setting->save();
            $setting = GuestUserSetting::all()->first();
            return $setting;
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $general = GuestUserSetting::query()->findOrFail($id);
        $general->max_allowed_products = $request->max_allowed_products;
        $general->max_allowed_keywords = $request->max_allowed_keywords;
        $general->updatedBy()->associate(auth()->user());
        $general->save();
    }

    public function destroy($id)
    {
        //
    }
}
