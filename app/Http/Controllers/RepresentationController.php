<?php

namespace App\Http\Controllers;

use App\Models\representation;
use Illuminate\Http\Request;

class RepresentationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('pages.representacion');
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
  public function show(representation $representation)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(representation $representation)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, representation $representation)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(representation $representation)
  {
    //
  }
}
