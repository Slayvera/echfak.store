<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Personal::select('id', 'phone',	'email',	'title_head' , 'logo' , 'description_website' , 'instagram' , 'facebook' , 'tiktok')->get();
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
            'phone' => 'required',
            'email' => 'required',
            'logo' => 'nullable', 
            'title_head' => 'required',
            'description_website' => 'required',
            'instagram' => 'required',
            'facebook' => 'required',
            'tiktok' => 'required',
        ]);
    
        // Handle file upload for 'logo'
        $logoName = Str::random() . '.' . $request->logo->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('personal/logo', $request->logo, $logoName);
    
        // Create the personal record
        Personal::create($request->post() + ['logo' => $logoName]);
    
        return response()->json([
            'message' => 'Item added successfully',
        ]);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function show(Personal $personal)
    {
        return response()->json([
            'personal' => $personal
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function edit(Personal $personal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Personal $personal)
    {
        $request->validate([
            'phone'=>'required',
            'email' => 'required',
            'logo' => 'required|image',
            'title_head' => 'required',
            'description_website' => 'required',
            'instagram' => 'required',
            'facebook' => 'required',
            'tiktok' => 'required',
        ]);

        $personal->fill($request->post())->update();


        if ($request->hasFile('logo')) {
            if ($personal->logo) {
                    $exist = Storage::disk('public')->exists("personal/logo/{$personal->logo}"  );
                    if ($exist) {
                    Storage::disk('public')->delete("personal/logo/{$personal->logo}");
                    }
            }

        $imageName = Str::random() . '.' . $request->logo->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('personal/logo', $request->logo, $imageName);
        $personal->logo = $imageName;
        $personal->save();

        }


        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Personal $personal)
    {
        if ($personal->image) {
            $exist = Storage::disk('public')->exists("personal/image/{$personal->image}");
            if ($exist) {
                Storage::disk('public')->delete("personal/image/{$personal->image}");
            }
        }
        $personal->delete();
        return response()->json([
            'message' => 'Item deleted successfully'
        ]);
    }
}
