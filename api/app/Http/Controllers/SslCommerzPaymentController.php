<?php

namespace App\Http\Controllers;

use App\Order;
use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\Validator;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function payViaAjax(Request $request)
    {

        $new_request = $request->order;
        $request = new Request($new_request);

        //return response()->json($request->all());
        Validator::make($request->all(),[
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'email'=>'email',
            'phone_number'=>'required|max:20',
            'address_l1'=>'required',
            'country_id'=>'required|not_in:0|exists:countries,id',
            'division_id'=>'required|not_in:0|exists:divisions,id',
            'city_id'=>'required|not_in:0|exists:cities,id',
            'zip'=>'required|numeric',
            'pay_amount'=>'required|numeric',
            'user_id'=>'required|not_in:0|exists:users,id',
            'carts.*'=>'required',
            'carts.*.product'=>'required',
            'carts.*.discount_price'=>'required',
            'carts.*.price'=>'required',
            'carts.*.flash_deal'=>'required',
            'carts.*.quantity'=>'required',
        ])->validate();
        //return response()->json($request->all(),200);
        $transaction_id = uniqid();
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
            'transaction_id'=>$transaction_id,
            'payment_type'=>1,
            'status'=>'Pending',
            'pay_amount'=>$request->pay_amount,//this all product purces amount
            'buyer_id'=>$request->user_id,
            'ip'=>$request->ip(),
            'browser'=>$request->userAgent(),
            'currency'=>'BDT'
        ];
        foreach (collect($request->carts) as $cart){
            $data1=$common_data;
            $sub_total = $cart['discount_price']*$cart['quantity'];
            $shipping_charge = $cart['product']['shipping_cost']??0;
            $vat = $cart['product']['tax']??0;
            $data1 +=[
                'product_id'=>$cart['product']['id'],
                'product'=>json_encode($cart['product']),
                'product_variant'=>isset($cart['variant'])?json_encode($cart['variant']):json_encode([]),
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
        }

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $request->pay_amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $transaction_id; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->first_name.' '.$request->last_name;
        $post_data['cus_email'] = $request->email;
        $post_data['cus_add1'] = $request->address_l1;
        $post_data['cus_add2'] = $request->address_l2;
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $request->phone_number;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = $request->s_first_name.' '.$request->s_last_name;;
        $post_data['ship_add1'] = $request->s_address_l1;
        $post_data['ship_add2'] = $request->s_address_l1;
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = $request->s_phone_number;
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "tizaara product";
        $post_data['product_category'] = "example category";
        $post_data['product_profile'] = "example product";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        /*$update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'total_amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);*/

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request)
    {
        //echo "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'pay_amount')->first();

        if ($order_detials->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing','payment_info'=>json_encode($request->all())]);
                //echo "success";
                return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/success");
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Failed']);
                //echo "validation Fail";
                return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/error");
            }
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            //echo "Transaction is successfully Completed";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/success");
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            //echo "Invalid Transaction";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/error");
        }


    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'pay_amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            //echo "Transaction is Failed";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/error");
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            //echo "Transaction is already Successful";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/success");
        } else {
            //echo "Transaction is Invalid";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/error");
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'pay_amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            //echo "Transaction is Cancel";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/cancel");
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            //echo "Transaction is already Successful";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/success");
        } else {
            //echo "Transaction is Invalid";
            return redirect()->away(env('PUBLIC_DOMAIN')."/checkout/error");
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'pay_amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->pay_amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

                    echo "validation Fail";
                }

            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
