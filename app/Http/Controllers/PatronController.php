<?php

namespace App\Http\Controllers;

use App\Models\Patron;
use Illuminate\Http\Request;

class PatronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Patron::select('email', 'password','age')->get();
       
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
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'age' => 'required|numeric|min:0',
        ]);
    
        // Create a new city record
        Patron::create($request->post());
    
        // Return a JSON response
        return response()->json([
            'message' => 'Admin added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patron  $patron
     * @return \Illuminate\Http\Response
     */
    public function show(Patron $patron)
    {
        return response()->json([
            'ville' => $patron
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patron  $patron
     * @return \Illuminate\Http\Response
     */
    public function edit(Patron $patron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patron  $patron
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patron $patron)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'age' => 'required|numeric|min:0',
        ]);
    
        // Fill the model with updated data and update it
        $patron->fill($request->post())->update();
    
        return response()->json([
            'message' => 'Admin updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patron  $patron
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patron $patron)
    {
        // Delete the city record
        $patron->delete();
    
        return response()->json([
            'message' => 'Patron deleted successfully'
        ]);
    }
}
