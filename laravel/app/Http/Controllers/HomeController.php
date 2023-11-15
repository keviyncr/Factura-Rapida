<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Companies;
use App\TypeIdCards;
use App\BranchOffices;
use App\UsersCompanies;
use App\Consecutives;
use App\Provinces;
use App\Cantons;
use App\Districts;
use App\CountryCodes;
use App\Terminals;
use App\Expenses;
use App\Documents;
use App\EconomicActivities;
use App\CompaniesEconomicActivities;
use Auth;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        $datos = array();
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
        if (count($datos ['companies']) == 0) {
            $datos ['type_id_cards'] = TypeIdCards::all();
            $datos ['provinces'] = Provinces::all();
            $datos ['country_codes'] = CountryCodes::all();
            $datos ['economic_activities'] = EconomicActivities::all();
        } else {   
            session(['companies' => $datos ['companies']]);
            if (session('company') == null) {
                session(['company' => $datos ['companies'][0]]);
            }
            $datos["company"] = session('company');
        }
        if(isset(session('company')->id)){
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
            
            //GASTOS ULTIMOS 12 MESES
            $oldDate = date(date("Y")."-".date("m")."-01");
            $oldDate = date("Y-m-d",strtotime($oldDate."- 12 month")); 
            $expsY = Expenses::where("expenses.id_company", "=", session('company')->id)->
             where("condition", "=", "aceptado")->whereDate('created_at',">=", $oldDate)->get();
             $datos["purchasesY"] = 0;
            foreach ($expsY as $expY) {
                if(date("m")-12 < substr($expY["key"], 5, 2)){
                $datos["purchasesY"] = $datos["purchasesY"] + ($expY["total_invoice"] - $expY["total_tax"]);
                }
            }
            session(['purchasesY' => $datos["purchasesY"],'purchasesM' => $datos["purchasesY"]/12]);
            
            //ventas 12 meses
            $docsY = Documents::where("documents.id_company", "=",session('company')->id)->
            where("answer_mh", "=", "aceptado")->whereDate('created_at',">=", $oldDate)->get();
                $datos["salesY"] = 0;
                foreach ($docsY as $docY) {
                    if (substr($docY["consecutive"], 8, 2) == '03') {
                        $datos["salesY"] = $datos["salesY"] - ($docY["total_invoice"] - $docY["total_tax"]);
                    } else {
                        $datos["salesY"] = $datos["salesY"] + ($docY["total_invoice"] - $docY["total_tax"]);
                    }
                }
                session(['salesY' => $datos["salesY"],'salesM' => $datos["salesY"]/date("m")]);
                 //Mes actual
                $docsM1 = Documents::where("documents.id_company", "=",session('company')->id)->
                        where("answer_mh", "=", "aceptado")->whereMonth('created_at',"=", date('m'))
                        ->whereYear('created_at',"=", date('Y'))->get();
                        
                $datos["salesM1"] = 0;
                foreach ($docsM1 as $docM1) {
                    if (substr($docM1["consecutive"], 8, 2) == '03') {
                        $datos["salesM1"] = $datos["salesM1"] - ($docM1["total_invoice"] - $docM1["total_tax"]);
                    } else {
                        $datos["salesM1"] = $datos["salesM1"] + ($docM1["total_invoice"] - $docM1["total_tax"]);
                    }
                }
                session(['salesM1' => $datos["salesM1"]]);
                //Mes 1
                $docsM2 = Documents::where("documents.id_company", "=",session('company')->id)->
                        where("answer_mh", "=", "aceptado")->whereMonth('created_at',"=", date('m')-1)
                        ->whereYear('created_at',"=", date('Y'))->get();
                        
                $datos["salesM2"] = 0;
                foreach ($docsM2 as $docM2) {
                    if (substr($docM2["consecutive"], 8, 2) == '03') {
                        $datos["salesM2"] = $datos["salesM2"] - ($docM2["total_invoice"] - $docM2["total_tax"]);
                    } else {
                        $datos["salesM2"] = $datos["salesM2"] + ($docM2["total_invoice"] - $docM2["total_tax"]);
                    }
                }
                session(['salesM2' => $datos["salesM2"]]);
                 //Mes 2
                $docsM3 = Documents::where("documents.id_company", "=",session('company')->id)->
                        where("answer_mh", "=", "aceptado")->whereMonth('created_at',"=", date('m')-2)
                        ->whereYear('created_at',"=", date('Y'))->get();
                        
                $datos["salesM3"] = 0;
                foreach ($docsM3 as $docM3) {
                    if (substr($docM3["consecutive"], 8, 2) == '03') {
                        $datos["salesM3"] = $datos["salesM3"] - ($docM3["total_invoice"] - $docM3["total_tax"]);
                    } else {
                        $datos["salesM3"] = $datos["salesM3"] + ($docM3["total_invoice"] - $docM3["total_tax"]);
                    }
                }
                session(['salesM3' => $datos["salesM3"]]);
                
            
        }
        return view('home', $datos);
    }

    public function store(Request $request) {

        $company = $request->all("id_card", "type_id_card", "name_company", "user_mh", "pass_mh", "cryptographic_key", "pin");
        if ($request->hasFile("cryptographic_key")) {
            $company ["cryptographic_key"] = $request->file("cryptographic_key")->storeAs("Keys/" . $company["id_card"], $request->file("cryptographic_key")->getClientOriginalName());
        }
        // Start transaction!
        DB::beginTransaction();

        try {
            // Validate, then create if valid
            $company = Companies::create($company);
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

        $bo = $request->all("name_branch_office", "id_province",
                "id_canton", "id_district", "other_signs", "phone", "emails", 'id_country_code');
        $bo['number'] = 1;
        $bo['id_company'] = $company["id"];
        try {
            // Validate, then create if valid
            $bo = BranchOffices::create($bo);
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

        $UC = array();
        $UC ["id_user"] = auth()->user()->id;
        $UC ["id_company"] = $company["id"];
        $UC ["roll"] = "Administrador";
        $UC ["active"] = "1";
        try {
            // Validate, then create if valid
            $UC = UsersCompanies::create($UC);
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
        $TM = array();
        $TM ["id_branch_office"] = $bo["id"];
        $TM ["id_company"] = $company["id"];
        $TM ["number"] = "1";
        try {
            // Validate, then create if valid
            $TM = Terminals::create($TM);
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
        try {
            $c_ea = array();
            $c_ea["id_company"] = $company["id"];
            $c_ea["id_economic_activity"] = $request->all("economic_activities")["economic_activities"];

            // Validate, then create if valid
            $c_ea = CompaniesEconomicActivities::create($c_ea);
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
        try {
            $consecutives = array();
            $consecutives["id_branch_offices"] = $bo["id"];
            // Validate, then create if valid
            $consecutives = consecutives::create($consecutives);
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
        return redirect('home');
    }

}
