<?php

namespace App\Http\Controllers;

use App\Business_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index','activeBusinessTypes']]);
    }

    public function index(Request $request)
    {
        $columns = ['id', 'name', 'status'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return DB::table('business_types')->select('id', 'name')->orderBy('id', 'DESc')->get();
        }
        $query = Business_type::orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('code', 'like', '%' . $searchValue . '%');
            });
        }

        $projects = $query->latest()->paginate($length);
        return ['data' => $projects, 'draw' => $request->input('draw')];
    }

    /*
     * method for get all active business types
     * */
    public function activeBusinessTypes()
    {
        return response()->json(
            Business_type::where('status',1)->get(['id','name']),200
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:business_types',
        ]);

        return Business_type::create($request->all());
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
            'name' => 'required|max:255|unique:business_types,name,' . $id,
        ]);

        return Business_type::findOrFail($id)->update($request->all());
    }

    public function updateStatus($id, $id2)
    {
        $currency = Business_type::findOrFail($id);
        $currency->status = $id2;
        return $currency->save();
    }

    public function destroy($id)
    {
        //
    }

}
