<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Order;
use App\UserMembershipPlan;
use Exception;
use Illuminate\Http\Request;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

class PaymentController extends Controller
{
    public function shurjopay_check($id){
        $shurjopay_service = new ShurjopayController(); 
        return $shurjopay_service->verify($id);
    }
    //
    public function shurjopay_checkout(Request $request){
        try{
            
            $info = array( 
                'currency' => "BDT", 
                'amount' => $request->query('amount'),  
                'discsount_amount' => null, 
                'order_id' => $request->query('order_id'),
                'disc_percent' => null, 
                'client_ip' => $request->ip(), 
                'customer_name' => $request->query('name'), 
                'customer_phone' => $request->query('phone'), 
                'email' => $request->query('email'), 
                'customer_address' => $request->query('address'), 
                'customer_city' => $request->query('city'), 
                'customer_state' => $request->query('division'), 
                'customer_postcode' => $request->query('zip'), 
                'customer_country' => $request->query('country'),
                'value1'=> $request->query('value1'),
                'value2' => $request->query('value2'),
            );
            $shurjopay_service = new ShurjopayController(); 
            return $shurjopay_service->checkout($info);
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function update_order_payment_status(Request $req){
        try{
            $req->validate([
                'order_id'=>'required|string'
            ]);
            $shurjopay_service = new ShurjopayController(); 
            $result= $shurjopay_service->verify($req->order_id);
            $tz_order_id=json_decode($result,true)[0]['value1'];
            $tz_order_type=json_decode($result,true)[0]['value2'];
            $res_message="";
            if($tz_order_type=="shopping"){
                Order::where('transaction_id',$tz_order_id)->update([
                    'shurjopay_order_id'=>$req->order_id,
                    'payment_status'=>true,
                    'payment_type'=>1,
                ]);
                $res_message="Your order is successfully completed. Thank you for shopping with us. You can see your transaction history
                in your dashboard.";
            }
            elseif($tz_order_type=="membership"){
                UserMembershipPlan::where('id',$tz_order_id)->update([
                    'shurjopay_order_id'=>$req->order_id,
                    'payment_status'=>true,
                ]);
                $res_message="Your membership plan payment is successful and has been activated.";
            }
            return response()->json(['success'=>true,'message'=>$res_message,'data'=>json_decode($result,true)[0]],200);
        }
        catch (Exception $e){
            return response()->json(['success'=>false,'error'=>$e->getMessage()],503);
        }
    }
}
