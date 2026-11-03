<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return ('monedas pages');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Moneda $moneda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Moneda $moneda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Moneda $moneda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Moneda $moneda)
    {
        //
    }
}
