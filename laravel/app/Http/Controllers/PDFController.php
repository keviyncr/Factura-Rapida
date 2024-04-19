<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller {

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
    public function viewInvoice() {
        $data = ['title' => 'coding driver test title'];
        $carpeta = storage_path('app/public/files/' . session('company')->id_card . '/Creados/SinEnviar/50607052000030438030800100001010000000037119890717');

        $pdf = PDF::loadView('invoice.invoice_pdf', $data)
                ->save($carpeta . 'prueba2.pdf');
        return view('invoice.invoice_pdf', $data);
        //return $pdf->download('codingdriver.pdf');
    }

}
