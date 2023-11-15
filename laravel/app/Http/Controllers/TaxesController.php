<?php

namespace App\Http\Controllers;

use App\Taxes;
use App\RateCode;
use App\TaxesCode;
use App\Exonerations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TaxesController extends Controller {

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
        $datos ['taxes'] = Taxes::where('taxes.id_company', session('company')->id)
                        ->join('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                        ->select('taxes.*', 'taxes_codes.description as taxes_code_description')->get();

        $datos ['rate_codes'] = RateCode::all();
        $datos ['taxes_codes'] = TaxesCode::all();
        $datos ['exonerations'] = Exonerations::all();
        return view('company.taxes', $datos);
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
        echo $d["rateIVA"];
        if ($d["id_exoneration"] == "0") {
            $d["id_exoneration"] = null;
        }
        if ($d["id_rate_code"] == "0") {
            $d["id_rate_code"] = null;
        }
        $d["id_company"] = session('company')->id;
// Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $discount = \App\Taxes::create($d);
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
        return Redirect::back()->with('message', 'Impuesto ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function show(Taxes $taxes) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Taxes::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $d = $request->except("_token", "_method");
        if ($d["id_exoneration"] == "0") {
            $d["id_exoneration"] = null;
        }
        if ($d["id_rate_code"] == "0") {
            $d["id_rate_code"] = null;
        }
        Taxes::where('id', '=', $id)->update($d);
        return Redirect::back()->with(['message' => 'Impuesto actualizado con exito!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Taxes::destroy($id);
        return Redirect::back()->with('message', 'Impuesto eliminado correctamente');
    }

}
