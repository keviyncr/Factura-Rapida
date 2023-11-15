<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Session;
use App\Documents;
use App\Expenses;
use Illuminate\Support\Facades\DB;
use App\ExchangeRate\Indicador;


// EJEMPLO DE UTILIZACION

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request) {
        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = \Session::getId();
        Auth::user()->save();
        $this->clearLoginAttempts($request);
        if(auth()->user()->roll == "SuperUser" ){
                $datos ['companies'] = DB::table('companies')
                    ->join('users_companies', 'companies.id', '=', 'users_companies.id_company')
                    ->select('companies.*', 'users_companies.roll')
                    ->get(); 
            }else if(auth()->user()->roll == "SuperGlovers"){
                $datos ['companies'] = DB::table('companies')
                    ->join('users_companies', 'companies.id', '=', 'users_companies.id_company')
                    ->select('companies.*', 'users_companies.roll')
                    ->where('users_companies.active', '1')
                    ->where('companies.plan', '1')
                    ->get(); 
            }else{
                $datos ['companies'] = DB::table('companies')
                    ->join('users_companies', 'companies.id', '=', 'users_companies.id_company')
                    ->select('companies.*', 'users_companies.roll')
                    ->where('users_companies.active', '1')
                    ->where('users_companies.id_user', auth()->user()->id)
                    ->get(); 
            }
            session(['companies' => $datos ['companies']]);
        
        if (count($datos ['companies']) == 0) {
            $datos ['type_id_cards'] =  \App\TypeIdCards::all();
            $datos ['provinces'] = \App\Provinces::all();
            $datos ['country_codes'] = \App\CountryCodes::all();
            $datos ['economic_activities'] = \App\EconomicActivities::all();
        } else {
            // Constructor recibe como parametro true si se va a usar SOAP, de lo contrario por defecto es false
            $i = new Indicador(false);

            // Metodo recibe el tipo de cambio Indicador::VENTA o Indicador::COMPRA
            $sale = $i->obtenerIndicadorEconomico("317","");
            $purchase = $i->obtenerIndicadorEconomico("318","");
            session(['sale' => $sale, 'purchase' => $purchase]);

            
            if (session('company') == null) {
                session(['company' => $datos ['companies'][0]]);
            }
            $datos["company"] = session('company');
            
             $docs = Documents::where("documents.id_company", "=", $datos["company"]->id)->
             where("answer_mh", "=", "aceptado")->get();
             $exps = Expenses::where("expenses.id_company", "=", $datos["company"]->id)->
             where("condition", "=", "aceptado")->get();
            $datos["sales"] = 0;
            foreach ($docs as $doc) {
                if (substr($doc["consecutive"], 8, 2) == '03') {
                    $datos["sales"] = $datos["sales"] - ($doc["total_invoice"] - $doc["total_tax"]);
                } else {
                    $datos["sales"] = $datos["sales"] + ($doc["total_invoice"] - $doc["total_tax"]);
                }
            }
            
            $exps = Expenses::where("expenses.id_company", "=", $datos["company"]->id)->
             where("condition", "=", "aceptado")->get();
             $datos["purchases"] = 0;
            foreach ($exps as $exp) {
                $datos["purchases"] = $datos["purchases"] + ($exp["total_invoice"] - $exp["total_tax"]);
            }
            session(['sales' => $datos["sales"], 'purchases' => $datos["purchases"]]);
        }

        return $this->authenticated($request, $this->guard()->user()) ?: redirect()->intended($this->redirectPath());
    }

}
