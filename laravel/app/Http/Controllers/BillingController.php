<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class BillingController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    public function checkBilling(){
        $t=date('d-m-Y');
        $t = date("D",strtotime($t));

        $companiesP = DB::table('companies')
            ->where('companies.id_company', session('company')->id)
            ->whereDay("companies.created_at",'=',$t)
            ->orderBy('created_at', 'DESC')->get();
            echo json_encode($companiesP);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $datos["billings"] = DB::table('billings')
                ->join('companies', 'companies.id', '=', 'billings.id_company')
                ->where('billings.id_company', session('company')->id)
                ->get();
        return view('Billing.PendingInvoices', $datos);
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
   
    public function update(Request $request) {
        
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
}
