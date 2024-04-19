<?php

namespace App\Http\Controllers;

use App\Clients;
use App\Documents;
use App\TypeIdCards;
use App\Provinces;
use App\CountryCodes;
use App\SaleConditions;
use App\PaymentMethods;
use App\Currencies;
use App\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ClientsController extends Controller {
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
            $exps = Expenses::where("expenses.id_company", "=", session('company')->id)->
             where("condition", "=", "aceptado")->get();
             $datos["purchases"] = 0;
            foreach ($exps as $exp) {
                $datos["purchases"] = $datos["purchases"] + ($exp["total_invoice"] - $exp["total_tax"]);
            }
            session(['sales' => $datos["sales"], 'purchases' => $datos["purchases"]]);
        
        
        $datos ['clients'] = Clients::where('id_company', session('company')->id)
                        ->join('type_id_cards', 'type_id_cards.id', '=', 'clients.type_id_card')
                        ->select('clients.*', 'type_id_cards.type as type_idCard')
                        ->where("active", "1")->get();
        $datos ['type_id_cards'] = TypeIdCards::all();
        $datos ['provinces'] = Provinces::all();
        $datos ['country_codes'] = CountryCodes::all();
        $datos ['sale_conditions'] = SaleConditions::all();
        $datos ['payment_methods'] = PaymentMethods::all();
        $datos ['currencies'] = Currencies::all();
        return view('company.client', $datos);
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
            $bo = Clients::create($c);
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
        return Redirect::back()->with('message', 'Cliente ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function show(Clients $clients) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Clients::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $c = $request->except("_token", "_method");
        $id = $request->all("id");
        Clients::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Cliente actualizado con exito!!','tab'=>'profile-branchOffice']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $c["active"] = '0';
        Clients::where('id', '=', $id)->update($c);
        return Redirect::back()->with(['message'=> 'Cliente eliminado con exito!!','tab'=>'profile-branchOffice']);
    }
}
