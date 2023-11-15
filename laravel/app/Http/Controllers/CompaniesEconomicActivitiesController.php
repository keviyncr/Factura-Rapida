<?php

namespace App\Http\Controllers;

use App\CompaniesEconomicActivities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
class CompaniesEconomicActivitiesController extends Controller {
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
        DB::beginTransaction();

        try {
            $datos["id_economic_activity"] = $request->all("economic_activities")["economic_activities"];
            $datos ["id_company"] = session('company')->id;
            // Validate, then create if valid
            $company = CompaniesEconomicActivities::create($datos);
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

        return Redirect::back()->with('message', 'Expediente actualizado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\companies_economic_activities  $companies_economic_activities
     * @return \Illuminate\Http\Response
     */
    public function show(companies_economic_activities $companies_economic_activities) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\companies_economic_activities  $companies_economic_activities
     * @return \Illuminate\Http\Response
     */
    public function edit(companies_economic_activities $companies_economic_activities) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\companies_economic_activities  $companies_economic_activities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, companies_economic_activities $companies_economic_activities) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\companies_economic_activities  $companies_economic_activities
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        CompaniesEconomicActivities::destroy($id);
        
        return Redirect::back()->with('message', 'Expediente actualizado correctamente');
       
    }

}
