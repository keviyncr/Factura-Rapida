<?php

namespace App\Http\Controllers;

use App\type_id_cards;
use Illuminate\Http\Request;

class TypeIdCardsController extends Controller {

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
     * @param  \App\type_id_cards  $type_id_cards
     * @return \Illuminate\Http\Response
     */
    public function show(type_id_cards $type_id_cards) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\type_id_cards  $type_id_cards
     * @return \Illuminate\Http\Response
     */
    public function edit(type_id_cards $type_id_cards) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\type_id_cards  $type_id_cards
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, type_id_cards $type_id_cards) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\type_id_cards  $type_id_cards
     * @return \Illuminate\Http\Response
     */
    public function destroy(type_id_cards $type_id_cards) {
        //
    }

}
