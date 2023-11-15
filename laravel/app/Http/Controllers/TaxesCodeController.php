<?php

namespace App\Http\Controllers;

use App\TaxesCode;
use Illuminate\Http\Request;

class TaxesCodeController extends Controller {

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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Taxes_Code  $taxes_Code
     * @return \Illuminate\Http\Response
     */
    public function show(TaxesCode $taxes_Code) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Taxes_Code  $taxes_Code
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return TaxesCode::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Taxes_Code  $taxes_Code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaxesCode $taxes_Code) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Taxes_Code  $taxes_Code
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxesCode $taxes_Code) {
        //
    }

}
