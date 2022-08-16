<?php

namespace App\Http\Controllers;

use App\SellerPackage;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SellerPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index']]);
    }

    use FileUpload;

    public function index()
    {
        return DB::table('seller_packages')->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:seller_packages',
            'amount' => 'required',
            'upload' => 'required',
            'duration' => 'required',
            'logo' => 'required',
        ]);

        $data = $request->all();
        if ($request->logo != '') {
            $data['logo'] = $this->saveImages($request, 'logo', 'upload/package/', 400, 400);
        }

        return SellerPackage::create($data);

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
        $this->validate($request, [
            'name' => 'required|max:50|unique:seller_packages,name,' . $id,
            'amount' => 'required',
            'upload' => 'required',
            'duration' => 'required',
        ]);

        $package = SellerPackage::findOrFail($id);
        $data = $request->all();
        if ($request->logo != '' && strlen($request->logo) > 200) {
            File::delete(public_path($package->logo));
            $data['logo'] = $this->saveImages($request, 'logo', 'upload/package/', 400, 400);
        }
        $package->update($data);
        return SellerPackage::findOrFail($id);
    }

    public function destroy($id)
    {
        $package = SellerPackage::findOrFail($id);
        File::delete(public_path($package->logo));
        $package->delete();
        return response()->json(['result' => 'Success', 'message' => 'Package has been deleted'], 200);
    }
}
