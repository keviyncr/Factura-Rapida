<?php

namespace App\Http\Controllers;

use App\Cantons;
use Illuminate\Http\Request;

class CantonsController extends Controller {
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

    public function byProvince($id) {
        return Cantons::where('id_province', "=", $id)->get();
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
     * @param  \App\cantons  $cantons
     * @return \Illuminate\Http\Response
     */
    public function show(cantons $cantons) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cantons  $cantons
     * @return \Illuminate\Http\Response
     */
    public function edit(cantons $cantons) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cantons  $cantons
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cantons $cantons) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cantons  $cantons
     * @return \Illuminate\Http\Response
     */
    public function destroy(cantons $cantons) {
        //
    }

}
