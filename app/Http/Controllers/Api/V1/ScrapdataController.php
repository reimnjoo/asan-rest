<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Scrapdata;
use App\Http\Requests\StoreScrapdataRequest;
use App\Http\Requests\UpdateScrapdataRequest;
use App\Http\Controllers\Controller;

class ScrapdataController extends Controller
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
    public function store(StoreScrapdataRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Scrapdata $scrapdata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scrapdata $scrapdata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrapdataRequest $request, Scrapdata $scrapdata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scrapdata $scrapdata)
    {
        //
    }
}
