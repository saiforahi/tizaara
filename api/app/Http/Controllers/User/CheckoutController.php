<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function checkOut(Request $request)
    {
        Validator::make($request->all(),[
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'email'=>'email',
            'phone_number'=>'required|max:20',
            'address_l1'=>'required',
            'country_id'=>'required|not_in:0|exists:countries,id',
            'division_id'=>'required|not_in:0|exists:divisions,id',
            'city_id'=>'required|not_in:0|exists:cities,id',
            'user_id'=>'required|not_in:0|exists:users,id',
            'zip'=>'required|numeric',
            'carts.*'=>'required',
            'carts.*.product'=>'required',
            'carts.*.discount_price'=>'required',
            'carts.*.price'=>'required',
            'carts.*.flash_deal'=>'required',
            'carts.*.quantity'=>'required',
        ])->validate();
        //return response()->json($request->all(),200);
        $common_data =[
            'name'=>$request->s_first_name.' '.$request->s_last_name,
            'email'=>$request->s_email,
            'phone'=>$request->s_phone_number,
            'address'=>$request->s_address_l1.' '.$request->s_address_l2,
            'country_id'=>$request->s_country_id,
            'division_id'=>$request->s_division_id,
            'city_id'=>$request->s_city_id,
            'zip_code'=>$request->s_zip,
            'billing_info'=>json_encode($request->only(['first_name',
                'last_name','email','phone_number','address_l1','address_l2','country_id',
                'division_id','city_id','zip'])),
            'transaction_id'=>uniqid(),
            'payment_type'=>0,
            'status'=>'success',
            'buyer_id'=>$request->user_id,
            'ip'=>$request->ip(),
            'browser'=>$request->userAgent(),
            'currency'=>'BDT'
        ];
        $status=0;
        foreach (collect($request->carts) as $cart){
            $data1=$common_data;
            $sub_total = $cart['discount_price']*$cart['quantity'];
            $shipping_charge = $cart['product']['shipping_cost']??0;
            $vat = $cart['product']['tax']??0;
            $data1 +=[
                'product_id'=>$cart['product']['id'],
                'product'=>json_encode($cart['product']),
                'product_variant'=>json_encode($cart['variant']),
                'offer'=>json_encode($cart['flash_deal']),
                'quantity'=>$cart['quantity'],
                'unit_price'=>$cart['price'],
                'offer_price'=>$cart['discount_price'],
                'sub_total'=>$sub_total,
                'shipping_charge'=>$shipping_charge,
                'vat'=>$vat,
                'total_amount'=>$sub_total+$shipping_charge+$vat,
                'supplier_id'=>$cart['product']['user_id'],
                'payment_type'=>0,
            ];
            Order::create($data1);
            $status +=1;
        }
        if ($status>0) return response()->json(['status'=>'success','message'=>'Thank you for your order'],200);
        return response()->json($request,200);
    }
}
