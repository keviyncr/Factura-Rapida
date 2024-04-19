<?php

namespace App\Http\Controllers;

use App\Payment_methods;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller {

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
     * @param  \App\Payment_methods  $payment_methods
     * @return \Illuminate\Http\Response
     */
    public function show(Payment_methods $payment_methods) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment_methods  $payment_methods
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment_methods $payment_methods) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment_methods  $payment_methods
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment_methods $payment_methods) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment_methods  $payment_methods
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment_methods $payment_methods) {
        //
    }

}
