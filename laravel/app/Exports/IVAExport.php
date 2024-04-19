<?php

namespace App\Exports;

use App\Documents;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;

class IVAExport implements FromView, WithTitle{
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
      return view('company.ivaD',$this->data);
        
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Titulo';
    }
}
