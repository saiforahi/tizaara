<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRequestController extends Controller
{
    //
    public function ecomZoneRequestList(Request $request)
    {
        $product = collect();
        $request = DB::table('product_requests')->where('request_type', 1)->orderBy('id', 'DESC')->get();
        foreach ($request as $item) {
            $data = DB::table('products')->where('id', $item->product_id)->first();
            $user = DB::table('users')->where('id', $item->user_id)->first();
            // $status = $item->status == 0 ? 'Request' : 'Approve';
            $status = $item->status;
            $product->push([
                'id' => $item->id, 'image' => $data->thumbnail_img, 'product_name' => $data->name, 'user_name' => $user->first_name . ' ' . $user->last_name, 'status' => $status,
                'discount' => $item->discount, 'discount_type' => $item->discount_type,

            ]);
        }
        return $product;
    }

    public function statusUpdate(Request $request)
    {
        $product = ProductRequest::findOrFail($request->id);
        $product->status = (int)$request->status;
        $product->save();
    }
    public function destroy($id)
    {
        ProductRequest::findOrFail($id)->delete();
        return response()->json(['result' => 'Success', 'message' => 'Ecom Zone Request has been deleted'], 200);
    }

    public function ecom_zone_products(){
        try{
            $product = collect();
            $request = DB::table('product_requests')->where('request_type', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
            foreach ($request as $item) {
                $data = Product::where('id', $item->product_id)->with([
                    'currency'=>function($q){$q->select('id','symbol');},
                    'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                    'product_stock',
                ])->first();
                $user = DB::table('users')->where('id', $item->user_id)->first();
                // $status = $item->status == 0 ? 'Request' : 'Approve';
                $status = $item->status;
                $product->push([
                    'id' => $item->id, 'product'=>$data,'image' => $data->thumbnail_img,'unit_price'=>$data->unit_price, 'product_name' => $data->name, 'user_name' => $user->first_name . ' ' . $user->last_name, 'status' => $status,
                    'discount' => $item->discount, 'discount_type' => $item->discount_type,

                ]);
            }
            return $product;
        }
        catch(Exception $e){
            throw $e;
        }
    }
}
