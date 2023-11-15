<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
       
    }

    public function exportFileIVA() 
    {
        return Excel::download(new IVAFileExport, 'Reporte IVA.xlsx');
    }

   

}
