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

    public function index(Request $request)
    {
        $per_page = $request->has('per_page') ? intval($request->per_page) : 20;
        $tags = Tag::paginate($per_page);
        return $tags;
    }

    public function show($id)
    {
        $tag = Tag::find($id);

        if(!$tag){
            return response()->json(['error' => 'not_found', 'message' => 'tag not found'], 404);
        }
        
        return $tag;
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['error' => 'not_found', 'message' => 'tag not found'], 404);
        }

        if ($tag->user_id != Auth::user()->id) {
            return response()->json(['error' => 'permission_denied', 'message' => 'tag not found'], 404);
        }

        // atualizacoes
        if ($request->has('name')) {
            $tag->name = $request->input('name');
        }

        if ($request->has('color')) {
            $tag->color = $request->input('color');
        }

        // save
        $tag->save();
        return $tag;
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['error' => 'tag not found'], 500);
        }

        $tag->delete();
        return response()->json(true, 204);
    }
}
