<?php

namespace App\Http\Controllers;

use App\CompanyBasicInfo;
use App\PackagePayment;
use App\Product;
use App\ProductFavorite;
use App\ProductRating;
use App\ProductReview;
use App\Quotation;
use App\QuotationUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    public function index()
    {
        return DB::table('users')->whereIn('account_type', [0, 2])->get();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
        $product = Product::where('user_id', $id)->first();
        $company_basic_info = CompanyBasicInfo::where('user_id', $id)->first();
        $product_rating = ProductRating::where('user_id', $id)->first();
        $product_favorite = ProductFavorite::where('user_id', $id)->first();
        $package_payment = PackagePayment::where('user_id', $id)->first();
        $quotation = Quotation::where('user_id', $id)->first();
        $quotation_user = QuotationUser::where('user_id', $id)->first();
        $product_review = ProductReview::where('user_id', $id)->first();
        if ($product_rating || $product_favorite || $product || $package_payment || $company_basic_info || $quotation || $quotation_user || $product_review) return response()->json(['result' => 'Error', 'message' => 'You can\'t delete this user'], 200);
        User::findOrFail($id)->delete();
    }

    public function updateStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required',
        ]);
        DB::table('users')->where('id', $request->id)->update([
            'status' => $request->status
        ]);
        return $request->all();
    }
}
