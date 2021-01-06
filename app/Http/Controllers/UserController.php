<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if($request->has('name')){
            $user->name = $request->input('name');
        }
        
        if($request->has('four_key')){
            $user->four_key = $request->input('four_key');
        }
        
        if($request->has('profile_image')){
            $user->profile_image = $request->input('profile_image');
        }
        
        if($request->has('type')){
            $user->type = $request->input('type');
        }
        
        return response()->json(['user' => $user], 200);
    }
}
