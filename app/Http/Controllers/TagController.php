<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'color' => 'string',
        ]);

        try {
            $data = [
                'user_id' => Auth::user()->id,
                'name' => $request->input('name'),
                'color' => $request->has('color') ? $request->input('color') : '#F2E0B7',
            ];

            $tag = Tag::create($data);

            return response()->json(['tag' => $tag], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['error' => 'Não foi possível criar a tag'], 500);
        }
    }
}
