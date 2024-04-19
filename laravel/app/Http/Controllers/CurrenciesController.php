<?php

namespace App\Http\Controllers;

use App\Currencies;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
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
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currencies  $currencies
     * @return \Illuminate\Http\Response
     */
    public function show(Currencies $currencies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currencies  $currencies
     * @return \Illuminate\Http\Response
     */
    public function edit(Currencies $currencies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currencies  $currencies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currencies $currencies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currencies  $currencies
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currencies $currencies)
    {
        //
    }
}
