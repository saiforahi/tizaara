<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index']]);
    }

    public function index(Request $request)
    {
        $columns = ['id', 'name', 'code'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return DB::table('countries')->get();
        }
        $query = Country::orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('code', 'like', '%' . $searchValue . '%');
            });
        }

        $projects = $query->latest()->paginate($length);
        return ['data' => $projects, 'draw' => $request->input('draw')];
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:countries',
            'code' => 'required|max:100|unique:countries',
        ]);
        $country = new Country();
        $country->name = $request->name;
        $country->code = $request->code;
        $country->code_a3 = $request->code_a3;
        $country->code_n3 = $request->code_n3;
        $country->lat = $request->lat;
        $country->long = $request->long;
        $country->status = $request->status ?? Country::STATUS_NOT_AVAILABLE;
        $country->created_by = auth()->user()->id;

        return $country->save();
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
            'name' => 'required|max:100|unique:countries,name,' . $id,
            'code' => 'required|max:100|unique:countries,code,' . $id,
        ]);
        $country = Country::findOrFail($id);
        $country->name = $request->name;
        $country->code = $request->code;
        $country->code_a3 = $request->code_a3;
        $country->code_n3 = $request->code_n3;
        $country->lat = $request->lat;
        $country->long = $request->long;
        $country->updated_by = auth()->user()->id;

        return $country->update();
    }

    public function destroy($id)
    {
        Country::findOrFail($id)->delete();
        return response()->json(['result' => 'Success', 'message' => 'Country has been deleted'], 200);
    }

    public function updateStatus($id, $id2)
    {
        $country = Country::findOrFail($id);
        $country->status = $id2;
        return $country->save();
    }
}
