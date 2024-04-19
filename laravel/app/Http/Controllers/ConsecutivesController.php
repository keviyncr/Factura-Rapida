<?php

namespace App\Http\Controllers;

use App\Consecutives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConsecutivesController extends Controller {

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
     * @param  \App\consecutives  $consecutives
     * @return \Illuminate\Http\Response
     */
    public function show(consecutives $consecutives) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\consecutives  $consecutives
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Consecutives::where('consecutives.id_branch_offices', $id)->first();
    }
   
    public function update(Request $request, $id) {
        $c = $request->except("_token", "_method");
        Consecutives::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Consecutivos actualizados con exito!!','tab'=>'profile-branchOffice']);
    }

    public function more() {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\consecutives  $consecutives
     * @return \Illuminate\Http\Response
     */
    public function destroy(consecutives $consecutives) {
        //
    }

}
