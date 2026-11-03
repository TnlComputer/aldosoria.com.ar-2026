<?php

namespace App\Http\Controllers;

use App\Models\Electronic;
use Illuminate\Http\Request;

class ElectronicController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('pages.electronica');
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
  public function show(Electronic $electronic)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Electronic $electronic)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Electronic $electronic)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Electronic $electronic)
  {
    //
  }
}
