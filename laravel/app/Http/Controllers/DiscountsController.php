<?php

namespace App\Http\Controllers;

use App\Discounts;
use App\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DiscountsController extends Controller {

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
        $datos = array();
         $docs = Documents::where("documents.id_company", "=",session('company')->id)->
        where("answer_mh", "=", "aceptado")->get();
            $datos["sales"] = 0;
            $datos["purchases"] = 0;
            foreach ($docs as $doc) {
                if (substr($doc["consecutive"], 8, 2) == '03') {
                    $datos["sales"] = $datos["sales"] - ($doc["total_invoice"] - $doc["total_tax"]);
                } else {
                    $datos["sales"] = $datos["sales"] + ($doc["total_invoice"] - $doc["total_tax"]);
                }
            }
            session(['sales' => $datos["sales"], 'purchases' => $datos["purchases"]]);
        
        $datos ['discounts'] = Discounts::where('id_company', session('company')->id)->get();
        return view('company.discount', $datos);
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
        $d = $request->except('_token');
        $d["id_company"] = session('company')->id;
// Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $discount = Discounts::create($d);
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::to('/form')
                            ->withErrors($e->getErrors())
                            ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return Redirect::back()->with('message', 'Descuento ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discounts  $discounts
     * @return \Illuminate\Http\Response
     */
    public function show(Discounts $discounts) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discounts  $discounts
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
       return Discounts::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discounts  $discounts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $d = $request->except("_token", "_method");
        Discounts::where('id', '=', $id)->update($d);
        return Redirect::back()->with(['message'=> 'Descuento actualizado con exito!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discounts  $discounts
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Discounts::destroy($id);
        return Redirect::back()->with('message', 'Descuento eliminado correctamente');
    }

}
