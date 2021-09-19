<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
// use App\Http\Controllers\Controller;

class VerifyEmailController extends Controller
{

    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        // if ($user->hasVerifiedEmail()) {
        //     return redirect('/api/email/verify/already-success');
        // }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect('/api/email/verify/success');
    }

    public function showPage(Request $request, $msg)
    {
        return response()->json(['message' => 'Verification Success'], 200);
    }
}
