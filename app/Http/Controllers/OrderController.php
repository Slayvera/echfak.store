<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('items.product')->get();
        return response()->json($orders);
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
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'total_price' => 'required|numeric',
            'cart' => 'required|array',
        ]);

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'total_price' => $validated['total_price'],
        ]);

        foreach ($validated['cart'] as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'product_title' => $item['product_title'],
            ]);
        }

        return response()->json(['message' => 'Order placed successfully!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $orders = Order::with('items.product')->findOrFail($order);
        return response()->json($orders);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
