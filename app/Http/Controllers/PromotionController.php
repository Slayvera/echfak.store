<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Promotion::select('id', 'title', 'description', 'image')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'required|image',
        ]);

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('promotion/image', $request->image, $imageName);

        Promotion::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Promotion added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        return response()->json([
            'promotion' => $promotion,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
        ]);

        $promotion->fill($request->only(['title', 'description']))->save();

        if ($request->hasFile('image')) {
            if ($promotion->image) {
                $exist = Storage::disk('public')->exists("promotion/image/{$promotion->image}");
                if ($exist) {
                    Storage::disk('public')->delete("promotion/image/{$promotion->image}");
                }
            }

            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('promotion/image', $request->image, $imageName);
            $promotion->image = $imageName;
            $promotion->save();
        }

        return response()->json([
            'message' => 'Promotion updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        if ($promotion->image) {
            $exist = Storage::disk('public')->exists("promotion/image/{$promotion->image}");
            if ($exist) {
                Storage::disk('public')->delete("promotion/image/{$promotion->image}");
            }
        }

        $promotion->delete();

        return response()->json([
            'message' => 'Promotion deleted successfully',
        ]);
    }
}
