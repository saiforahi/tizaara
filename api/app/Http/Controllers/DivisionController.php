<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
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
            return DB::table('divisions')->get();
        }
        $query = Division::with('country')->orderBy($columns[$column], $dir);

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
            'country_id' => 'required',
        ]);
        $division = new Division();
        $division->name = $request->name;
        $division->country_id = $request->country_id;
        $division->status = $request->status ?? Division::STATUS_NOT_AVAILABLE;
        $division->created_by = auth()->user()->id;

        return $division->save();
    }

    public function show($id)
    {
        return Division::select('id', 'name')->where('country_id', $id)->orderBy('id', 'DESc')->get();
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:divisions,name,' . $id,
            'country_id' => 'required',
        ]);
        $division = Division::findOrFail($id);
        $division->name = $request->name;
        $division->country_id = $request->country_id;
        $division->status = $request->status ?? Division::STATUS_NOT_AVAILABLE;
        $division->updated_by = auth()->user()->id;

        return $division->update();
    }

    public function destroy($id)
    {
        //
    }

    public function updateStatus($id, $id2)
    {
        $division = Division::findOrFail($id);
        $division->status = $id2;
        return $division->save();
    }
}
