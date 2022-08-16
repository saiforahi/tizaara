<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    /*
     * method for get buying order information
     * */
    public function orders()
    {
        $orders = Order::with([
            'supplier'=>function($q){$q->select('id','first_name','last_name'); }
        ])->orderBy('id','DESC')->get();
        return response()->json($orders,200);
    }

    /*
     * method for order status change
     * */
    public function statusChange( $status, Order $order)
    {
        $ss = $order->update(['approval_status'=>$status,'approved_by'=>auth()->id()]);
        if(isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully change status'],200);
        return response()->json(['status'=>'error','message'=>'Invalid request'],404);
    }

}
