<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function profile(){
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }
}
