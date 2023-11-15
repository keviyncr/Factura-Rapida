<?php

namespace App\Http\Controllers;

use App\Sale_conditions;
use Illuminate\Http\Request;

class SaleConditionsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sale_conditions  $sale_conditions
     * @return \Illuminate\Http\Response
     */
    public function show(Sale_conditions $sale_conditions) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sale_conditions  $sale_conditions
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale_conditions $sale_conditions) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sale_conditions  $sale_conditions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale_conditions $sale_conditions) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sale_conditions  $sale_conditions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale_conditions $sale_conditions) {
        //
    }

}
