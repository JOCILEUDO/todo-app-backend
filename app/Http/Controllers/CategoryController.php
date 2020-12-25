<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string',
            'color' => 'string',
            'icon' => 'string'
        ]);

        try {
            $data = [
                'user_id' => Auth::user()->id,
                'title' => $request->input('title'),
                'color' => $request->has('color') ? $request->input('color') : '#F2E0B7',
                'icon' => $request->has('icon') ? $request->input('icon') : 'tag',
            ];

            $category = Category::create($data);

            return response()->json(['category' => $category], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['error' => 'Não foi possível criar a categoria'], 500);
        }
    }
}
