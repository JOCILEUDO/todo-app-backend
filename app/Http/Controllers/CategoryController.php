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

    public function index(Request $request)
    {
        if (auth()->user()->type == 'admin') {
            $per_page = $request->has('per_page') ? intval($request->per_page) : 20;
            $category = Category::paginate($per_page);
            return $category;
        } else {
            $per_page = $request->has('per_page') ? intval($request->per_page) : 20;
            $category = Category::where('user_id', auth()->user()->id)->paginate($per_page);
            return $category;
        }
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

    public function show($id)
    {
        if (auth()->user()->type == 'admin') {
            $category = Category::find($id);
            return $category;
        } else {

            $category = Category::withCount('Activities')->where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->first();

            if (!$category) {
                return response()->json(['error' => 'Categoria não encontrada'], 404);
            }

            return $category;
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'not_found', 'message' => 'Category not found'], 404);
        }

        if ($category->user_id != Auth::user()->id) {
            return response()->json(['error' => 'permission_denied', 'message' => 'Category not found'], 404);
        }

        // atualizacoes
        if ($request->has('title')) {
            $category->title = $request->input('title');
        }
        if ($request->has('color')) {
            $category->color = $request->input('color');
        }
        if ($request->has('icon')) {
            $category->icon = $request->input('icon');
        }

        // save
        $category->save();

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 500);
        }


        $category->delete();
        return response()->json(true, 204);
    }
}
