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

    public function index(Request $request)
    {
        if (auth()->user()->type == 'admin') {
            return Activity::get();
        } else {
            $query = Activity::query();
            $query->where('user_id', auth()->user()->id);

            return $query->paginate($request->perpage ?? 25);
        }
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string',
            'category_id' => 'numeric',
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

    public function show($id, Request $request)
    {
        if (auth()->user()->type == 'admin') {
            return Activity::find($id);
        } else {

            $activity = Activity::with(['Category'])
                ->where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->first();

            if (!$activity) {
                return response()->json(["error" => "Activity not found"], 404);
            }

            return $activity;
        }
    }

    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(["error" => "Activity not found"], 404);
        }

        $activity->delete();

        return response()->json([true], 204);
    }

    public function update($id, Request $request)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(["error" => "Activity not found"], 404);
        }

        $activity->update($request->only(['title', 'category_id', 'expire_date', 'finished', 'description']));

        return response()->json(['activity' => $activity], 200);
    }
}
