<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string',
            'category_id' => 'numeric',
            'expire_date' => 'datetime',
            'finished' => 'boolean',
            'description' => 'string'
        ]);

        try {
            $data = [
                'user_id' => Auth::user()->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'category_id' => $request->has('category_id') ? $request->input('category_id') : null,
                'expire_date' => $request->has('expire_date') ? $request->input('expire_date') : null,
                'finished' => $request->has('finished') ? $request->input('finished') : false,
            ];

            $activity = Activity::create($data);

            return response()->json(['activity' => $activity], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['error' => 'Não foi possível criar a categoria'], 500);
        }
    }
}
