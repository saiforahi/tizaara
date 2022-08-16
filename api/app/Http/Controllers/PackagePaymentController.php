<?php

namespace App\Http\Controllers;

use App\Http\Resources\PackagePaymentResource;
use App\PackagePayment;
use Illuminate\Http\Request;

class PackagePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['id', 'supplier.full_name', 'payment_date', 'payment_type', 'amount'];
        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return PackagePayment::query()->orderBy('id', 'DESC')->get();
        }
        $query = PackagePayment::query()->orderBy($columns[$column], $dir);
        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->whereHas('supplier', function ($q) use ($searchValue) {
                    $q->where('first_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('last_name', 'like', '%' . $searchValue . '%');
                })->orWhereHas('package', function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%');
                });
            });
        }
        $projects = $query->latest()->paginate($length);

        return $this->pagination($projects, PackagePaymentResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $packagePayment = PackagePayment::query()->find($id);
        $packagePayment->delete();

        return response()->json(['result' => 'Success', 'message' => 'Package payment has been deleted'], 200);
    }
}
