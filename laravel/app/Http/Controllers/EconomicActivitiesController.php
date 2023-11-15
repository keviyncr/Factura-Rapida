<?php

namespace App\Http\Controllers;

use App\economic_activities;
use Illuminate\Http\Request;

class EconomicActivitiesController extends Controller {

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
     * @param  \App\economic_activities  $economic_activities
     * @return \Illuminate\Http\Response
     */
    public function show(economic_activities $economic_activities) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\economic_activities  $economic_activities
     * @return \Illuminate\Http\Response
     */
    public function edit(economic_activities $economic_activities) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\economic_activities  $economic_activities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, economic_activities $economic_activities) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\economic_activities  $economic_activities
     * @return \Illuminate\Http\Response
     */
    public function destroy(economic_activities $economic_activities) {
        //
    }

}
