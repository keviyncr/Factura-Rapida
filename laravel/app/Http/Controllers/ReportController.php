<?php

namespace App\Http\Controllers;
use App\Documents;
use App\Exports\ProfitAndLostExport;
use App\Exports\DetailSalesExport;
use App\Exports\DetailExpensesExport;
use App\Exports\IVAExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\EconomicActivities;

class ReportController extends Controller {

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
     public function dSales(Request $request) {
      $p = $request->except("_token");
      $datos = array();
      if(isset($p["f1"]) && isset($p["f2"])){
          $datos["f1"] = $p["f1"];
          $datos["f2"] = $p["f2"];
        //Ingresos 
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->where('e_a', $p["economic_activities"])
                ->orderBy('created_at', 'DESC')->get();
            }else{
              $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get();  
            }
        }else{
             $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get(); 
        }
        $datos["sumaMonto"]= 0;
        $datos["sumaDescuento"]= 0;
        $datos["sumaImpuesto"]= 0;
        $datos["sumaTotal"]= 0;
        $datos["detail"] = array();
        $line = array();
        if(sizeof($docs) > 0){
            foreach($docs as $index => $doc){
                $path = 'laravel/storage/app/public/'.$doc->ruta."/".$doc->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    $line["state"] = $doc->answer_mh;
                    $line["ae"] = $xml->CodigoActividad;
                    $line["fecha"] = substr ( $xml->FechaEmision,0,10);
                    $line["clave"] = $xml->NumeroConsecutivo;
                    $line["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                    }
                    if(isset($xml->Receptor)){
                        $line["receptor"] = $xml->Receptor->Nombre;
                    }else{
                        $line["receptor"] = "Sin Receptor";
                    }
                    $tipoCambio = 1;
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != "" && $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 0){
                            $tipoCambio = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    foreach($xml->DetalleServicio->LineaDetalle as  $detail){
                        if(substr($line["clave"],8,2)=="03"){
                            $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                            $typeTax = (isset($detail->Impuesto))?$detail->Impuesto->Tarifa:"Sin impuesto";
                            $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                            $line["item"]=$detail->Detalle;
                            $line["monto"]=($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad;
                            $datos["sumaMonto"] += $line["monto"];
                            $line["discount"]=$discount*-1*$tipoCambio;
                            $datos["sumaDescuento"] += $line["discount"];
                            $line["tax"]=$tax*-1*$tipoCambio;
                            $datos["sumaImpuesto"] += $line["tax"];
                            $line["total"]=$detail->MontoTotalLinea*-1*$tipoCambio;
                            $datos["sumaTotal"] += $line["total"];
                            $line["typeTax"]=$typeTax;
                            array_push($datos["detail"], $line);
                        }else{
                                $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                                $typeTax = (isset($detail->Impuesto))?$detail->Impuesto->Tarifa:"Sin impuesto";
                                $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                                $line["item"]=$detail->Detalle;
                                $line["monto"]=($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad;
                                $datos["sumaMonto"] += $line["monto"];
                                $line["discount"]=$discount*$tipoCambio;
                                $datos["sumaDescuento"] += $line["discount"];
                                $line["tax"]=$tax*$tipoCambio;
                                $datos["sumaImpuesto"] += $line["tax"];
                                $line["total"]=$detail->MontoTotalLinea*$tipoCambio;
                                $datos["sumaTotal"] += $line["total"]*$tipoCambio;
                                $line["typeTax"]=$typeTax;
                                array_push($datos["detail"], $line);
                            }
                    }
                }
            }
        }
       
      }
       $datos ['economic_activities'] = DB::table('companies_economic_activities')
                    ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                    ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                    ->where('companies_economic_activities.id_company', session('company')->id)
                    ->get();
      if(isset($p["btn2"])){
          return Excel::download(new DetailSalesExport($datos),'Detalle de Ventas.xlsx');
      }else{
          return view('company.detailSales', $datos);
      }
    }
    public function dExpenses(Request $request) {
      $p = $request->except("_token");
      
      $datos = array();
      if(isset($p["f1"]) && isset($p["f2"])){
          $datos["f1"] = $p["f1"];
          $datos["f2"] = $p["f2"];
        
        //gastos
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $exps = DB::table('expenses')
                    ->where('id_company', session('company')->id)
                    ->where('e_a', $p["economic_activities"])
                    ->orderBy('created_at', 'DESC')->get();
            }else{
              $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get();
            }
        }else{
             $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get();
        }
        
        
        
        $datos["sumaMontoG"]= 0;
        $datos["sumaDescuentoG"]= 0;
        $datos["sumaImpuestoG"]= 0;
        $datos["sumaTotalG"]= 0;
        $datos["detailG"] = array();
        $line2 = array();
        if(sizeof($exps) > 0){
            foreach($exps as $exp){
                $path = 'laravel/storage/app/public/'.$exp->ruta."/".$exp->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    
                    $line2["condition"] = $exp->condition;
                    $line2["state"] = $exp->state;
                    $line2["aeG"] = $exp->e_a;
                    $line2["category"] = $exp->category;
                    $line2["fechaG"] = substr ( $xml->FechaEmision,0,10);
                    $line2["claveG"] = $xml->NumeroConsecutivo;
                    if(isset($xml->Emisor)){
                        $line2["emisor"] = $xml->Emisor->Nombre;
                    }else{
                        $line2["emisor"] = "Sin Emisor";
                    }
                    $tipoCambio = 1;
                    $line2["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line2["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != "" && $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 0){
                            $tipoCambio = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    foreach($xml->DetalleServicio->LineaDetalle as $detail2){
                       
                        if(substr($line2["claveG"],8,2)=="03"){
                            $tax = (isset($detail2->Impuesto))?$detail2->Impuesto->Monto*$tipoCambio:0;
                            $typeTax = (isset($detail2->Impuesto))?$detail2->Impuesto->Tarifa:"Sin impuesto";
                            $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento*$tipoCambio:0;
                            
                            $line2["itemG"]=$detail2->Detalle;
                            $line2["montoG"]=($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad;
                            $datos["sumaMontoG"] += $line2["montoG"];
                            $line2["discountG"]=$discount*-1*$tipoCambio;
                            $datos["sumaDescuentoG"] += $line2["discountG"];
                            $line2["taxG"]=$tax*-1*$tipoCambio;
                            $datos["sumaImpuestoG"] += $line2["taxG"];
                            $line2["totalG"]=$detail2->MontoTotalLinea*-1*$tipoCambio;
                            $datos["sumaTotalG"] += $line2["totalG"];
                            $line2["typeTax"]=$typeTax;
                            array_push($datos["detailG"], $line2);
                        }else{
                                $tax = (isset($detail2->Impuesto))?$detail2->Impuesto->Monto*$tipoCambio:0;
                                $typeTax = (isset($detail2->Impuesto))?$detail2->Impuesto->Tarifa:"Sin impuesto";
                                $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento*$tipoCambio:0;
                                
                                $line2["itemG"]=$detail2->Detalle;
                                $line2["montoG"]=($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad;
                                $datos["sumaMontoG"] += $line2["montoG"];
                                $line2["discountG"]=$discount*$tipoCambio;
                                $datos["sumaDescuentoG"] += $line2["discountG"];
                                $line2["taxG"]=$tax*$tipoCambio;
                                $datos["sumaImpuestoG"] += $line2["taxG"];
                                $line2["totalG"]=$detail2->MontoTotalLinea*$tipoCambio;
                                $datos["sumaTotalG"] += $line2["totalG"];
                                $line2["typeTaxG"]=$typeTax;
                                array_push($datos["detailG"], $line2);
                        }
                    }
                }
            }
        }
      }
       $datos ['economic_activities'] = DB::table('companies_economic_activities')
                    ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                    ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                    ->where('companies_economic_activities.id_company', session('company')->id)
                    ->get();
      if(isset($p["btn2"])){
          return Excel::download(new DetailExpensesExport($datos),'Detalle de gastos.xlsx');
      }else{
          return view('company.detailExpenses', $datos);
      }
    }
   
    public function profitAndLost(Request $request) {
      $p = $request->except("_token");
      
      $datos = array();
      if(isset($p["f1"]) && isset($p["f2"])){
          $datos["f1"] = $p["f1"];
          $datos["f2"] = $p["f2"];
        //Ingresos 
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->where('e_a', $p["economic_activities"])
                ->orderBy('created_at', 'DESC')->get();
            }else{
              $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get();  
            }
        }else{
             $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get(); 
        }
        
        
        $datos["sumaMonto"]= 0;
        $datos["sumaDescuento"]= 0;
        $datos["sumaImpuesto"]= 0;
        $datos["sumaTotal"]= 0;
        $datos["detail"] = array();
        $line = array();
        if(sizeof($docs) > 0){
            foreach($docs as $index => $doc){
                $path = 'laravel/storage/app/public/'.$doc->ruta."/".$doc->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    if($doc->answer_mh != 'rechazado'){
                    $line["state"] = $doc->answer_mh;
                    $line["ae"] = $xml->CodigoActividad;
                    $line["fecha"] = substr ( $xml->FechaEmision,0,10);
                    $line["clave"] = $xml->NumeroConsecutivo;
                    if(isset($xml->Receptor)){
                        $line["receptor"] = $xml->Receptor->Nombre;
                    }else{
                        $line["receptor"] = "Sin Receptor";
                    }
                    $tipoCambio = 1;
                    $line["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != "" && $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 0){
                            $tipoCambio = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    foreach($xml->DetalleServicio->LineaDetalle as  $detail){
                        if(substr($line["clave"],8,2)=="03"){
                            $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                            $typeTax = (isset($detail->Impuesto))?$detail->Impuesto->Tarifa:"Sin impuesto";
                            $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                            $line["item"]=$detail->Detalle;
                            $line["monto"]=($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad;
                            $datos["sumaMonto"] += $line["monto"];
                            $line["discount"]=$discount*-1*$tipoCambio;
                            $datos["sumaDescuento"] += $line["discount"];
                            $line["tax"]=$tax*-1*$tipoCambio;
                            $datos["sumaImpuesto"] += $line["tax"];
                            $line["total"]=$detail->MontoTotalLinea*-1*$tipoCambio;
                            $datos["sumaTotal"] += $line["total"];
                            $line["typeTax"]=$typeTax;
                            array_push($datos["detail"], $line);
                        }else{
                            if(substr($line["clave"],8,2)!="04"){
                                $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                                $typeTax = (isset($detail->Impuesto))?$detail->Impuesto->Tarifa:"Sin impuesto";
                                $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                                $line["item"]=$detail->Detalle;
                                $line["monto"]=($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad;
                                $datos["sumaMonto"] += $line["monto"];
                                $line["discount"]=$discount*$tipoCambio;
                                $datos["sumaDescuento"] += $line["discount"];
                                $line["tax"]=$tax*$tipoCambio;
                                $datos["sumaImpuesto"] += $line["tax"];
                                $line["total"]=$detail->MontoTotalLinea*$tipoCambio;
                                $datos["sumaTotal"] += $line["total"];
                                $line["typeTax"]=$typeTax;
                                array_push($datos["detail"], $line);
                            }
                        }
                    }
                }
                }
            }
        }
        //gastos
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $exps = DB::table('expenses')
                    ->where('id_company', session('company')->id)
                    ->where('state', '!=','rechazado')
                    ->where('e_a', $p["economic_activities"])
                    ->orderBy('created_at', 'DESC')->get();
            }else{
              $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->where('state','!=', 'rechazado')
                ->orderBy('created_at', 'DESC')->get();
            }
        }else{
             $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->where('state','!=', 'rechazado')
                ->orderBy('created_at', 'DESC')->get();
        }
        $datos["sumaMontoG"]= 0;
        $datos["sumaDescuentoG"]= 0;
        $datos["sumaImpuestoG"]= 0;
        $datos["sumaTotalG"]= 0;
        $datos["detailG"] = array();
        $line2 = array();
        if(sizeof($exps) > 0){
            foreach($exps as $exp){
                $path = 'laravel/storage/app/public/'.$exp->ruta."/".$exp->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    if($exp->condition != 'rechazado'){
                    $line2["condition"] = $exp->condition;
                    $line2["state"] = $exp->state;
                    $line2["aeG"] = $exp->e_a;
                    $line2["category"] = $exp->category;
                    $line2["fechaG"] = substr ( $xml->FechaEmision,0,10);
                    $line2["claveG"] = $xml->NumeroConsecutivo;
                    if(isset($xml->Emisor)){
                        $line2["emisor"] = $xml->Emisor->Nombre;
                    }else{
                        $line2["emisor"] = "Sin Emisor";
                    }
                    $tipoCambio = 1;
                    $line2["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line2["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != "" && $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 0){
                            $tipoCambio = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    foreach($xml->DetalleServicio->LineaDetalle as $detail2){
                       
                        if(substr($line2["claveG"],8,2)=="03"){
                            $tax = (isset($detail2->Impuesto))?$detail2->Impuesto->Monto*$tipoCambio:0;
                            $typeTax = (isset($detail2->Impuesto))?$detail2->Impuesto->Tarifa:"Sin impuesto";
                            $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento*$tipoCambio:0;
                            
                            $line2["itemG"]=$detail2->Detalle;
                            $line2["montoG"]=($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad;
                            $datos["sumaMontoG"] += $line2["montoG"];
                            $line2["discountG"]=$discount*-1*$tipoCambio;
                            $datos["sumaDescuentoG"] += $line2["discountG"];
                            $line2["taxG"]=$tax*-1*$tipoCambio;
                            $datos["sumaImpuestoG"] += $line2["taxG"];
                            $line2["totalG"]=$detail2->MontoTotalLinea*-1*$tipoCambio;
                            $datos["sumaTotalG"] += $line2["totalG"];
                            $line2["typeTaxG"]=$typeTax;
                            array_push($datos["detailG"], $line2);
                        }else{
                            if(substr($line2["claveG"],8,2)!="04"){
                                $tax = (isset($detail2->Impuesto))?$detail2->Impuesto->Monto*$tipoCambio:0;
                                $typeTax = (isset($detail2->Impuesto))?$detail2->Impuesto->Tarifa:"Sin impuesto";
                                $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento*$tipoCambio:0;
                                
                                $line2["itemG"]=$detail2->Detalle;
                                $line2["montoG"]=($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad;
                                $datos["sumaMontoG"] += $line2["montoG"];
                                $line2["discountG"]=$discount*$tipoCambio;
                                $datos["sumaDescuentoG"] += $line2["discountG"];
                                $line2["taxG"]=$tax*$tipoCambio;
                                $datos["sumaImpuestoG"] += $line2["taxG"];
                                $line2["totalG"]=$detail2->MontoTotalLinea*$tipoCambio;
                                $datos["sumaTotalG"] += $line2["totalG"];
                                $line2["typeTaxG"]=$typeTax;
                                array_push($datos["detailG"], $line2);
                            }
                        }
                    }
                }
                }
            }
        }
      }
       $datos ['economic_activities'] = DB::table('companies_economic_activities')
                    ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                    ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                    ->where('companies_economic_activities.id_company', session('company')->id)
                    ->get();
      if(isset($p["btn2"])){
          return Excel::download(new ProfitAndLostExport($datos),'GastosIngresos.xlsx');
      }else{
          return view('company.profitAndLost', $datos);
      }
    }
    
    public function ivaReport(Request $request) {
      $p = $request->except("_token");
      $datos = array();
      if(isset($p["f1"]) && isset($p["f2"])){
          $datos["f1"] = $p["f1"];
          $datos["f2"] = $p["f2"];
        //Ingresos 
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->where('e_a', $p["economic_activities"])
                ->orderBy('created_at', 'DESC')->get();
            }else{
              $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get();  
            }
        }else{
             $docs = DB::table('documents')
                ->where('id_company', session('company')->id)
                ->orderBy('created_at', 'DESC')->get(); 
        }
        
        $datos["sumaMontoB"]= 0;
        $datos["sumaDescuentoB"]= 0;
        $datos["sumaTotalB"]= 0;
        $datos["sumaExoB"]= 0;
        $datos["sumaMontoBC"]= 0;
        $datos["sumaDescuentoBC"]= 0;
        $datos["sumaTotalBC"]= 0;
        $datos["sumaExoBC"]= 0;
        $datos["sumaMontoS"]= 0;
        $datos["sumaDescuentoS"]= 0;
        $datos["sumaTotalS"]= 0;
        $datos["sumaExoS"]= 0;
        $datos["sumaMontoE"]= 0;
        $datos["sumaDescuentoE"]= 0;
        $datos["sumaTotalE"]= 0;
        $datos["sumaExoE"]= 0;
        $datos["sumaMontoNS"]= 0;
        $datos["sumaDescuentoNS"]= 0;
        $datos["sumaTotalNS"]= 0;
        $datos["sumaExoNS"]= 0;
        $datos["sumaMontoFAE"]= 0;
        $datos["sumaDescuentoFAE"]= 0;
        $datos["sumaTotalFAE"]= 0;
        $datos["sumaExoFAE"]= 0;
        $datos["sumaMontoSC"]= 0;
        $datos["sumaDescuentoSC"]= 0;
        $datos["sumaTotalSC"]= 0;
        $datos["sumaExoSC"]= 0;
        
        $datos["sumaImpuestoB0"]= 0;
        $datos["sumaMontoB0"]= 0;
        $datos["sumaImpuestoB1"]= 0;
        $datos["sumaMontoB1"]= 0;
        $datos["sumaImpuestoB2"]= 0;
        $datos["sumaMontoB2"]= 0;
        $datos["sumaImpuestoB4"]= 0;
        $datos["sumaMontoB4"]= 0;
        $datos["sumaImpuestoB8"]= 0;
        $datos["sumaMontoB8"]= 0;
        $datos["sumaImpuestoB13"]= 0;
        $datos["sumaMontoB13"]= 0;
        $datos["sumaImpuestoBC0"]= 0;
        $datos["sumaMontoBC0"]= 0;
        $datos["sumaImpuestoBC1"]= 0;
        $datos["sumaMontoBC1"]= 0;
        $datos["sumaImpuestoBC2"]= 0;
        $datos["sumaMontoBC2"]= 0;
        $datos["sumaImpuestoBC4"]= 0;
        $datos["sumaMontoBC4"]= 0;
        $datos["sumaImpuestoBC8"]= 0;
        $datos["sumaMontoBC8"]= 0;
        $datos["sumaImpuestoBC13"]= 0;
        $datos["sumaMontoBC13"]= 0;
        $datos["sumaImpuestoS0"]= 0;
        $datos["sumaMontoS0"]= 0;
        $datos["sumaImpuestoS1"]= 0;
        $datos["sumaMontoS1"]= 0;
        $datos["sumaImpuestoS2"]= 0;
        $datos["sumaMontoS2"]= 0;
        $datos["sumaImpuestoS4"]= 0;
        $datos["sumaMontoS4"]= 0;
        $datos["sumaImpuestoS8"]= 0;
        $datos["sumaMontoS8"]= 0;
        $datos["sumaImpuestoS13"]= 0;
        $datos["sumaMontoS13"]= 0;
        $datos["sumaImpuestoE0"]= 0;
        $datos["sumaMontoE0"]= 0;
        $datos["sumaImpuestoE1"]= 0;
        $datos["sumaMontoE1"]= 0;
        $datos["sumaImpuestoE2"]= 0;
        $datos["sumaMontoE2"]= 0;
        $datos["sumaImpuestoE4"]= 0;
        $datos["sumaMontoE4"]= 0;
        $datos["sumaImpuestoE8"]= 0;
        $datos["sumaMontoE8"]= 0;
        $datos["sumaImpuestoE13"]= 0;
        $datos["sumaMontoE13"]= 0;
        $datos["sumaImpuestoNS0"]= 0;
        $datos["sumaMontoNS0"]= 0;
        $datos["sumaImpuestoNS1"]= 0;
        $datos["sumaMontoNS1"]= 0;
        $datos["sumaImpuestoNS2"]= 0;
        $datos["sumaMontoNS2"]= 0;
        $datos["sumaImpuestoNS4"]= 0;
        $datos["sumaMontoNS4"]= 0;
        $datos["sumaImpuestoNS8"]= 0;
        $datos["sumaMontoNS8"]= 0;
        $datos["sumaImpuestoNS13"]= 0;
        $datos["sumaMontoNS13"]= 0;
        $datos["sumaImpuestoFAE0"]= 0;
        $datos["sumaMontoFAE0"]= 0;
        $datos["sumaImpuestoFAE1"]= 0;
        $datos["sumaMontoFAE1"]= 0;
        $datos["sumaImpuestoFAE2"]= 0;
        $datos["sumaMontoFAE2"]= 0;
        $datos["sumaImpuestoFAE4"]= 0;
        $datos["sumaMontoFAE4"]= 0;
        $datos["sumaImpuestoFAE8"]= 0;
        $datos["sumaMontoFAE8"]= 0;
        $datos["sumaImpuestoFAE13"]= 0;
        $datos["sumaMontoFAE13"]= 0;
        $datos["sumaImpuestoSC0"]= 0;
        $datos["sumaMontoSC0"]= 0;
        $datos["sumaImpuestoSC1"]= 0;
        $datos["sumaMontoSC1"]= 0;
        $datos["sumaImpuestoSC2"]= 0;
        $datos["sumaMontoSC2"]= 0;
        $datos["sumaImpuestoSC4"]= 0;
        $datos["sumaMontoSC4"]= 0;
        $datos["sumaImpuestoSC8"]= 0;
        $datos["sumaMontoSC8"]= 0;
        $datos["sumaImpuestoSC13"]= 0;
        $datos["sumaMontoSC13"]= 0;
        
        
        $datos["sumaTotal"]= 0;
        $datos["sinClasificar"] = array();
        $datos["bienes"] = array();
        $datos["bienesCapital"] = array();
        $datos["servicios"] = array();
        $datos["exento"] = array();
        $datos["noSujeto"] = array();
        $datos["fueraAE"] = array();
        $line = array();
        if(sizeof($docs) > 0){
            foreach($docs as $index => $doc){
                $path = 'laravel/storage/app/public/'.$doc->ruta."/".$doc->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    if($doc->answer_mh != 'rechazado'){
                    $line["ae"] = $xml->CodigoActividad;
                    $line["fecha"] = substr ( $xml->FechaEmision,0,10);
                    $line["clave"] = $xml->NumeroConsecutivo;
                    $line["emisor"] = $xml->Emisor->Nombre;
                    $line["receptor"] = (isset($xml->Receptor->Nombre))?$xml->Receptor->Nombre:"No hay";
                    $tipoCambio = 1;
                    $line["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != "" && $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 'CRC'){
                            $tipoCambio = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    $tipoCambio = ($tipoCambio<1)?1:$tipoCambio;
                    $line["monto"]=0;
                    $line["discount"]=0;
                    $line["exo"]=0;
                    $line["tax0"]=0;
                    $line["tax1"]=0;
                    $line["tax2"]=0;
                    $line["tax4"]=0;
                    $line["tax8"]=0;
                    $line["tax13"]=0;
                    $line["total"]=0;
                    foreach($xml->DetalleServicio->LineaDetalle as  $detail){
                       if(substr($line["clave"],8,2)=="03"){
                            $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                            $exo = (isset($detail->Impuesto->Exoneracion))?$detail->Impuesto->Exoneracion->MontoExoneracion*$tipoCambio:0;
                            $typeTax = (isset($detail->Impuesto))?(int)$detail->Impuesto->Tarifa:"0";
                            $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                            $line["monto"]+=($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad;
                            $line["discount"]+=$discount*-1*$tipoCambio;
                            $line["tax".$typeTax]+=$tax*-1*$tipoCambio;
                            $line["exo"]+=$exo*-1*$tipoCambio;
                            $line["total"]+=$detail->MontoTotalLinea*-1*$tipoCambio;
                            
                            if($doc->category == "Bienes"){
                                $datos["sumaMontoB".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoB".$typeTax] += $tax*-1;
                                $datos["sumaExoB"] += $exo*-1;
                            }else if($doc->category == "Bienes Capital"){
                                $datos["sumaMontoBC".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoBC".$typeTax] += $tax*-1;
                                $datos["sumaExoBC"] += $exo*-1;
                            }else if($doc->category == "Servicios"){
                                $datos["sumaMontoS".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoS".$typeTax] += $tax*-1;
                            }else if($doc->category == "Exento"){
                                $datos["sumaMontoE".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoE".$typeTax] += $tax*-1;
                                $datos["sumaExoE"] += $exo*-1;
                            }else if($doc->category == "No sujeto"){
                                $datos["sumaMontoNS".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoNS".$typeTax] += $tax*-1;
                                $datos["sumaExoNS"] += $exo*-1;
                            }else if($doc->category == "Fuera de la actividad economica"){
                                $datos["sumaMontoFAE".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoFAE".$typeTax] += $tax*-1;
                                $datos["sumaExoFAE"] += $exo*-1;
                            }else{
                                $datos["sumaMontoSC".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoSC".$typeTax] += $tax*-1;
                                $datos["sumaExoSC"] += $exo*-1;
                            }
                           
                        }else{
                                $tax = (isset($detail->Impuesto))?$detail->Impuesto->Monto*$tipoCambio:0;
                                $exo = (isset($detail->Impuesto->Exoneracion))?$detail->Impuesto->Exoneracion->MontoExoneracion*$tipoCambio:0;
                                $typeTax = (isset($detail->Impuesto))?(int)$detail->Impuesto->Tarifa:"0";
                                $discount = (isset($detail->Descuento))?$detail->Descuento->MontoDescuento*$tipoCambio:0;
                                $line["monto"]+=($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad;
                                $line["discount"]+=$discount*$tipoCambio;
                                $line["tax".$typeTax]+=$tax*$tipoCambio;
                                $line["exo"]+=$exo*$tipoCambio;
                                $line["total"]+=$detail->MontoTotalLinea*$tipoCambio;
                                
                                
                                if($doc->category == "Bienes"){
                                    $datos["sumaMontoB".$typeTax]+= (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoB".$typeTax] += $tax;
                                    $datos["sumaExoB"] += $exo;
                                }else if($doc->category == "Bienes Capital"){
                                    $datos["sumaMontoBC".$typeTax]+= (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoBC".$typeTax] += $tax;
                                     $datos["sumaExoBC"] += $exo;
                                }else if($doc->category == "Servicios"){
                                    $datos["sumaMontoS".$typeTax] += (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoS".$typeTax] += $tax;
                                     $datos["sumaExoS"] += $exo;
                                }else if($doc->category == "Exento"){
                                    $datos["sumaMontoE".$typeTax]+= (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoE".$typeTax] += $tax;
                                     $datos["sumaExoE"] += $exo;
                                }else if($doc->category == "No sujeto"){
                                    $datos["sumaMontoNS".$typeTax]+= (($detail->PrecioUnitario*-1*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoNS".$typeTax] += $tax;
                                     $datos["sumaExoNS"] += $exo;
                                }else if($doc->category == "Fuera de la actividad economica"){
                                    $datos["sumaMontoFAE".$typeTax]+= (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoFAE".$typeTax] += $tax;
                                     $datos["sumaExoFAE"] += $exo;
                                }else{
                                    $datos["sumaMontoSC".$typeTax]+= (($detail->PrecioUnitario*$tipoCambio)*$detail->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoSC".$typeTax] += $tax;
                                     $datos["sumaExoSC"] += $exo;
                                }
                        }
                        
                    }
                    
                    if($doc->category == "Bienes"){
                        array_push($datos["bienes"], $line);
                        $datos["sumaMontoB"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoB"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalB"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else if($doc->category == "Bienes Capital"){
                        array_push($datos["bienesCapital"], $line);
                        
                        $datos["sumaMontoBC"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoBC"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalBC"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else if($doc->category == "Servicios"){
                        array_push($datos["servicios"], $line);
                        $datos["sumaMontoS"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoS"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalS"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else if($doc->category == "Exento"){
                        array_push($datos["exento"], $line);
                        $datos["sumaMontoE"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoE"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalE"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else if($doc->category == "No sujeto"){
                        array_push($datos["noSujeto"], $line);
                        
                        $datos["sumaMontoNS"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoNS"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalNS"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else if($doc->category == "Fuera de la actividad economica"){
                        array_push($datos["fueraAE"], $line);
                        $datos["sumaMontoFAE"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoFAE"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalFAE"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }else{
                        array_push($datos["sinClasificar"], $line);
                        $datos["sumaMontoSC"] += ($line["monto"]-$line["discount"])*$tipoCambio;
                        $datos["sumaDescuentoSC"] += $line["discount"]*$tipoCambio;
                        $datos["sumaTotalSC"] += ($line["total"]-$line["discount"])*$tipoCambio;
                    }
                    
                }
                }
            }
        }
        //gastos
        if(isset($p["economic_activities"])){
            if($p["economic_activities"] != 0){
                $exps = DB::table('expenses')
                    ->where('id_company', session('company')->id)
                    ->where('state', '!=','rechazado')
                    ->where('e_a', $p["economic_activities"])
                    ->orderBy('created_at', 'DESC')->get();
            }else{
              $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->where('state', '!=','rechazado')
                ->orderBy('created_at', 'DESC')->get();
            }
        }else{
             $exps = DB::table('expenses')
                ->where('id_company', session('company')->id)
                ->where('state', '!=','rechazado')
                ->orderBy('created_at', 'DESC')->get();
        }
        
        
        
        $datos["sumaMontoBG"]= 0;
        $datos["sumaDescuentoBG"]= 0;
        $datos["sumaTotalBG"]= 0;
        $datos["sumaExoBG"]= 0;
        $datos["sumaOtrosBG"]= 0;
        
        $datos["sumaMontoBCG"]= 0;
        $datos["sumaDescuentoBCG"]= 0;
        $datos["sumaTotalBCG"]= 0;
        $datos["sumaExoBCG"]= 0;
        $datos["sumaOtrosBCG"]= 0;
        
        $datos["sumaMontoSG"]= 0;
        $datos["sumaDescuentoSG"]= 0;
        $datos["sumaTotalSG"]= 0;
        $datos["sumaExoSG"]= 0;
        $datos["sumaOtrosSG"]= 0;
        
        $datos["sumaMontoEG"]= 0;
        $datos["sumaDescuentoEG"]= 0;
        $datos["sumaTotalEG"]= 0;
        $datos["sumaExoEG"]= 0;
        $datos["sumaOtrosEG"]= 0;
        
        $datos["sumaMontoNSG"]= 0;
        $datos["sumaDescuentoNSG"]= 0;
        $datos["sumaTotalNSG"]= 0;
        $datos["sumaExoNSG"]= 0;
        $datos["sumaOtrosNSG"]= 0;
        
        
        $datos["sumaMontoFAEG"]= 0;
        $datos["sumaDescuentoFAEG"]= 0;
        $datos["sumaTotalFAEG"]= 0;
        $datos["sumaExoFAEG"]= 0;
        $datos["sumaOtrosFAEG"]= 0;
        
        $datos["sumaMontoSCG"]= 0;
        $datos["sumaDescuentoSCG"]= 0;
        $datos["sumaTotalSCG"]= 0;
        $datos["sumaExoSCG"]= 0;
        $datos["sumaOtrosSCG"]= 0;
       
        $datos["sumaImpuestoBG0"]= 0;
        $datos["sumaImpuestoBG1"]= 0;
        $datos["sumaImpuestoBG2"]= 0;
        $datos["sumaImpuestoBG4"]= 0;
        $datos["sumaImpuestoBG8"]= 0;
        $datos["sumaImpuestoBG13"]= 0;
        $datos["sumaImpuestoBCG0"]= 0;
        $datos["sumaImpuestoBCG1"]= 0;
        $datos["sumaImpuestoBCG2"]= 0;
        $datos["sumaImpuestoBCG4"]= 0;
        $datos["sumaImpuestoBCG8"]= 0;
        $datos["sumaImpuestoBCG13"]= 0;
        $datos["sumaImpuestoSG0"]= 0;
        $datos["sumaImpuestoSG1"]= 0;
        $datos["sumaImpuestoSG2"]= 0;
        $datos["sumaImpuestoSG4"]= 0;
        $datos["sumaImpuestoSG8"]= 0;
        $datos["sumaImpuestoSG13"]= 0;
        $datos["sumaImpuestoEG0"]= 0;
        $datos["sumaImpuestoEG1"]= 0;
        $datos["sumaImpuestoEG2"]= 0;
        $datos["sumaImpuestoEG4"]= 0;
        $datos["sumaImpuestoEG8"]= 0;
        $datos["sumaImpuestoEG13"]= 0;
        $datos["sumaImpuestoNSG0"]= 0;
        $datos["sumaImpuestoNSG1"]= 0;
        $datos["sumaImpuestoNSG2"]= 0;
        $datos["sumaImpuestoNSG4"]= 0;
        $datos["sumaImpuestoNSG8"]= 0;
        $datos["sumaImpuestoNSG13"]= 0;
        $datos["sumaImpuestoFAEG0"]= 0;
        $datos["sumaImpuestoFAEG1"]= 0;
        $datos["sumaImpuestoFAEG2"]= 0;
        $datos["sumaImpuestoFAEG4"]= 0;
        $datos["sumaImpuestoFAEG8"]= 0;
        $datos["sumaImpuestoFAEG13"]= 0;
        $datos["sumaImpuestoSCG0"]= 0;
        $datos["sumaImpuestoSCG1"]= 0;
        $datos["sumaImpuestoSCG2"]= 0;
        $datos["sumaImpuestoSCG4"]= 0;
        $datos["sumaImpuestoSCG8"]= 0;
        $datos["sumaImpuestoSCG13"]= 0;
        
        $datos["sumaMontoBG0"]= 0;
        $datos["sumaMontoBG1"]= 0;
        $datos["sumaMontoBG2"]= 0;
        $datos["sumaMontoBG4"]= 0;
        $datos["sumaMontoBG8"]= 0;
        $datos["sumaMontoBG13"]= 0;
        $datos["sumaMontoBCG0"]= 0;
        $datos["sumaMontoBCG1"]= 0;
        $datos["sumaMontoBCG2"]= 0;
        $datos["sumaMontoBCG4"]= 0;
        $datos["sumaMontoBCG8"]= 0;
        $datos["sumaMontoBCG13"]= 0;
        $datos["sumaMontoSG0"]= 0;
        $datos["sumaMontoSG1"]= 0;
        $datos["sumaMontoSG2"]= 0;
        $datos["sumaMontoSG4"]= 0;
        $datos["sumaMontoSG8"]= 0;
        $datos["sumaMontoSG13"]= 0;
        $datos["sumaMontoEG0"]= 0;
        $datos["sumaMontoEG1"]= 0;
        $datos["sumaMontoEG2"]= 0;
        $datos["sumaMontoEG4"]= 0;
        $datos["sumaMontoEG8"]= 0;
        $datos["sumaMontoEG13"]= 0;
        $datos["sumaMontoNSG0"]= 0;
        $datos["sumaMontoNSG1"]= 0;
        $datos["sumaMontoNSG2"]= 0;
        $datos["sumaMontoNSG4"]= 0;
        $datos["sumaMontoNSG8"]= 0;
        $datos["sumaMontoNSG13"]= 0;
        $datos["sumaMontoFAEG0"]= 0;
        $datos["sumaMontoFAEG1"]= 0;
        $datos["sumaMontoFAEG2"]= 0;
        $datos["sumaMontoFAEG4"]= 0;
        $datos["sumaMontoFAEG8"]= 0;
        $datos["sumaMontoFAEG13"]= 0;
        $datos["sumaMontoSCG0"]= 0;
        $datos["sumaMontoSCG1"]= 0;
        $datos["sumaMontoSCG2"]= 0;
        $datos["sumaMontoSCG4"]= 0;
        $datos["sumaMontoSCG8"]= 0;
        $datos["sumaMontoSCG13"]= 0;
        
       
        $datos["sinClasificarG"] = array();
        $datos["bienesG"] = array();
        $datos["bienesCapitalG"] = array();
        $datos["serviciosG"] = array();
        $datos["exentoG"] = array();
        $datos["noSujetoG"] = array();
        $datos["fueraAEG"] = array();
        $line2 = array();
        if(sizeof($exps) > 0){
            foreach($exps as $exp){
                $path = 'laravel/storage/app/public/'.$exp->ruta."/".$exp->key.".xml";
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
                if(substr ( $xml->FechaEmision,0,10) >= $p["f1"] && substr ( $xml->FechaEmision,0,10) <= $p["f2"]){
                    if($exp->condition != 'rechazado'){
                    $line2["condition"] = $exp->condition;
                    $line2["state"] = $exp->state;
                    $line2["aeG"] = $exp->e_a;
                    $line2["category"] = $exp->category;
                    $line2["fechaG"] = substr ( $xml->FechaEmision,0,10);
                    $line2["claveG"] = $xml->NumeroConsecutivo;
                    $line2["otrosG"] = 0;
                   
                    if(isset($xml->Emisor)){
                        $line2["emisor"] = $xml->Emisor->Nombre;
                    }else{
                        $line2["emisor"] = "Sin Emisor";
                    }
                    $tipoCambio = 1;
                    $line2["tmoneda"] = "CRC";
                    if(isset($xml->ResumenFactura->CodigoTipoMoneda)){
                        $line2["tmoneda"] = $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                        if($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != 'CRC'){
                         $tipoCambio = ''.$xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        }
                    }
                    $tipoCambio = ($tipoCambio<1)?1:$tipoCambio;
                    $line2["montoG"]=0;
                    $line2["discountG"]=0;
                    $line2["exoG"]=0;
                    $line2["taxG0"]=0;
                    $line2["taxG1"]=0;
                    $line2["taxG2"]=0;
                    $line2["taxG4"]=0;
                    $line2["taxG8"]=0;
                    $line2["taxG13"]=0;
                    $line2["totalG"]=0;
                    
                    foreach($xml->DetalleServicio->LineaDetalle as $detail2){
                        if(substr($line2["claveG"],8,2)=="03"){
                            $tax = (isset($detail2->Impuesto) && $line2["tmoneda"] != "CRC")?$detail2->Impuesto->Monto*$tipoCambio:0;
                            $exo = (isset($detail2->Impuesto->Exoneracion))?$detail2->Impuesto->Exoneracion->MontoExoneracion*$tipoCambio:0;
                            $typeTax = (isset($detail2->Impuesto))?(int)$detail2->Impuesto->Tarifa:"0";
                            $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento*$tipoCambio:0;
                            $line2["montoG"]+=($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad;
                            $line2["discountG"]+=$discount*-1*$tipoCambio;
                            $line2["taxG".$typeTax]+=$tax*-1*$tipoCambio;
                            $line2["exoG"]+=$exo*-1*$tipoCambio;
                            $line2["totalG"]+=$detail2->MontoTotalLinea*-1*$tipoCambio;
                            
                            
                           
                            if($line2["category"] == "Bienes"){
                                $datos["sumaMontoBG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoBG".$typeTax] += $tax*-1;
                                $datos["sumaExoBG"] += $exo*-1;
                            }else if($line2["category"] == "Bienes Capital"){
                                $datos["sumaMontoBCG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoBCG".$typeTax] += $tax*-1;
                                $datos["sumaExoBCG"] += $exo*-1;
                            }else if($line2["category"] == "Servicios"){
                                $datos["sumaMontoSG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoSG".$typeTax] += $tax*-1;
                                $datos["sumaExoSG"] += $exo*-1;
                            }else if($line2["category"] == "Exento"){
                                $datos["sumaMontoEG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoEG".$typeTax] += $tax*-1;
                                $datos["sumaExoEG"] += $exo*-1;
                            }else if($line2["category"] == "No sujeto"){
                                $datos["sumaMontoNSG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoNSG".$typeTax] += $tax*-1;
                                $datos["sumaExoNSG"] += $exo*-1;
                            }else if($line2["category"] == "Fuera de la actividad economica"){
                                $datos["sumaMontoFAEG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaExoFAEG"] += $exo*-1;
                                $datos["sumaImpuestoFAEG".$typeTax] += $tax*-1;
                            }else{
                                $datos["sumaMontoSCG".$typeTax]+= (($detail2->PrecioUnitario*-1*$tipoCambio)*$detail2->Cantidad*-1)-($discount*-1*$tipoCambio);
                                $datos["sumaImpuestoSCG".$typeTax] += $tax*-1; 
                                $datos["sumaExoSCG"] += $exo*-1;
                            }
                           
                        }else{
                            if(substr($line2["claveG"],8,2)!="04"){
                                $tax = (isset($detail2->Impuesto) && $line2["tmoneda"] != "CRC")?$detail2->Impuesto->Monto*$tipoCambio:0;
                                $exo = (isset($detail2->Impuesto->Exoneracion))?$detail2->Impuesto->Exoneracion->MontoExoneracion*$tipoCambio:0;
                                $typeTax = (isset($detail2->Impuesto))?(int)$detail2->Impuesto->Tarifa:"0";
                                $discount = (isset($detail2->Descuento))?$detail2->Descuento->MontoDescuento:0;
                                
                                $line2["montoG"]+=($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad;
                                $line2["discountG"]+=$discount*$tipoCambio;
                                if($typeTax == 0){
                                    $line2["taxG".$typeTax]+=(($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad);
                                    $line2["montoG"]-=$line2["taxG".$typeTax];
                                }else{
                                    $line2["taxG".$typeTax]+=$tax*$tipoCambio; 
                                }
                                $line2["exoG"]+=$exo*$tipoCambio; 
                                $line2["totalG"]+=$detail2->MontoTotalLinea*$tipoCambio;
                                
                                
                                if($line2["category"] == "Bienes"){
                                    $datos["sumaMontoBG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoBG".$typeTax] += ((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoBG"] += $exo;
                                }else if($line2["category"] == "Bienes Capital"){
                                    $datos["sumaMontoBCG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoBCG".$typeTax] += ((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoBCG"] += $exo;
                                }else if($line2["category"] == "Servicios"){
                                    $datos["sumaMontoSG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoSG".$typeTax] +=((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoSG"] += $exo;
                                }else if($line2["category"] == "Exento"){
                                    $datos["sumaMontoEG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoEG".$typeTax] +=((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoEG"] += $exo;
                                }else if($line2["category"] == "No sujeto"){
                                    $datos["sumaMontoNSG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoNSG".$typeTax] += ((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                }else if($line2["category"] == "Fuera de la actividad economica"){
                                    $datos["sumaMontoFAEG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoFAEG".$typeTax] += ((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoFAEG"] += $exo;
                                }else{
                                    $datos["sumaMontoSCG".$typeTax]+= (($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio);
                                    $datos["sumaImpuestoSCG".$typeTax] += ((($detail2->PrecioUnitario*$tipoCambio)*$detail2->Cantidad)-($discount*$tipoCambio))*$typeTax/100;
                                    $datos["sumaExoSCG"] += $exo;
                                }
                                
                                
                            }
                        }
                    }
                    
                    
                    if($line2["category"] == "Bienes"){
                        array_push($datos["bienesG"], $line2);
                        
                        $datos["sumaMontoBG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoBG"] += $line2["discountG"];
                        $datos["sumaTotalBG"] += ($line2["totalG"]);
                        $datos["sumaOtrosBG"]= $line2["otrosG"];
                    }else if($line2["category"] == "Bienes Capital"){
                        array_push($datos["bienesCapitalG"], $line2);
                        
                        $datos["sumaMontoBCG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoBCG"] += $line2["discountG"];
                        $datos["sumaTotalBCG"] +=($line2["totalG"]);
                        $datos["sumaOtrosBCG"]= $line2["otrosG"];
                    }else if($line2["category"] == "Servicios"){
                        array_push($datos["serviciosG"], $line2);
                        
                        $datos["sumaMontoSG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoSG"] += $line2["discountG"];
                        $datos["sumaTotalSG"] += ($line2["totalG"]);
                        $datos["sumaOtrosSG"]= $line2["otrosG"];
                    }else if($line2["category"] == "Exento"){
                        array_push($datos["exentoG"], $line2);
                        
                        $datos["sumaMontoEG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoEG"] += $line2["discountG"];
                        $datos["sumaTotalEG"] += ($line2["totalG"]);
                        $datos["sumaOtrosEG"]= $line2["otrosG"];
                    }else if($line2["category"] == "No sujeto"){
                        array_push($datos["noSujetoG"], $line2);
                        
                        $datos["sumaMontoNSG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoNSG"] += $line2["discountG"];
                        $datos["sumaTotalNSG"] += ($line2["totalG"]);
                        $datos["sumaOtrosNSG"]= $line2["otrosG"];
                    }else if($line2["category"] == "Fuera de la actividad economica"){
                        array_push($datos["fueraAEG"], $line2);
                        
                        $datos["sumaMontoFAEG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoFAEG"] += $line2["discountG"];
                        $datos["sumaTotalFAEG"] += ($line2["totalG"]);
                        $datos["sumaOtrosFAEG"]= $line2["otrosG"];
                    }else{
                        array_push($datos["sinClasificarG"], $line2);
                        
                        $datos["sumaMontoSCG"] += ($line2["montoG"]-$line2["discountG"]);
                        $datos["sumaDescuentoSCG"] += $line2["discountG"];
                        $datos["sumaTotalSCG"] += ($line2["totalG"]);
                        $datos["sumaOtrosSCG"]= $line2["otrosG"];
                    }
                }
                }
            }
        }
      }
       $datos ['economic_activities'] = DB::table('companies_economic_activities')
                    ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                    ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                    ->where('companies_economic_activities.id_company', session('company')->id)
                    ->get();
      if(isset($p["btn2"])){
          return Excel::download(new IVAExport($datos),'Reporte IVA.xlsx');
      }else{
          return view('company.iva', $datos);
      }
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Taxes::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Taxes  $taxes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

}
