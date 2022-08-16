<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index']]);
    }

    public function index(Request $request)
    {
        $columns = ['id', 'name'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return DB::table('areas')->get();
        }
        $query = Area::with('city.division.country')->orderBy($columns[$column], $dir);

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

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:areas',
            'zip_code' => 'required|max:100',
            'city_id' => 'required',
        ]);
        $area = new Area();
        $area->name = $request->name;
        $area->city_id = $request->city_id;
        $area->zip_code = $request->zip_code;
        $area->status = $request->status ?? Area::STATUS_NOT_AVAILABLE;
        $area->created_by = auth()->user()->id;

        return $area->save();
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
            'name' => 'required|max:100|unique:areas,name,' . $id,
            'zip_code' => 'required|max:100',
            'city_id' => 'required',
        ]);
        $area = Area::findOrFail($id);
        $area->name = $request->name;
        $area->city_id = $request->city_id;
        $area->zip_code = $request->zip_code;
        $area->status = $request->status ?? Area::STATUS_NOT_AVAILABLE;
        $area->updated_by = auth()->user()->id;

        return $area->update();
    }

    public function destroy($id)
    {
        //
    }

    public function updateStatus($id, $id2)
    {
        $area = Area::findOrFail($id);
        $area->status = $id2;
        return $area->save();
    }
}
