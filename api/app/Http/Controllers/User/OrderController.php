<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /*
     * method for get buying order information
     * */
    public function buyerOrders()
    {
        $orders = Order::with([
            'supplier'=>function($q){$q->select('id','first_name','last_name'); }
        ])->where(['buyer_id'=>auth()->id()])->where('status','!=','Pending')->orderBy('id','DESC')->get();
        return response()->json($orders,200);
    }

    /*
     * method for supplier product status change
     * */
    public function statusChange(Order $order, $status)
    {
        if ($order->supplier_id !=auth()->id()) return response()->json(['status'=>'error','message'=>'You don\'t have permission to do this'],403);
        $ss = $order->update(['order_status'=>$status,'updated_by'=>auth()->id()]);
        if(isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully change status'],200);
        return response()->json(['status'=>'error','message'=>'Invalid request'],404);
    }
    /*
     * method for get buying order information
     * */
    public function supplierOrders()
    {
        $orders = Order::where(['supplier_id'=>auth()->id(),'approval_status'=>1])->where('status','!=','Pending')->orderBy('id','DESC')->get();
        return response()->json($orders,200);
    }
}
