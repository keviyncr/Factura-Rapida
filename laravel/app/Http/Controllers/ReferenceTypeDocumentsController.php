<?php

namespace App\Http\Controllers;

use App\reference_type_documents;
use Illuminate\Http\Request;

class ReferenceTypeDocumentsController extends Controller {

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
     * @param  \App\reference_type_documents  $reference_type_documents
     * @return \Illuminate\Http\Response
     */
    public function show(reference_type_documents $reference_type_documents) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\reference_type_documents  $reference_type_documents
     * @return \Illuminate\Http\Response
     */
    public function edit(reference_type_documents $reference_type_documents) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\reference_type_documents  $reference_type_documents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, reference_type_documents $reference_type_documents) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\reference_type_documents  $reference_type_documents
     * @return \Illuminate\Http\Response
     */
    public function destroy(reference_type_documents $reference_type_documents) {
        //
    }

}
