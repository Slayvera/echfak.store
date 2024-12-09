<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Category::select('id', 'title',	'description',	'image')->get();
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
            'title'=>'required',
            'description' => 'nullable',
            'image' => 'required|image',
        ]);

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('category/image', $request->image, $imageName);
        Category::create($request->post()+ ['image'=> $imageName]);
        return response()->json([
            'message'=>'Item added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title'=>'required',
            'description' => 'nullable',
            'image' => 'nullable',
        ]);

        $category->fill($request->post())->update();


        if ($request->hasFile('image')) {
            if ($category->image) {
                    $exist = Storage::disk('public')->exists("category/image/{$category->image}"  );
                    if ($exist) {
                    Storage::disk('public')->delete("category/image/{$category->image}");
                    }
            }

        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('category/image', $request->image, $imageName);
        $category->image = $imageName;
        $category->save();

        }


        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->image) {
            $exist = Storage::disk('public')->exists("category/image/{$category->image}");
            if ($exist) {
                Storage::disk('public')->delete("category/image/{$category->image}");
            }
        }
        $category->delete();
        return response()->json([
            'message' => 'Item deleted successfully'
        ]);
    }
}
