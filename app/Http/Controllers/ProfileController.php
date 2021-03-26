<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateAccount(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->save();
        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        $current_password = $request->current_password;
        $user = User::find(Auth::user()->id);
        if (Hash::check($current_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            Auth::login($user);
            return response()->json(['success'=>$user->save()]);
        }
        return response()->json(['success'=>false]);
    }
}
