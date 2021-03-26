<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('user/index');
    }

    public function create()
    {
        return view('user/create');
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        return response()->json([
            'success' => $user->save()
        ]);
    }
    public function show($id)
    {
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        return response()->json([
            'success' => $user->save()
        ]);
    }

    public function destroy($id)
    {
        $delete = User::find($id)->delete();
        return response()->json(['success' => $delete]);
    }

    public function user_datatables()
    {
        return DataTables::of(
            User::where('id', "!=", Auth::user()->id)->orderBy("created_at", "DESC")
        )->make(true);
    }
}
