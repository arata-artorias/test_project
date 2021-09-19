<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
	// public function __invoke(Request $request)
	// {
    //     $request->validate($this->rules(), $this->validationErrorMessages());

    //     // Here we will attempt to reset the user's password. If it is successful we
    //     // will update the password on an actual user model and persist it to the
    //     // database. Otherwise we will parse the error and return the response.
    //     $response = $this->broker()->reset(
    //         $this->credentials($request), function ($user, $password) {
    //             $this->resetPassword($user, $password);
    //         }
    //     );

	// 	return $response == Password::PASSWORD_RESET
	// 		? response()->json(['message' => 'Password Updated', 'status' => true], 201)
	// 		: response()->json(['message' => 'Unable to update Password', 'status' => false], 401);
	// }

    public function __invoke(Request $request){

        $input = $request->only('email','token', 'password');

        $validator = Validator::make($input, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $response = Password::reset($input, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        $message = $response == Password::PASSWORD_RESET ? 'Password reset successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG;
        return response()->json($message);
}
}
