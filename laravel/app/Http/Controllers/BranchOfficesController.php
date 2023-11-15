<?php

namespace App\Http\Controllers;

use App\BranchOffices;
use App\Consecutives;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BranchOfficesController extends Controller {
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $bo = $request->all("number", "name_branch_office", "id_province", "id_canton", "id_district", "other_signs", "id_country_code", "phone", "emails");
        $bo["id_company"] = session('company')->id;
// Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $bo = BranchOffices::create($bo);
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::back()->with(['message'=> 'Sucursal ingrasada con exito!!','tab'=>'profile-branchOffice']);
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with(['message'=> 'Sucursal ingrasada con exito!!','tab'=>'profile-branchOffice']);
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
        return Redirect::back()->with(['message'=> 'Sucursal ingrasada con exito!!','tab'=>'profile-branchOffice']);
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
     * Display the specified resource.
     *
     * @param  \App\BranchOffices  $branch_Offices
     * @return \Illuminate\Http\Response
     */
    public function show(BranchOffices $branch_Offices) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BranchOffices  $branch_Offices
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return BranchOffices::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BranchOffices  $branch_Offices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $bo = $request->except("_token", "_method");
        $id = $request->all("id");
        BranchOffices::where('id', '=', $id)->update($bo);
        return Redirect::back()->with(['message'=> 'Sucursal ingrasada con exito!!','tab'=>'profile-branchOffice']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BranchOffices  $branch_Offices
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        BranchOffices::destroy($id);
        return Redirect::back()->with(['message'=> 'Sucursal eliminada correctamente','tab'=>'profile-branchOffice']);

    }

}
