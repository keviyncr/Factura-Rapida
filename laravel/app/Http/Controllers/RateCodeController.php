<?php

namespace App\Http\Controllers;

use App\RateCode;
use Illuminate\Http\Request;

class RateCodeController extends Controller {

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

    public function getall() {
        Rate_Code::all();
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
     * @param  \App\Rate_Code  $rate_Code
     * @return \Illuminate\Http\Response
     */
    public function show(RateCode $rate_Code) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate_Code  $rate_Code
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return RateCode::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate_Code  $rate_Code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RateCode $rate_Code) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate_Code  $rate_Code
     * @return \Illuminate\Http\Response
     */
    public function destroy(RateCode $rate_Code) {
        //
    }

}
