<?php

namespace App\Http\Controllers;

use App\Skus;
use Illuminate\Http\Request;

class SkusController extends Controller {

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
     * @param  \App\skus  $skus
     * @return \Illuminate\Http\Response
     */
    public function show(skus $skus) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\skus  $skus
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return \App\Skus::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\skus  $skus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, skus $skus) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\skus  $skus
     * @return \Illuminate\Http\Response
     */
    public function destroy(skus $skus) {
        //
    }

}
