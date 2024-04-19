<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\TypeIdCards;
use App\Provinces;
use App\CountryCodes;
use App\EconomicActivities;
use App\BranchOffices;

class CompaniesController extends Controller {

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
        
        $datos ['companies'] = Companies::all();
        if (count($datos ['companies']) != 0) {
            return view('company.index');
        } else {
            $datos ['type_id_cards'] = TypeIdCards::all();
            $datos ['provinces'] = Provinces::all();
            $datos ['country_codes'] = CountryCodes::all();
            $datos ['economic_activities'] = EconomicActivities::all();
            return view('company.configuration', $datos);
        }
    }

    /**
     * Display a configuration of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuration() {

        $company = Companies::findOrFail(session('company')->id);
        session(['company' => $company]);
        $datos ['type_id_cards'] = TypeIdCards::all();
        $datos ['provinces'] = Provinces::all();
        $datos ['country_codes'] = CountryCodes::all();
        $datos ['economic_activities'] = EconomicActivities::all();
        $datos ['e_as'] = DB::table('companies_economic_activities')
                ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                ->where('companies_economic_activities.id_company', session('company')->id)
                ->get();
        $datos ['branch_offices'] = BranchOffices::where("id_company", "=", session('company')->id)
                        ->join('provinces', 'provinces.id', '=', 'branch_offices.id_province')
                        ->join('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
                        ->join('districts', 'districts.id', '=', 'branch_offices.id_district')
                        ->select('branch_offices.*', 'provinces.province as province', 'cantons.canton as canton', 'districts.district as district')->get();

        $datos ['users'] = DB::table('users')
                ->join('users_companies', 'users.id', '=', 'users_companies.id_user')
                ->select('users.*', 'users_companies.roll')
                ->where('users_companies.id_company', session('company')->id)
                ->get();
        return view('company.configuration', $datos);
    }

    /**
     * Display a configuration of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveImage(Request $request) {
        $company = Companies::findOrFail(session('company')->id);
        Storage::delete($company->logo_url);
        if ($request->hasFile("logo_url")) {
            $datos ["logo_url"] = $request->file("logo_url")->store("logos" . $request->input("logo_url"));
        }
        DB::beginTransaction();

        try {
            // Validate, then create if valid
            $company = Companies::where('id', session('company')->id)->update($datos);
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

        return redirect()->route('configuration');
    }

    /**
     * Display a configuration of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCryptoKey(Request $request) {

        $company = Companies::findOrFail(session('company')->id);
        Storage::delete($company->cryptographic_key);
        if ($request->hasFile("cryptographic_key")) {
            $datos ["cryptographic_key"] = $request->file("cryptographic_key")->storeAs("Keys/" . session('company')->id_card, $request->file("cryptographic_key")->getClientOriginalName());
        }
        DB::beginTransaction();

        try {
            $datos["pin"] = $request->all("pin")["pin"];
            // Validate, then create if valid
            $company = Companies::where('id', session('company')->id)->update($datos);
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

        return redirect()->route('configuration');
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function show(companies $companies) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function adminCompanies() {
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
                ->where('companies.plan', '1')
                ->get(); 
        }
          return view('company.adminCompanies', $datos);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $company = $request->except("_token", "_method");
        Companies::where('id', '=', session('company')->id)->update($company);
        return redirect()->route('configuration');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function destroy(companies $companies) {
        //
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  \App\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function changeStateCompany($id,$state) {
        $data ["active"] = $state;
        Companies::where('id', '=', $id)->update($data);
        return $state;
    }
    
    public function changePlanCompany($plan,$id) {
        $data ["plan"] = $plan;
        Companies::where('id', '=', $id)->update($data);
        return $plan;
    }

    public function change(Request $request) {
        $datos ['companies'] = DB::table('companies')
                ->join('users_companies', 'companies.id', '=', 'users_companies.id_company')
                ->select('companies.*', 'users_companies.roll')
                ->where('companies.id', $request['sl_company'])
                ->get();
        session(['company' => $datos ['companies'][0]]);
        return redirect()->route('home')->with('message', 'Expediente actualizado correctamente');
    }

}
