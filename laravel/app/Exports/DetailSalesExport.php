<?php

namespace App\Exports;

use App\Documents;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class DetailSalesExport implements FromView{
    private $data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($datos) {
        $this->data = $datos;
    }
    public function view(): View {
      return view('company.detailSalesD',$this->data);
        
    }
}
