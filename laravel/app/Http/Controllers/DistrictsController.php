<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Districts;

class DistrictsController extends Controller {

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
     * @param  \App\districts  $districts
     * @return \Illuminate\Http\Response
     */
    public function show(districts $districts) {
        //
    }

    public function byCanton($id) {
        return Districts::where('id_canton', "=", $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\districts  $districts
     * @return \Illuminate\Http\Response
     */
    public function edit(districts $districts) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\districts  $districts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, districts $districts) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\districts  $districts
     * @return \Illuminate\Http\Response
     */
    public function destroy(districts $districts) {
        //
    }

}
