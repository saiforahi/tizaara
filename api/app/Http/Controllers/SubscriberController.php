<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    /*
     * method for get all subscribers
     * */
    public function subscribers(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Subscriber::all(['id','email']));
    }

    /*
     * method for gel all subscribers
     * */
    public function remove(Subscriber $subscriber): \Illuminate\Http\JsonResponse
    {
        $subscriber->delete();
        return response()->json(['status'=>'success','message'=>'Successfully removed'],200);
    }
}
