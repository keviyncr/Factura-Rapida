<?php

namespace App\Http\Controllers;

use App\reference_references;
use Illuminate\Http\Request;

class ReferenceReferencesController extends Controller {

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
     * @param  \App\reference_references  $reference_references
     * @return \Illuminate\Http\Response
     */
    public function show(reference_references $reference_references) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\reference_references  $reference_references
     * @return \Illuminate\Http\Response
     */
    public function edit(reference_references $reference_references) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\reference_references  $reference_references
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, reference_references $reference_references) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\reference_references  $reference_references
     * @return \Illuminate\Http\Response
     */
    public function destroy(reference_references $reference_references) {
        //
    }

}
