<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Slider::select('id' , 'title', 'description', 'image')->get();
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image',
        ]);

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('slider/image', $request->image, $imageName);

        Slider::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Slider added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return response()->json([
            'slider' => $slider,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                $exist = Storage::disk('public')->exists("slider/image/{$slider->image}");
                if ($exist) {
                    Storage::disk('public')->delete("slider/image/{$slider->image}");
                }
            }

            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('slider/image', $request->image, $imageName);
            $slider->image = $imageName;
            $slider->save();
        }

        return response()->json([
            'message' => 'Slider updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            $exist = Storage::disk('public')->exists("slider/image/{$slider->image}");
            if ($exist) {
                Storage::disk('public')->delete("slider/image/{$slider->image}");
            }
        }

        $slider->delete();

        return response()->json([
            'message' => 'Slider deleted successfully',
        ]);
    }
}
