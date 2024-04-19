<?php

namespace App\Http\Controllers;

use App\Providers;
use App\TypeIdCards;
use App\Provinces;
use App\CountryCodes;
use App\SaleConditions;
use App\PaymentMethods;
use App\Currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProvidersController extends Controller {
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
        $datos ['providers'] = Providers::where('id_company', session('company')->id)
                        ->join('type_id_cards', 'type_id_cards.id', '=', 'providers.type_id_card')
                        ->select('providers.*', 'type_id_cards.type as type_idCard')
                        ->where("active", "1")->get();
        $datos ['type_id_cards'] = TypeIdCards::all();
        $datos ['provinces'] = Provinces::all();
        $datos ['country_codes'] = CountryCodes::all();
        $datos ['sale_conditions'] = SaleConditions::all();
        $datos ['payment_methods'] = PaymentMethods::all();
        $datos ['currencies'] = Currencies::all();
        return view('company.provider', $datos);
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
        $c = $request->except('_token');
        $c["id_company"] = session('company')->id;
    // Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $bo = Providers::create($c);
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
        return Redirect::back()->with('message', 'Proveedor ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function show(Providers $providers) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Providers::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $c = $request->except("_token", "_method");
        $id = $request->all("id");
        Providers::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Cliente actualizado con exito!!','tab'=>'profile-branchOffice']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $c["active"] = '0';
        Providers::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Cliente eliminado con exito!!','tab'=>'profile-branchOffice']);
    }
}
