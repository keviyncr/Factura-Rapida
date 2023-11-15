<?php

namespace App\Http\Controllers;

use App\country_codes;
use Illuminate\Http\Request;

class CountryCodesController extends Controller {
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
     * @param  \App\country_codes  $country_codes
     * @return \Illuminate\Http\Response
     */
    public function show(country_codes $country_codes) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\country_codes  $country_codes
     * @return \Illuminate\Http\Response
     */
    public function edit(country_codes $country_codes) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\country_codes  $country_codes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, country_codes $country_codes) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\country_codes  $country_codes
     * @return \Illuminate\Http\Response
     */
    public function destroy(country_codes $country_codes) {
        //
    }

}
