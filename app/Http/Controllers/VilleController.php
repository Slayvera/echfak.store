<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Ville::select('id', 'name','price')->get();
       
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
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
    
        // Create a new city record
        Ville::create($request->post());
    
        // Return a JSON response
        return response()->json([
            'message' => 'City added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ville  $ville
     * @return \Illuminate\Http\Response
     */
    public function show(Ville $ville)
    {
        return response()->json([
            'ville' => $ville
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ville  $ville
     * @return \Illuminate\Http\Response
     */
    public function edit(Ville $ville)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ville  $ville
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ville $ville)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
    
        // Fill the model with updated data and update it
        $ville->fill($request->post())->update();
    
        return response()->json([
            'message' => 'Ville updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ville  $ville
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ville $ville)
    {
        // Delete the city record
        $ville->delete();
    
        return response()->json([
            'message' => 'City deleted successfully'
        ]);
    }
}
