<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Banner::select('id','image_one', 'image_two')->get();
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
            'image_one' => 'required|image',
            'image_two' => 'required|image',
        ]);

        $imageOneName = Str::random() . '.' . $request->image_one->getClientOriginalExtension();
        $imageTwoName = Str::random() . '.' . $request->image_two->getClientOriginalExtension();

        Storage::disk('public')->putFileAs('banner/image', $request->image_one, $imageOneName);
        Storage::disk('public')->putFileAs('banner/image', $request->image_two, $imageTwoName);

        Banner::create([
            'image_one' => $imageOneName,
            'image_two' => $imageTwoName,
        ]);

        return response()->json([
            'message' => 'Banner added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return response()->json([
            'banner' => $banner,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image_one' => 'nullable|image',
            'image_two' => 'nullable|image',
        ]);

        if ($request->hasFile('image_one')) {
            if ($banner->image_one) {
                $exist = Storage::disk('public')->exists("banner/image/{$banner->image_one}");
                if ($exist) {
                    Storage::disk('public')->delete("banner/image/{$banner->image_one}");
                }
            }

            $imageOneName = Str::random() . '.' . $request->image_one->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('banner/image', $request->image_one, $imageOneName);
            $banner->image_one = $imageOneName;
            $banner->save();
        }

        if ($request->hasFile('image_two')) {
            if ($banner->image_two) {
                $exist = Storage::disk('public')->exists("banner/image/{$banner->image_two}");
                if ($exist) {
                    Storage::disk('public')->delete("banner/image/{$banner->image_two}");
                }
            }

            $imageTwoName = Str::random() . '.' . $request->image_two->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('banner/image', $request->image_two, $imageTwoName);
            $banner->image_two = $imageTwoName;
            $banner->save();
        }

        return response()->json([
            'message' => 'Banner updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image_one) {
            $exist = Storage::disk('public')->exists("banner/image/{$banner->image_one}");
            if ($exist) {
                Storage::disk('public')->delete("banner/image/{$banner->image_one}");
            }
        }

        if ($banner->image_two) {
            $exist = Storage::disk('public')->exists("banner/image/{$banner->image_two}");
            if ($exist) {
                Storage::disk('public')->delete("banner/image/{$banner->image_two}");
            }
        }

        $banner->delete();

        return response()->json([
            'message' => 'Banner deleted successfully',
        ]);
    }
}
