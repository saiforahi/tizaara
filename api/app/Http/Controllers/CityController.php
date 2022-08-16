<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index','topCities']]);
    }

    public function index(Request $request)
    {
        $columns = ['id', 'name'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return DB::table('cities')->get();
        }
        $query = City::with('division','division.country')->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
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
            'name' => 'required|max:100|unique:divisions',
            'division_id' => 'required',
        ]);
        $city = new City();
        $city->name = $request->name;
        $city->division_id = $request->division_id;
        $city->status = $request->status ?? City::STATUS_NOT_AVAILABLE;
        $city->created_by = auth()->user()->id;

        return $city->save();
    }

    public function show($id)
    {
        return City::select('id', 'name')->where('division_id', $id)->get();
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:cities,name,' . $id,
            'division_id' => 'required',
        ]);
        $city = City::findOrFail($id);
        $city->name = $request->name;
        $city->division_id = $request->division_id;
        $city->status = $request->status ?? City::STATUS_NOT_AVAILABLE;
        $city->updated_by = auth()->user()->id;

        return $city->update();
    }

    public function destroy($id)
    {
        //
    }

    public function updateStatus($id, $id2)
    {
        $city = City::findOrFail($id);
        $city->status = $id2;
        return $city->save();
    }

    /*
     * method for find top cities
     * */
    public function topCities()
    {
        $cities =City::whereIn('id', Address::all()->pluck('city_id')->unique()->whereNotNull())->inRandomOrder()->limit(12)->get();
        return response()->json($cities,200);
    }
}
