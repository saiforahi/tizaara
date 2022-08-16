<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    /*
     * method for email verify and verification token sent
     * */
    public function verifyTokenSend(Request $request)
    {
        Validator::make($request->all(),[
            'email' => 'required|email',
        ])->validate();

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $verification_code = CommonHelper::generateOTP(6);
            $user->update(['verificationToken'=>$verification_code]);
            $subject = "Forget password";
            $email = $user->email;
            Mail::send('email.forgetPassword', ['verification_code' => $verification_code],
                function ($mail) use ($email, $subject) {
                    $mail->from("support@tizaara.com", "Tizaara.com");
                    $mail->to($email);
                    $mail->subject($subject);
                });
            return response()->json(['status'=>'success','message'=>'Verification code is sent'],200);
        } else {
            return response()->json(['status'=>'error','message'=>'Not match any record'],200);
        }
    }
    /*
     * method for verification token match
     *
     * */
    public function verificationTokenMatch(Request $request)
    {
        Validator::make($request->all(),[
            'email' => 'required|email',
            'verificationToken'=>'required|numeric'
        ])->validate();
        $user = User::where(['email'=> $request->email,'verificationToken'=>$request->verificationToken])->first();
        if (isset($user->id)) return response()->json(['status'=>'success','message'=>'Successfully match'],200);
        return response()->json(['status'=>'error','message'=>'Not match'],200);
    }

    /*
     *
     * method for new password set
     * */
    public function passwordSet(Request $request)
    {
        Validator::make($request->all(),[
            'email' => 'required|email',
            'password'=>'required|string'
        ])->validate();
        $user = User::where(['email'=> $request->email])->first();
        if (isset($user->id)) {
            $user->update(['password'=>Hash::make($request->password)]);
            return response()->json(['status' => 'success', 'message' => 'Successfully change password'], 200);
        }
        return response()->json(['status'=>'error','message'=>'Invalid request'],200);
    }
}
