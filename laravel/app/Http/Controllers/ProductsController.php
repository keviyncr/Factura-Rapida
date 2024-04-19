<?php

namespace App\Http\Controllers;

use App\Products;
use App\Skus;
use App\Discounts;
use App\Taxes;
use App\Cabys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller {

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
        $datos ['skuses'] = Skus::all();
        $datos ['cabys'] = Cabys::all();
        $datos ['discounts'] = Discounts::where('discounts.id_company', session('company')->id)->get();
        $datos ['taxes'] = Taxes::where('taxes.id_company', session('company')->id)->get();
        $datos ['products'] = Products::join('skuses', 'skuses.id', '=', 'products.id_sku')
                ->select('products.*', 'skuses.symbol as sku_symbol', 'skuses.description as sku_description')
                ->where('products.id_company', session('company')->id)
                ->get();
        return view('company.product', $datos);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $p = $request->except("_token");
        $p["id_company"] = session('company')->id;
        if (isset($p["ids_taxes"])) {
            $p["ids_taxes"] = json_encode($p["ids_taxes"]);
        } else {
            $p["ids_taxes"] = null;
        }
        if (isset($p["ids_discounts"])) {
            $p["ids_discounts"] = json_encode($p["ids_discounts"]);
        } else {
            $p["ids_discounts"] = null;
        }
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $p["cabys"] = str_pad($p["cabys"],13,"0", STR_PAD_LEFT);
            $p["type_internal_code"] = str_pad($p["type_internal_code"],2,"0", STR_PAD_LEFT);
            if($p["type_internal_code"] == "00" || $p["type_internal_code"] == ""){
                $p["internal_code"] = "0000000000";
            }
            $p = Products::create($p);
        } catch (ValidationException $e) {
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
        return Redirect::back()->with('message', 'Producto ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Products::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $p = $request->except("_token", "_method");
        if (isset($p["ids_taxes"])) {
            $p["ids_taxes"] = json_encode($p["ids_taxes"]);
        } else {
            $p["ids_taxes"] = null;
        }
        if (isset($p["ids_discounts"])) {
            $p["ids_discounts"] = json_encode($p["ids_discounts"]);
        } else {
            $p["ids_discounts"] = null;
        }
        $p["cabys"] = str_pad($p["cabys"],13,"0", STR_PAD_LEFT);
        $p["type_internal_code"] = str_pad($p["type_internal_code"],2,"0", STR_PAD_LEFT);
        if($p["type_internal_code"] == "00" || $p["type_internal_code"] == ""){
            $p["internal_code"] = "0000000000";
        }
        $p["id_company"] = session('company')->id;
        Products::where('id', '=', $id)->update($p);
        return Redirect::back()->with(['message' => 'Producto actualizado con exito!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Products::destroy($id);
        return Redirect::back()->with('message', 'Producto eliminado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Skus  $skus
     * @return \Illuminate\Http\Response
     */
    public function getsku($id) {
        return Products::findOrFail($id);
    }

}
