<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\GuestUserSetting;
use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\Traits\Slug;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Slug;
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:admins',
            'email' => 'required|string|unique:admins',
            'password' => 'required|confirmed',
        ]);

        try {
            $user = new Admin();
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json([
                'entity' => 'admins',
                'action' => 'create',
                'result' => 'success'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'entity' => 'admins',
                'action' => 'create',
                'result' => 'failed'
            ], 409);
        }
    }
    /*
     * method for supplier or buyer register
     * */
    public function sbRegister(Request $request)
    {
        Validator::make($request->all(),[
            'account_type'=>'required|numeric|not_in:-1|max:2',
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'email'=>'required|email|max:50|unique:users,email',
            'mobile'=>'required|max:50|unique:users,mobile',
        ])->validate();

        $username = $this->username($request->first_name . $request->last_name);
        $data =$request->all();
        $password=rand(11111111,99999999);
        $data +=[
            'ip_address'=>$request->ip(),
            'username'=>$username,
            'is_verified'=>1,
            'status'=>2,
            'password'=>Hash::make($password),
        ];
        try {
            $user = User::create($data);
            if (isset($user->id)) {
                $setting = GuestUserSetting::all()->first();
                $user->guestUserSetting()->associate($setting);
                $user->save();
            }
            $subject = "Welcome to tizaara";
            $name = $request->first_name . ' ' . $request->last_name;
            Mail::send('email.registration', ['name' => $name, 'password' => $password],
                function ($mail) use ($request, $name, $subject) {
                    $mail->from("rofequlislamnayem@gmail.com", "Tizaara.com");
                    $mail->to($request->email, $name);
                    $mail->subject($subject);
                });

            return response()->json(['status'=>'success','message'=>'successfully created'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'entity' => 'users',
                'action' => 'create',
                'result' => 'failed'
            ], 500);
        }

    }

    /*
     *
     * method for supplier or buyer update
     * */
    public function sbUpdate(Request $request, User $user)
    {
        Validator::make($request->all(),[
            'account_type'=>'required|numeric|not_in:-1|max:2',
            'first_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'email'=>'required|email|max:50|unique:users,email,'.$user->id,
            'mobile'=>'required|max:50|unique:users,mobile,'.$user->id,
        ])->validate();
        $ss = $user->update($request->only(['account_type','first_name','last_name','email','mobile','membership_plan_id']));
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'successfully updated'],200);
        return  response()->json(['status'=>'error','message'=>'Invalid request'],404);

    }
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::guard('admins')->attempt($credentials)) {
            return response()->json(['errors' => 'The login detail is incorrect', 'message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function profile()
    {
        return response()->json(['user' => auth::guard('admins')->user()]);
    }

    /*
     * method for change password
     * */
    public function changePassword(Request $request)
    {
        Validator::make($request->all(),[
            'old_password'=>'required',
            'password'=>'required|confirmed|min:6'
        ])->validate();
        $hashpassword = auth()->user()->password;

        if(Hash::check($request->old_password, $hashpassword)){
            if (!Hash::check($request->password,$hashpassword)){
                $user = Admin::find(auth()->id());
                $user->update(['password'=>Hash::make($request->password)]);

                $msg = "!Successfully change the password";
                return response()->json(['status'=>'success','message'=>$msg],200);

            }else{
                $msg ="Error! New password cannot be the same as old password";
                return response()->json(['status'=>'error','message'=>$msg],200);
            }
        }else{
            $msg ="Error! old password not match!!";
            return response()->json(['status'=>'error','message'=>$msg],200);
        }
    }

    public function userList()
    {
        return User::where('is_verified', 1)->get();
    }

    public function supplierList()
    {
        return DB::table('users')->where('is_verified', 1)->where('account_type', '!=', 2)->select('first_name',
            'last_name', 'id')->get();
    }

    public function userVerify($id)
    {
        $user = User::findOrFail($id);
        $user->status = 2;
        $user->save();
        return User::where('is_verified', 1)->get();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admins')->factory()->getTTL()
        ], 200);
    }

}
