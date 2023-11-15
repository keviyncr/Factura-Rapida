<?php

namespace App\Http\Controllers;

use App\type_document_exonerations;
use Illuminate\Http\Request;

class TypeDocumentExonerationsController extends Controller {

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
     * @param  \App\type_document_exonerations  $type_document_exonerations
     * @return \Illuminate\Http\Response
     */
    public function show(type_document_exonerations $type_document_exonerations) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\type_document_exonerations  $type_document_exonerations
     * @return \Illuminate\Http\Response
     */
    public function edit(type_document_exonerations $type_document_exonerations) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\type_document_exonerations  $type_document_exonerations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, type_document_exonerations $type_document_exonerations) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\type_document_exonerations  $type_document_exonerations
     * @return \Illuminate\Http\Response
     */
    public function destroy(type_document_exonerations $type_document_exonerations) {
        //
    }

}
