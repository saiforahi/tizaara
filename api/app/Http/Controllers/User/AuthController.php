<?php

namespace App\Http\Controllers\User;

use App\GuestUserSetting;
use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\User;
use Google_Client;
use Illuminate\Http\Request;
use App\Traits\Slug;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    use Slug;
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:users', ['except' => ['login', 'loginByGoogle', 'loginByFacebook', 'register', 'verify', 'verifyTokenSend', 'supplierSearch']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'account_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'emailOrMobile' => 'required',
            'password' => 'required',
        ]);

        $regMedium = filter_var($request->emailOrMobile, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $this->validateUsername($request, $regMedium);

        $email = $regMedium == 'email' ? $request->emailOrMobile : null;
        $phone = $regMedium == 'email' ? null : $request->emailOrMobile;
        $number = "";
        if ($phone) {
            $number = $this->phone_number($phone);
            if (!$number) {
                return $this->errorResponse('Invalid email and phone number');
            }
        }
        $username = $this->username($request->first_name . $request->last_name);
        $verification_code = CommonHelper::generateOTP(6);

        $user = new User();
        $user->account_type = $request->account_type;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->ip_address = $request->ip_address;
        $user->username = $username;
        $user->password = Hash::make($request->password);
        $user->email = $email;
        $user->mobile = $phone;
        $user->verificationToken = $verification_code;
        $user->save();
        if ($user) {
            $setting = GuestUserSetting::all()->first();
            $user->guestUserSetting()->associate($setting);
            $user->save();
        }
        if ($email) {
            $subject = "Please verify your email address.";
            $name = $request->first_name . ' ' . $request->last_name;
            Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
                function ($mail) use ($email, $name, $subject) {
                    $mail->from(env('FROM_EMAIL_ADDRESS'), "Tizaara.com");
                    $mail->to($email, $name);
                    $mail->subject($subject);
                });
        }
        if ($phone) {
            $message = 'Please enter the following code ' . $verification_code . ' to verify your account.';
            $post_url = 'https://api.smsinbd.com/sms-api/sendsms';
            $post_values = array(
                'api_token' => config('sms.api_key'),
                'type' => 'text',
                'senderid' => config('sms.sender_id'),
                'contact_number' => $number,
                'message' => $message,
                'method' => 'api'
            );
            $post_string = "";
            foreach ($post_values as $key => $value) {
                $post_string .= "$key=" . urlencode($value) . "&";
            }
            $post_string = rtrim($post_string, "& ");

            $requests = curl_init($post_url);
            curl_setopt($requests, CURLOPT_HEADER, 0);
            curl_setopt($requests, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($requests, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($requests, CURLOPT_SSL_VERIFYPEER, FALSE);
            $post_response = curl_exec($requests);
            curl_close($requests);
        }
        return response()->json([
            'emailOrMobile' => $request->emailOrMobile,
            'type' => $regMedium,
            'username' => $username
        ], 200);


    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'emailOrMobile' => 'required',
            'username' => 'required',
            'type' => 'required',
            'verificationToken' => 'required|numeric|digits:6',
        ]);

        if ($request->type === 'email') {
            $user = User::where('email', $request->emailOrMobile)->where('username', $request->username)
                ->where('is_verified', 0)->first();
        } else {
            $user = User::where('mobile', $request->emailOrMobile)->where('username', $request->username)
                ->where('is_verified', 0)->first();
        }

        if ($user) {
            if ($user->verificationToken == $request->verificationToken) {
                $user->is_verified = 1;
                $user->save();

                if (!$token = Auth::guard('users')->fromUser($user)) {
                    return response()->json(['errors' => 'The login detail is incorrect', 'message' => 'Unauthorized'], 404);
                }
                return $this->respondWithToken($token);

            } else {
                return response()->json([
                    "message" => "The given data was invalid.",
                    "errors" => ["verificationToken" => ["The verification token not match."]]
                ], 422);
            }
        } else {
            return response()->json([
                'entity' => 'users',
                'action' => 'verify',
                'message' => 'not found'
            ], 404);
        }
    }

    public function verifyTokenSend(Request $request)
    {
        $this->validate($request, [
            'emailOrMobile' => 'required',
            'username' => 'required',
            'type' => 'required',
        ]);

        if ($request->type === 'email') {
            $user = User::where('email', $request->emailOrMobile)->where('username', $request->username)
                ->where('is_verified', 0)->first();
        } else {
            $user = User::where('mobile', $request->emailOrMobile)->where('username', $request->username)
                ->where('is_verified', 0)->first();
        }

        if ($user) {
            if ($request->type === 'email') {
                $verification_code = CommonHelper::generateOTP(6);
                $user->verificationToken = $verification_code;
                $user->save();
                $subject = "Please verify your email address.";
                $name = $user->first_name . ' ' . $user->last_name;
                $email = $user->email;
                Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
                    function ($mail) use ($email, $name, $subject) {
                        $mail->from(env('FROM_EMAIL_ADDRESS'), "Tizaara.com");
                        $mail->to($email, $name);
                        $mail->subject($subject);
                    });
            }

            if ($request->type === 'phone') {
                $verification_code = CommonHelper::generateOTP(6);
                $user->verificationToken = $verification_code;
                $user->save();
                $message = 'Please enter the following code ' . $verification_code . ' to verify your account.';
                $post_url = 'https://api.smsinbd.com/sms-api/sendsms';
                $post_values = array(
                    'api_token' => config('sms.api_key'),
                    'type' => 'text',
                    'senderid' => config('sms.sender_id'),
                    'contact_number' => $user->mobile,
                    'message' => $message,
                    'method' => 'api'
                );
                $post_string = "";
                foreach ($post_values as $key => $value) {
                    $post_string .= "$key=" . urlencode($value) . "&";
                }
                $post_string = rtrim($post_string, "& ");

                $requests = curl_init($post_url);
                curl_setopt($requests, CURLOPT_HEADER, 0);
                curl_setopt($requests, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($requests, CURLOPT_POSTFIELDS, $post_string);
                curl_setopt($requests, CURLOPT_SSL_VERIFYPEER, FALSE);
                $post_response = curl_exec($requests);
                curl_close($requests);

            }
        } else {
            return response()->json([
                'entity' => 'users',
                'action' => 'verify',
                'message' => 'not found'
            ], 404);
        }
    }

    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'emailOrMobile' => 'required|string',
            'password' => 'required|string',
        ]);

        $regMedium = filter_var($request->emailOrMobile, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $this->validateLogin($request, $regMedium);

        $email = $regMedium == 'email' ? $request->emailOrMobile : null;
        $phone = $regMedium == 'email' ? null : $request->emailOrMobile;

        if ($email) {
            $credentials = [
                "email" => $email,
                "password" => $request->password,
                "is_verified" => 1,
                "status" => User::STATUS_APPROVED,
            ];
        } else {
            $credentials = [
                "mobile" => $phone,
                "password" => $request->password,
                "is_verified" => 1,
                "status" => User::STATUS_APPROVED,
            ];
        }

        if (!$token = Auth::guard('users')->attempt($credentials)) {
            return response()->json(['errors' => 'The login detail is incorrect', 'message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function loginByGoogle(Request $request)
    {

        $input = $request->input('token');
        $client = new Google_Client(['client_id' => '541835342636-cpkgm0vn65eev3p1b3o3hngibfsd09ul.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($input);
        if ($payload) {
            $user = User::where('email', $payload['email'])->first();
            $username = $this->username($payload['name']);
            if (!$user) {
                $user = new User();
                $user->first_name = $payload['given_name'];
                $user->last_name = $payload['family_name'];
                $user->username = $username;
                $user->password = Hash::make($payload['sub']);
                $user->email = $payload['email'];
                $user->is_verified = 1;
                $user->registration_type = 2;
                $user->photo_type = 1;
                $user->photo = $payload['picture'];
                $user->save();

                if ($user) {
                    $setting = GuestUserSetting::all()->first();
                    $user->guestUserSetting()->associate($setting);
                    $user->save();
                }
            }

            if (!$token = Auth::guard('users')->fromUser($user)) {
                return response()->json(['error' => 'The login detail is incorrect', 'message' => 'Unauthorized'], 404);
            }
            return $this->respondWithToken($token);
        } else {
            return response()->json(['error' => 'Google token is already expired.'], 401);
        }

    }

    public function loginByFacebook(Request $request)
    {
        $input = $request->input('token');
        $url = 'https://graph.facebook.com/v8.0/me?access_token=' . $input;
        $client = new Client();
        $res = $client->get($url);

        if ($res->getStatusCode() === 200) {
            $facebook = json_decode($res->getBody(), true);
            $id = $facebook['id'];
            $first_name = strtok($facebook['name'], " ");
            $second_name = str_replace($first_name, '', $facebook['name']);
            $user = User::where('facebook_id', $id)->first();
            $username = $this->username($facebook['name']);
            if (!$user) {
                $user = new User();
                $user->first_name = $first_name;
                $user->last_name = $second_name;
                $user->username = $username;
                $user->facebook_id = $id;
                $user->password = Hash::make($id);
                $user->registration_type = 2;
                $user->save();

                if ($user) {
                    $setting = GuestUserSetting::all()->first();
                    $user->guestUserSetting()->associate($setting);
                    $user->save();
                }
            }

            if (!$token = Auth::guard('users')->fromUser($user)) {
                return response()->json(['error' => 'The login detail is incorrect', 'message' => 'Unauthorized'], 404);
            }
            return $this->respondWithToken($token);
        } else {
            return response()->json(['error' => 'Facebook token is already expired.'], 401);
        }
    }

    public function profile()
    {
        $user = auth::guard('users')->user();
        $user = $user->toArray();
        $user['allowed_products'] = auth::guard('users')->user()->getAllowedProducts();
        $user['allowed_keywords'] = auth::guard('users')->user()->getAllowedKeywords();
        return response()->json(['user' => $user]);
    }

    public function logout()
    {
        auth('users')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function verifyRequest(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->account_type == NULL) {
            $user->account_type = $request->account_type;
        }

        if ($user->email == NULL) {
            $user->email = $request->email;
        }

        if ($user->mobile == NULL) {
            $user->mobile = $request->mobile;
        }

        $user->status = 1;

        $user->save();
    }

    public function search(Request $request)
    {
        $phone = $request->input('phone');
        $email = $request->input('email');
        if ($phone != null) {
            $user = User::where('mobile', $phone)->first();
            if ($user) {
                return 1;
            } else {
                return 2;
            }
        }

        if ($email != null) {
            $user = User::where('email', $email)->first();
            if ($user) {
                return 1;
            } else {
                return 2;
            }
        }

        return 0;
    }

    public function supplierSearch(Request $request)
    {
        $search = $request->input('searchSupplier');
        if ($search != null) {
            return User::select('id', 'first_name', 'last_name', 'mobile')
                ->where('first_name', 'like', '%' . $search . '%')
                ->orwhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%')->get();
        } else {
            return [];
        }
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('users')->factory()->getTTL()
        ], 200);
    }

    private function validateUsername($request, $regMedium)
    {
        if ($regMedium == 'email') {
            $user = User::where('email', $request->emailOrMobile)->where('is_verified', 0)->first();
            if ($user) {
                $user->delete();
            }
            return Validator::make($request->all(), [
                'emailOrMobile' => 'email|unique:users,email',
            ], [
                'emailOrMobile.email' => 'Invalid email',
                'emailOrMobile.unique' => 'This email is already taken'
            ])->validate();
        } else {
            return Validator::make($request->all(), [
                'emailOrMobile' => 'numeric|digits:11|unique:users,mobile',
            ], [
                'emailOrMobile.numeric' => 'Invalid email or Phone Number',
                'emailOrMobile.unique' => 'The phone number is already taken',
                'emailOrMobile.digits' => 'The phone number must be 11 digits'
            ])->validate();
        }
    }

    private function validateLogin($request, $regMedium)
    {
        if ($regMedium == 'email') {
            $user = User::where('email', $request->emailOrMobile)->where('is_verified', 0)->first();
            if ($user) {
                $user->delete();
            }
            return Validator::make($request->all(), [
                'emailOrMobile' => 'email',
            ], [
                'emailOrMobile.email' => 'Invalid email',
            ])->validate();
        } else {
            return Validator::make($request->all(), [
                'emailOrMobile' => 'numeric|digits:11',
            ], [
                'emailOrMobile.numeric' => 'Invalid email or Phone Number',
                'emailOrMobile.digits' => 'The phone number must be 11 digits'
            ])->validate();
        }
    }
}
