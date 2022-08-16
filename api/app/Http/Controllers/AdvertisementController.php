<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdvertisementController extends Controller
{
    use FileUpload;

    public function index()
    {
        return DB::table('advertisements')->get();
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'add_no' => 'required',
            'banner' => 'required',
        ]);
        $data = $request->all();
        $data['banner'] = $this->saveImages($request, 'banner', 'upload/advertisement/', 800, 300);
        $check = Advertisement::where('add_no', $request->add_no)->first();
        if ($check) {
            File::delete(public_path($check->banner));
            $check->delete();
        };
        return Advertisement::create($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $brand = Advertisement::where('add_no', $id)->first();
        if ($brand) {
            File::delete(public_path($brand->banner));
            $brand->delete();
        }
    }
}
