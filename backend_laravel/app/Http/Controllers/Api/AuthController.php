<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyCollection;
use App\Models\User;
use App\Models\FavouriteCompany;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware("auth:api", ["except" => ["login", "register"]]);
        $this->user = new User;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->toArray()], 400);
        }

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "password" => Hash::make($request->password),
        ];

        $user = $this->user->create($data);
        event(new Registered($user));

        return response()->json(['success' => true, 'message' => "Registration Successful"], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->toArray()], 400);
        }

        $credentials = $request->only(["email", "password"]);
        $user = User::where('email', $credentials['email'])->first();

        if ($user && auth()->attempt($credentials)) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            $responseMessage = "Login Successful";
            return $this->respondWithToken($accessToken, $responseMessage, auth()->user());
        }
        
        $responseMessage = "Invalid username or password";
        return response()->json(["success" => false, "message" => $responseMessage, "error" => $responseMessage], 422);
    }

    // public function forgot_password(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'message' => $validator->messages()->toArray()], 400);
    //     }
            
    //     try {
    //         $response = Password::sendResetLink($request->only('email'), function (Message $message) {
    //             $message->subject($this->getEmailSubject());
    //         });
    //         switch ($response) {
    //             case Password::RESET_LINK_SENT:
    //                 return response()->json(['success' => true, 'message' => trans($response)], 200);
    //             case Password::INVALID_USER:
    //                 return response()->json(['success' => false, 'message' => trans($response)], 400);
    //         }
    //     } catch (\Swift_TransportException $ex) {
    //         $arr = ['success' => false, 'message' => $ex->getMessage()];
    //     } catch (Exception $ex) {
    //         $arr = ['success' => false, 'message' => $ex->getMessage()];
    //     }

    //     return response()->json($arr, 400);
    // }

    // public function change_password(Request $request)
    // {
    //     $input = $request->all();
    //     $userid = Auth::guard('api')->user()->id;
    //     $rules = array(
    //         // 'old_password' => 'required',
    //         'new_password' => 'required|min:6',
    //         'confirm_password' => 'required|same:new_password',
    //     );
    //     $validator = Validator::make($input, $rules);
    //     if ($validator->fails()) {
    //         $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    //     } else {
    //         try {
    //             if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
    //                 $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
    //             } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
    //                 $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
    //             } else {
    //                 User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
    //                 $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
    //             }
    //         } catch (\Exception $ex) {
    //             if (isset($ex->errorInfo[2])) {
    //                 $msg = $ex->errorInfo[2];
    //             } else {
    //                 $msg = $ex->getMessage();
    //             }
    //             $arr = array("status" => 400, "message" => $msg, "data" => array());
    //         }
    //     }
    //     return \Response::json($arr);
    // }
}
