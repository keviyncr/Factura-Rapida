<?php

namespace App\Http\Controllers;

use App\terminals;
use Illuminate\Http\Request;

class TerminalsController extends Controller {

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
     * @param  \App\terminals  $terminals
     * @return \Illuminate\Http\Response
     */
    public function show(terminals $terminals) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\terminals  $terminals
     * @return \Illuminate\Http\Response
     */
    public function edit(terminals $terminals) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\terminals  $terminals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, terminals $terminals) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\terminals  $terminals
     * @return \Illuminate\Http\Response
     */
    public function destroy(terminals $terminals) {
        //
    }

}
