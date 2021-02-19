<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

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

    public function index(Request $request)
    {

        if (Auth::user()->type == 'admin') {
            $query = User::query();

            // $this->validate($request, [
            //     'order' => 'in_array:admin,teste,test',
            // ]);


            $query->when($request->has('type'), function ($q) use ($request) {
                return $q->where('type', $request->type);
            });

            $query->when($request->has('q'), function ($q) use ($request) {
                return $q->where("name", "LIKE", "%" . $request->q . "%")
                    ->orWhere("email", "LIKE", "%" . $request->q . "%")
                    ->orWhere("login", "LIKE", "%" . $request->q . "%");
            });

            $query->when(($request->has('orderby') && $request->has('order')), function ($q) use ($request) {
                if (in_array($request->orderby, ['name', 'email', 'login', 'type', 'updated_at', 'created_at'])) {

                    $order = in_array($request->order, ['asc', 'desc']) ? $request->order : "asc";

                    return $q->orderBy($request->orderby, $order);
                }
            });

            return $query->paginate($request->perpage ?? 20);
        } else {
            return response('', 401);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('four_key')) {
            $user->four_key = $request->input('four_key');
        }

        if ($request->has('profile_image')) {
            $user->profile_image = $request->input('profile_image');
        }

        if ($request->has('type')) {
            $user->type = $request->input('type');
        }

        $user->save();
        return response()->json(['user' => $user], 200);
    }
}
