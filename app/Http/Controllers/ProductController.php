<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;  // Add the Category model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('category:id,title')->select('id', 'title', 'description', 'image', 'size', 'colors', 'price', 'category_id' , 'isValid' , 'discount' , 'stars')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'required|image',
            'size' => 'nullable',
            'colors' => 'nullable',
            'price' => 'required',
            'category_id' => 'required|exists:categories,id',  // Validate category_id
            'isValid' => 'required',
            'discount' => 'required',
            'stars' => 'required',
        ]);

        // Handle image upload
        $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);

        // Create the product, including the category_id
        Product::create($request->post() + ['image' => $imageName]);

        return response()->json([
            'message' => 'Item added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'size' => 'nullable',
            'colors' => 'nullable',
            'price' => 'required',
            'category_id' => 'required|exists:categories,id',  // Validate category_id
            'isValid' => 'required',
            'discount' => 'required',
            'stars' => 'required',
        ]);

        // Update product details (including category_id)
        $product->fill($request->post())->update();

        // Handle image update if a new image is provided
        if ($request->hasFile('image')) {
            if ($product->image) {
                $exist = Storage::disk('public')->exists("product/image/{$product->image}");
                if ($exist) {
                    Storage::disk('public')->delete("product/image/{$product->image}");
                }
            }

            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
            $product->image = $imageName;
            $product->save();
        }

        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            $exist = Storage::disk('public')->exists("product/image/{$product->image}");
            if ($exist) {
                Storage::disk('public')->delete("product/image/{$product->image}");
            }
        }
        $product->delete();
        return response()->json([
            'message' => 'Item deleted successfully'
        ]);
    }
}
