<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterFertilizerController extends Controller
{
    public function index()
    {
        return response()->json(Fertilizer::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fertilizers,name',
            'description' => 'required',
            'npk_ratio' => 'required|string',
            'category' => 'required|string',
            'image' => 'required|image|max:2048',
            'application_guide' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('fertilizers', 'public');

        $lastId = Fertilizer::max('id') + 1;
        $fertilizerId = 'fart-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        $fertilizer = Fertilizer::create([
            'name' => $request->name,
            'description' => $request->description,
            'npk_ratio' => $request->npk_ratio,
            'category' => $request->category,
            'image_url' => Storage::url($path),
            'application_guide' => $request->application_guide,
        ]);

        return response()->json($fertilizer, 201);
    }

    public function show(string $id)
    {
            $fertilizer = Fertilizer::find($id);

            if (!$fertilizer) {
                return response()->json(['message' => 'Fertilizer not found.'], 404);
            }

            return response()->json($fertilizer, 200);

    }

    public function update(Request $request, string $id)
    {
        try {
            $fertilizer = Fertilizer::find($id);
            $request->validate([
                'name' => 'sometimes|unique:fertilizers,name,' . $fertilizer->id,
                'description' => 'sometimes|string',
                'npk_ratio' => 'sometimes|string',
                'category' => 'sometimes|string',
                'image' => 'sometimes|image|max:2048',
                'application_guide' => 'nullable|string',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('fertilizers', 'public');
                $fertilizer->image_url = Storage::url($path);
            }

            $fertilizer->update($request->except('image'));

            return response()->json($fertilizer);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(['message'=>'fertilizers not found'],404);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }

    public function destroy(string $id)
    {
        try {
            $fertilizer = Fertilizer::find($id);
            $fertilizer->delete();
            return response()->json(['message' => 'Fertilizer deleted.']);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(['message'=>'Fertilizer not found'],404);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }
}
