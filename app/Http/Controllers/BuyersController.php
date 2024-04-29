<?php

namespace App\Http\Controllers;

use App\Models\Buyers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyersRequest;
use App\Http\Requests\UpdateBuyersRequest;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuyersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyers $buyers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buyers $buyers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuyersRequest $request, Buyers $buyers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buyers $buyers)
    {
        //
    }
}
