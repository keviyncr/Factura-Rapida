<?php

namespace App\Http\Controllers;

use App\Exonerations;
use App\TypeDocumentExonerations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ExonerationsController extends Controller {

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
        $datos ['exonerations'] = Exonerations::where('id_company', session('company')->id)
                        ->join('type_document_exonerations', 'type_document_exonerations.id', '=', 'exonerations.id_type_document_exoneration')
                        ->select('exonerations.*', 'type_document_exonerations.document as type_doc')->get();
        $datos ['type_document_exonerations'] = TypeDocumentExonerations::all();
       
        return view('company.exoneration', $datos);
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
        $d["id_company"] = session('company')->id;
// Start transaction!
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $discount = Exonerations::create($d);
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
        return Redirect::back()->with('message', 'Exoneracion ingrasada con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exonerations  $exonerations
     * @return \Illuminate\Http\Response
     */
    public function show(Exonerations $exonerations) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exonerations  $exonerations
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Exonerations::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exonerations  $exonerations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $d = $request->except("_token", "_method");
        Exonerations::where('id', '=', $id)->update($d);
        return Redirect::back()->with(['message' => 'Exoneracion actualizada con exito!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exonerations  $exonerations
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Exonerations::destroy($id);
        return Redirect::back()->with('message', 'Exoneracion eliminada correctamente');
    }

}
