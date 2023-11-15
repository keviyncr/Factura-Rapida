<?php

namespace App\Http\Controllers;

use App\Expenses;
use Illuminate\Http\Request;
use App\Hacienda\Firmador;
use Illuminate\Support\Facades\Redirect;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use QuickBooksOnline\API\Facades\Bill;
use QuickBooksOnline\API\Facades\Vendor;
use QuickBooksOnline\API\Facades\VendorCredit;
use App\References;
use App\Skus;
use App\Discounts;
use App\Documents;
use App\Taxes;
use App\Consecutives;
use App\Exonerations;
use App\TypeDocumentExonerations;
use App\TaxesCode;
use App\RateCode;
use App\Providers;
use App\Products;
use App\EconomicActivities;
use App\SaleConditions;
use App\PaymentMethods;
use App\Currencies;
use App\Companies;
use App\BranchOffices;
use App\ReferenceTypeDocuments;
use Illuminate\Support\Facades\DB;
use PDF;

class ExpensesController extends Controller {

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
        
        $datos ['e_as'] = DB::table('companies_economic_activities')
                ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                ->where('companies_economic_activities.id_company', session('company')->id)
                ->get();
        $datos ['skuses'] = Skus::all();
        $datos ['discounts'] = Discounts::where('discounts.id_company', session('company')->id)->get();
        $datos ['taxes'] = Taxes::where('taxes.id_company', session('company')->id)->get();
        $datos ['providers'] = Providers::where('providers.id_company', session('company')->id)->
                        where('active', '1')->get();
        
        if(isset($_GET["f1"]) && isset($_GET["f2"])){
            $datos["f1"] = $_GET['f1'];
             $datos["f2"] = $_GET['f2'];
        }else{
            $datos["f1"] =  \Carbon\Carbon::now()->subDays(30)->format("Y-m-d");; 
            $datos["f2"] = date("Y-m-d");
        }
             
        $exps = DB::table('expenses')
                    ->join('providers', 'providers.id', '=', 'expenses.id_provider')
                    ->select('expenses.*', 'providers.name_provider as provider')
                    ->where('expenses.id_company', session('company')->id)
                    ->orderBy('updated_at', 'DESC')->get();      
        $datos["expenses"] = array();  
        foreach($exps as $exp){
            $dt = '20'.substr($exp->key, 7, 2).'-'.substr($exp->key, 5, 2).'-'.substr($exp->key, 3, 2);
            if($dt >=  $datos["f1"] && $dt <=  $datos["f2"] ){
               array_push($datos["expenses"] , $exp);
            }       
        }
        
        $datos ['sale_conditions'] = SaleConditions::all();
        $datos ['payment_methods'] = PaymentMethods::all();
        $datos ['reference_type_expenses'] = ReferenceTypeDocuments::all();
        $datos ['reference_codes'] = References::all();
        $datos ['currencies'] = Currencies::all();
        $datos["today"] = \Carbon\Carbon::now()->format("Y/m/d");
        $datos ['branch_offices'] = BranchOffices::where("id_company", "=", session('company')->id)->get();
        $datos ["bo"] = $datos ['branch_offices'][0]["id"];
        return view('company.expenses', $datos);
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
        $company = Companies::where("companies.id", "=", session('company')->id)
                ->join('type_id_cards', 'type_id_cards.id', '=', 'companies.type_id_card')
                ->select('companies.*', 'type_id_cards.code as type_id_card_code')
                ->get();
        $p = $request->except("_token");
        $bo = BranchOffices::where("branch_offices.id", "=", $p["id_branch_office"])
                        ->join('provinces', 'provinces.id', '=', 'branch_offices.id_province')
                        ->join('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
                        ->join('districts', 'districts.id', '=', 'branch_offices.id_district')
                        ->join('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
                        ->select('branch_offices.*', 'provinces.code as province_code', 'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict',
                                'cantons.code as canton_code', 'districts.code as district_code', 'country_codes.phone_code as phone_code')->get();
      
        $provider = Providers::where("providers.id", "=", $p["id_provider"])
                ->join('type_id_cards', 'type_id_cards.id', '=', 'providers.type_id_card')
                ->join('provinces', 'provinces.id', '=', 'providers.id_province')
                ->join('cantons', 'cantons.id', '=', 'providers.id_canton')
                ->join('districts', 'districts.id', '=', 'providers.id_district')
                ->join('country_codes', 'country_codes.id', '=', 'providers.id_country_code')
                ->select('providers.*', 'provinces.code as province_code', 'cantons.code as canton_code', 'districts.code as district_code',
                        'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict', 'country_codes.phone_code as phone_code', 'type_id_cards.code as type_id_card_code')
                ->get();

        $p["id_company"] = session('company')->id;
        $p["e_a"] = EconomicActivities::findOrFail($p["id_ea"])["number"];
        $p["sc"] = SaleConditions::findOrFail($p["id_sale_condition"])["sale_condition"];
        $p["currency"] = Currencies::findOrFail($p["id_currency"])["code"];
        $p["pm"] = PaymentMethods::findOrFail($p["id_payment_method"])["payment_method"];
        $p["key"] = $this->getKey($p, $company[0]);
        $p["detail_mh"] = "Ninguno";
        $p["state"] = "Procesando";
        $p["state_send"] = "No enviado";
        
        // Start transaction!
        DB::beginTransaction();
        try {

            $saveXML = $this->invoiceXML($p, $company[0], $bo[0], $provider[0]);
 
            //firmar Documento
            $firmador = new Firmador();
            $XMLFirmado = $firmador->firmarXml('laravel/storage/app/public/' . session('company')->cryptographic_key, session('company')->pin, trim($saveXML), $firmador::TO_XML_STRING);
            $saveXML = simplexml_load_string(trim($XMLFirmado));
            $carpeta = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $saveXML->Clave . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
           
            //agregar envio aqui
            $nombre_fichero = $carpeta . $saveXML->Clave . '.xml';
            $saveXML->asXML($nombre_fichero);
            $p["ruta"] = 'Files/recibidos/procesados/' . session('company')->id_card . '/' . $saveXML->Clave . '/';
            $token = $this->tokenMH($company[0]);
            $result = $this->send($company[0], $provider[0], $saveXML->Clave, $XMLFirmado, json_decode($token)->access_token);
             
            if ($result["status"] == "202" || $result["status"] == "200") {
                $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                if ($result["ind-estado"] != "") {
                    $p["state"] = $result["ind-estado"];
                    if ($p["state"] != "procesando") {
                        $xml = simplexml_load_string(trim($result["message"]));
                        if ($p["state"] == "rechazado") {
                            $p["detail_mh"] = $xml->DetalleMensaje;
                        }
                        if (!file_exists($carpeta)) {
                            mkdir($carpeta, 0777, true);
                        }
                        //agregar envio aqui
                        $nombre_fichero = $carpeta . $saveXML->Clave . '-R.xml';
                        $xml->asXML($nombre_fichero);
                    }
                }
            }
            
            if ($result["status"] == "401" || $result["status"] == "400") {
                $p["state"] = "rechazado";
                $p["detail_mh"] = $result["result"];
            }
            
            $p["condition"] = "guardado";
            // Validate, then create if valid 
            $doc = Expenses::create($p);
            
            $this->generatePDF($company[0], $bo[0], $provider[0], $p, $doc);
            $this->nextConseutive(substr($p["consecutive"], 8, 2), $bo[0]["id"]);
            
        } catch (ValidationException $e) {
            // back to form with errors
            DB::rollback();
            echo $e->getErrors();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            echo $e;
        }
        DB::commit();
        if($p["state"] == "rechazado"){
            echo $p["state"].' - '.$p["detail_mh"];
        }else{
            echo $p["state"];
        }
        
        //return Redirect::back()->with('message', 'Documento ingrasado con exito!!');
    }

    public function generatePDF($company, $bo, $client, $p, $doc) {
        try {
           $carpeta = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' .  $p["key"] . '/'; 
            $data = ['title' => 'Factura Electronica'];
            $pdf = PDF::loadView('invoice.expenses_pdf',
                            array(
                                'company' => $company,
                                'client' => $client,
                                'bo' => $bo,
                                'p' => $p,
                                'doc' => $doc
                    ))->save($carpeta . $p["key"] . '.pdf');
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show(Expenses $expenses) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(Expenses $expenses) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expenses $expenses) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expenses $expenses) {
        //
    }

    public function invoiceXML($data, $company, $bo, $provider) {
        $d = date('Y-m-d\TH:i:s');
        $stringXML = '';
        $stringXML .= $this->header(substr($data["consecutive"], 8, 2));
        $stringXML .= '
            <Clave>' . $data["key"] . '</Clave>
            <CodigoActividad>' . str_pad($data["e_a"], 6, "0", STR_PAD_LEFT) . '</CodigoActividad>
            <NumeroConsecutivo>' . $data["consecutive"] . '</NumeroConsecutivo>
            <FechaEmision>' . $d . '</FechaEmision>
            <Emisor>
            <Nombre>' . $provider["name_provider"] . '</Nombre>
            <Identificacion>
            <Tipo>' . $provider["type_id_card_code"] . '</Tipo>
            <Numero>' . $provider["id_card"] . '</Numero>
            </Identificacion>
            <NombreComercial>' . $provider["name_provider"] . '</NombreComercial>
            <Ubicacion>
            <Provincia>' . $provider["province_code"] . '</Provincia>
            <Canton>' . $provider["canton_code"] . '</Canton>
            <Distrito>' . $provider["district_code"] . '</Distrito>
            <OtrasSenas>' . $provider["other_signs"] . '</OtrasSenas>
            </Ubicacion>
            <Telefono>
            <CodigoPais>' . $provider["phone_code"] . '</CodigoPais>
            <NumTelefono>' .substr( $provider["phone"], -8, 8)  . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $provider["emails"] . '</CorreoElectronico>           
            </Emisor>
            ';

        if ($provider != "") {
            if (substr($data["consecutive"], 8, 2) != '09') {
                $stringXML .= '<Receptor>
            <Nombre>' . $company["name_company"] . '</Nombre>
            <Identificacion>
            <Tipo>' . $company["type_id_card_code"] . '</Tipo>
            <Numero>' . $company["id_card"] . '</Numero>
            </Identificacion>
            <NombreComercial>' . $company["name_company"] . '</NombreComercial>
            <Ubicacion>
            <Provincia>' . $bo["province_code"] . '</Provincia>
            <Canton>' . $bo["canton_code"] . '</Canton>
            <Distrito>' . $bo["district_code"] . '</Distrito>
            <OtrasSenas>' . $bo["other_signs"] . '</OtrasSenas>
            </Ubicacion>
            <Telefono>
            <CodigoPais>' . $bo["phone_code"] . '</CodigoPais>
            <NumTelefono>' .substr( $bo["phone"], -8, 8) . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $bo["emails"] . '</CorreoElectronico>
            </Receptor>
            ';
            } else {
                $stringXML .= '<Receptor>
                <Nombre>' . $provider["name_company"] . '</Nombre>
                <IdentificacionExtranjero>' . $provider["id_card"] . '</IdentificacionExtranjero>
                <OtrasSenasExtranjero>' . $provider["other_signs"] . '</OtrasSenasExtranjero>
                </Receptor>
                ';
            }
        }
        $stringXML .= '<CondicionVenta>' . SaleConditions::findOrFail($data["id_sale_condition"])['code'] . '</CondicionVenta>
            <PlazoCredito>' . $data["time"] . '</PlazoCredito>
            <MedioPago>' . PaymentMethods::findOrFail($data["id_payment_method"])['code'] . '</MedioPago>        
            <DetalleServicio>';
        $TotalServGravados = 0;
        $TotalServExentos = 0;
        $TotalServExonerado = 0;
        $totalServGravados = 0;
        $TotalMercanciasGravadas = 0;
        $TotalMercanciasExentas = 0;
        $TotalMercExonerada = 0;
        $TotalDescuentos = 0;
        $TotalImpuesto = 0;
        foreach ($data['details'] as $detail) {
            $stringXML .= '<LineaDetalle>
            <NumeroLinea>' . $detail[0] . '</NumeroLinea>
            <Codigo>' . $detail[1] . '</Codigo>
            ';

            $stringXML .= '<Cantidad>' . $detail[4] . '</Cantidad>
            <UnidadMedida>' . $detail[3] . '</UnidadMedida>
            <Detalle>' . $detail[2] . '</Detalle>
            <PrecioUnitario>' . $detail[5] . '</PrecioUnitario>
            <MontoTotal>' . $detail[5] * $detail[4] . '</MontoTotal>
            ';
            $discount = 0;
            
            if ($detail[6] != 0 && $detail[6] != "") {
                $discount = $detail[6];
                $stringXML .= '<Descuento>
                <MontoDescuento>' . round($discount,5) . '</MontoDescuento>
                <NaturalezaDescuento>Descuento General a este producto</NaturalezaDescuento>
                </Descuento>
                ';
                $TotalDescuentos = $TotalDescuentos + $discount;
            }
            $subTotal = (($detail[4] * $detail[5]) - $discount);
            $stringXML .= '<SubTotal>' . $subTotal . '</SubTotal>
            ';
            $impNeto = 0;
            if ($detail[10] != "" && $detail[10] != 0) {
                $tax = Taxes::findOrFail($detail[10]);
                $taxesCode = TaxesCode::findOrFail($tax["id_taxes_code"]);
                $temp = "";
                $totalImp = 0;
                $stringXML .= '<Impuesto>
                    <Codigo>' . $taxesCode["code"] . '</Codigo>
                    ';
                $montoImp = 0;
                if ($tax["rateIVA"] != "0" && $tax["rateIVA"] != null) {
                    $montoImp = round($subTotal * ($tax["rateIVA"] - 1),5);
                    $stringXML .= '<FactorIVA>' . $tax["rateIVA"] . '</FactorIVA>
                        <Monto>' . $montoImp . '</Monto>
                        ';
                } else {
                    if($tax["id_rate_code"] != null){
                    $rateCode = RateCode::findOrFail($tax["id_rate_code"]);
                    $montoImp =round(($tax["rate"] * $subTotal) / 100, 5);
                    $stringXML .= '<CodigoTarifa>' . $rateCode["code"] . '</CodigoTarifa>
                            <Tarifa>' . $tax["rate"] . '</Tarifa>                            
                            <Monto>' . $montoImp . '</Monto>
                        ';
                    }
                }
                if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                    $exo = Exonerations::findOrFail($tax["id_exoneration"]);
                    $tde = TypeDocumentExonerations::findOrFail($exo["id_type_document_exoneration"]);
                    $fechaDoc = \Carbon\Carbon::parse($exo["date"])->format('Y-m-d\TH:i:s');
                    $exoTotal = $exo["exemption_percentage"];
                    $stringXML .= '<Exoneracion>
                        <TipoDocumento>' . $tde["code"] . '</TipoDocumento>
                        <NumeroDocumento>' . $exo["document_number"] . '</NumeroDocumento>
                        <NombreInstitucion>' . $exo["institutional_name"] . '</NombreInstitucion>
                        <FechaEmision>' . $fechaDoc . '</FechaEmision>
                        <PorcentajeExoneracion>' . $exo["exemption_percentage"] . '</PorcentajeExoneracion>
                        <MontoExoneracion>' . ($montoImp * $exo["exemption_percentage"] / 100) . '</MontoExoneracion>
                        </Exoneracion>
                        </Impuesto>
                        ';
                    $impNeto = $impNeto + ( $montoImp - ($montoImp * $exo["exemption_percentage"] / 100));
                } else {
                    $stringXML .= '</Impuesto>
                        ';
                }
                $totalImp = $totalImp + $montoImp;
                if ($detail[3] == 'Spe' || $detail[3] == 'Sp') {
                    if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                        $TotalServExonerado = $TotalServExonerado + ($detail[5] * $detail[4]) * $exoTotal / 100;
                        $TotalServGravados = $TotalServGravados + ($detail[5] * $detail[4]) * (100 - $exoTotal) / 100;
                    } else {
                        $TotalServGravados = $TotalServGravados + ($detail[5] * $detail[4]);
                    }
                } else {
                    if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                        $TotalMercExonerada = $TotalMercExonerada + ($detail[5] * $detail[4]) * $exoTotal / 100;
                        $TotalMercanciasGravadas = $TotalMercanciasGravadas + ($detail[5] * $detail[4]) * (100 - $exoTotal) / 100;
                    } else {
                        $TotalMercanciasGravadas = $TotalMercanciasGravadas + ($detail[5] * $detail[4]);
                    }
                }
            } else {
                if ($detail[3] == 'Spe' || $detail[3] == 'Sp') {
                    $TotalServExentos = $TotalServExentos + ($detail[5] * $detail[4]);
                } else {
                    $TotalMercanciasExentas = $TotalMercanciasExentas + ($detail[5] * $detail[4]);
                }
            }
            $stringXML .= '<ImpuestoNeto>' . $impNeto . '</ImpuestoNeto>
            <MontoTotalLinea>' . ($detail[9]) . '</MontoTotalLinea>
            </LineaDetalle>';
            $TotalImpuesto = $TotalImpuesto + ($detail[7] - $detail[8]);
        }
        $stringXML .= '</DetalleServicio>
        <ResumenFactura>
        <TotalServGravados>' . $TotalServGravados . '</TotalServGravados>
        <TotalServExentos>' . $TotalServExentos . '</TotalServExentos>
            ';
        if (substr($data["consecutive"], 8, 2) != '09') {
            $stringXML .= '<TotalServExonerado>' . $TotalServExonerado . '</TotalServExonerado>
            ';
        }
        $stringXML .= '<TotalMercanciasGravadas>' . $TotalMercanciasGravadas . '</TotalMercanciasGravadas>
        <TotalMercanciasExentas>' . $TotalMercanciasExentas . '</TotalMercanciasExentas>
        ';
        if (substr($data["consecutive"], 8, 2) != '09') {
            $stringXML .= '<TotalMercExonerada>' . $TotalMercExonerada . '</TotalMercExonerada>
       ';
        }
        $stringXML .= '<TotalGravado>' . ($TotalServGravados + $TotalMercanciasGravadas) . '</TotalGravado>
        <TotalExento>' . ($TotalServExentos + $TotalMercanciasExentas) . '</TotalExento>
        ';
        if (substr($data["consecutive"], 8, 2) != '09') {
            $stringXML .= '<TotalExonerado>' . ($TotalServExonerado + $TotalMercExonerada) . '</TotalExonerado>
       ';
        }
        $stringXML .= '<TotalVenta>' . (($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) . '</TotalVenta>
        <TotalDescuentos>' . $TotalDescuentos . '</TotalDescuentos>
        <TotalVentaNeta>' . ((($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) - $TotalDescuentos) . '</TotalVentaNeta>
        <TotalImpuesto>' . $TotalImpuesto . '</TotalImpuesto>
        <TotalComprobante>' . ($TotalImpuesto + ((($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) - $TotalDescuentos)) . '</TotalComprobante>
        </ResumenFactura>
        ';
        if ($data["id_reference_type_document"] != "00") {
            $dr = date('Y-m-d\TH:i:s');
            $stringXML .= '<InformacionReferencia>
            <TipoDoc>' . $data["id_reference_type_document"] . '</TipoDoc>
            <Numero>' . $data["numReference"] . '</Numero>
            <FechaEmision>' . date('Y-m-d\TH:i:s', strtotime($data["date_reference"])) . '</FechaEmision>
            <Codigo>' . $data["code_reference"] . '</Codigo>
            <Razon>' . $data["reason"] . '</Razon>
            </InformacionReferencia>
            ';
        }
        $stringXML .= $this->footer(substr($data["consecutive"], 8, 2));
        return $stringXML;
    }

    public function getKey($data, $company) {
        $tipo_envio = 1;
        $cod_seguridad = 19890717;
        $key = "506" . date("d") . date("m") . date("y") . str_pad($company["id_card"], 12, "0", STR_PAD_LEFT) . $data["consecutive"] . $tipo_envio . $cod_seguridad;
        return $key;
    }

    public function footer($type) {
        $header = "";
        switch ($type) {
            case '01':
                $header = '</FacturaElectronica>';
                break;
            case '02':
                $header = '</NotaDebitoElectronica>';
                break;
            case '03':
                $header = '</NotaCreditoElectronica>';
                break;
            case '04':
                $header = '</TiqueteElectronico>';
                break;
            case '08':
                $header = '</FacturaElectronicaCompra>';
                break;
            case '09':
                $header = '</FacturaElectronicaExportacion>';
                break;
            default:
                $header = '</FacturaElectronica>';
        }
        return $header;
    }

    public function header($type) {
        $header = "";
        switch ($type) {
            case '01':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '02':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaDebitoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaDebitoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '03':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaCreditoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaCreditoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '04':
                $header = '<?xml version="1.0" encoding="utf-8"?><TiqueteElectronico xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/tiqueteElectronico" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '09':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaExportacion xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaExportacion" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '08':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaCompra xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaCompra" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            default:
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        }
        return $header;
    }

   public function tokenMH($company) {
        $url = '';
        $client_secret = "";
        $grant_type = "password";
        //selecccion e acceso a DB
        if (auth()->user()->proof == '1') {
            $client_id = "api-stag";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token";
        } else {
            $client_id = "api-prod";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token";
        }
//Solicitud de un nuevo token
        if ($grant_type == "password") {
            $username = $company["user_mh"];
            $password = $company["pass_mh"];

            //Validation de los datos necesarios
            if ($client_id == '') {
                $result = array("status" => "400", "message" => "El parametro Client ID es requerido");
                return $result;
            } else if ($grant_type == '') {
                $result = array("status" => "400", "message" => "El parametro Grant Type es requerido");
                return $result;
            } else if ($username == '') {
                $result = array("status" => "400", "message" => "El parametro Username es requerido");
                return $result;
            } else if ($password == '') {
                $result = array("status" => "400", "message" => "El parametro Password es requerido");
                return $result;
            }

            //creadcion del array de acceso 
            $data = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => $grant_type,
                'username' => $username,
                'password' => $password
            );
            //refrescand el token
        }

        //creacion del header para la consulta
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        //consulta y resultado
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public function consult($clave, $token) {
        $curl = curl_init();
        if ($clave == "") {
            echo "El valor codigoPais no debe ser vacio";
        }
        $url = null;
        if (auth()->user()->proof == '1') {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/";
        } else {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $clave,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: bf8dc171-5bb7-fa54-7416-56c5cda9bf5c"
            ),
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = array("status" => $status, "message" => "Error:" . $err);
        } else {
            $xml = json_decode($response, true);
            $indEstado = $xml["ind-estado"];
            if (isset($xml["respuesta-xml"])) {
                $xml = $xml["respuesta-xml"];
                $xml = base64_decode($xml);
            } else {
                $xml = "";
            }
            $result = array("status" => $status, "message" => $xml, "ind-estado" => $indEstado);
        }
        return $result;
    }

    public function consultState($clave) {
        $company = Companies::where("companies.id", "=", session('company')->id)
                        ->select('companies.*')->get();
        $token = json_decode($this->tokenMH($company[0]))->access_token;
        $curl = curl_init();
        if ($clave == "") {
            echo "El valor codigoPais no debe ser vacio";
        }
        $url = null;
        if (auth()->user()->proof == '1') {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/";
        } else {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $clave,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: bf8dc171-5bb7-fa54-7416-56c5cda9bf5c"
            ),
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = array("status" => $status, "message" => "Error:" . $err);
        } else {
            $xml = json_decode($response, true);
            $indEstado = $xml["ind-estado"];
            if (isset($xml["respuesta-xml"])) {
                $xml = $xml["respuesta-xml"];
                $xml = base64_decode($xml);
            } else {
                $xml = "";
            }
        }
        if ($xml != "") {
            $document = array();
            $document["state"] = $indEstado;
            $xml = simplexml_load_string(trim($xml));
            if ($indEstado == "rechazado") {
                $document["detail_mh"] = $xml->DetalleMensaje;
            }
            $carpeta = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' .  $clave . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            //agregar envio aqui
            $nombre_fichero = $carpeta . $clave . '-R.xml';
            $xml->asXML($nombre_fichero);
            Expenses::where('key', '=', $clave)->update($document);
        }
        return Redirect::back()->with('message', 'Documento ingrasado con exito!!');
    }

    function send($receptor, $emisor, $clave, $xmlFirmado, $token) {
        $xml64 = base64_encode($xmlFirmado);
        $d = '';
        $d = date('Y-m-d\TH:i:s');

        $datos = array(
            'clave' => trim($clave),
            'fecha' => $d,
            'emisor' => array(
                'tipoIdentificacion' => $receptor["type_id_card_code"],
                'numeroIdentificacion' => $receptor["id_card"]
            ),
            'receptor' => array(
                'tipoIdentificacion' => $emisor["type_id_card_code"],
                'numeroIdentificacion' => $emisor["id_card"]
            ),
            'comprobanteXml' => $xml64
        );
        $mensaje = json_encode($datos);

        $header = array(
            'Authorization: bearer ' . $token,
            'Content-Type: application/json'
        );
        if (auth()->user()->proof == 1) {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion");
        } else {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion");
        }
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);

        $respuesta = curl_exec($curl);

        list($headers, $response) = explode("\r\n\r\n", $respuesta, 2);
// $headers now has a string of the HTTP headers
// $response is the body of the HTTP response
        $h = "";
        $headers = explode("\n", $headers);
        foreach ($headers as $header) {
            if (stripos($header, 'x-error-cause:') !== false) {
                $h = str_replace('x-error-cause: ', "", $header);
                $h = str_replace('\r', "", $h);
            }
        }

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $result = array("status" => $status, "message" => $respuesta, "result" => $h);
        return $result;
    }

    function nextConseutive($type, $id) {
        $cons = Consecutives::where('consecutives.id_branch_offices', $id)->get();
        $consecutive = Array();
        switch ($type) {
            case '01':
                $consecutive["c_fe"] = ($cons[0]["c_fe"] + 1);
                break;
            case '02':
                $consecutive["c_nd"] = ($cons[0]["c_nd"] + 1);
                break;
            case '03':
                $consecutive["c_nc"] = ($cons[0]["c_nc"] + 1);
                break;
            case '04':
                $consecutive["c_te"] = ($cons[0]["c_te"] + 1);
                break;
            case '08':
                $consecutive["c_fc"] = ($cons[0]["c_fc"] + 1);
                break;
            case '09':
                $consecutive["c_fex"] = ($cons[0]["c_fex"] + 1);
                break;
            default:
                $consecutive["c_fe"] = ($cons[0]["c_fe"] + 1);
        }
        Consecutives::where('id_branch_offices', '=', $id)->update($consecutive);
    }
     public function getView($key) {
        $result = array();
        $path='laravel/storage/app/public/Files/recibidos/procesados/'.session('company')->id_card .'/'.$key.'/'.$key.'.xml';
        if (file_exists($path)) {
                $xml = simplexml_load_file($path);
                $result["key"] = (string) $xml->Clave;
                $result["consecutive"] = (string) $xml->NumeroConsecutivo;
                $result["emisor"] = $xml->Emisor;
                $result["receptor"] = $xml->Receptor;
                $result["saleCondition"] = (string)$xml->CondicionVenta;
                $result["paymentMethod"] = (string)$xml->MedioPago;
                $result["detail"] = $xml->DetalleServicio;
                $result["currency"] = "CRC";
                if (isset($xml->ResumenFactura->CodigoTipoMoneda) && (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != null) {
                    $result["currency"] = (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                }
                $result["typeChange"] = (isset($xml->ResumenFactura->CodigoTipoMoneda->TipoCambio))?(string)$xml->ResumenFactura->CodigoTipoMoneda->TipoCambio:1;
                $result["discount"] = 0;
                if ((string) $xml->ResumenFactura->TotalDescuentos != null) {
                    $result["discount"] = (string) $xml->ResumenFactura->TotalDescuentos;
                }
                $result["exoneration"] = 0;
                if ((string) $xml->ResumenFactura->TotalExonerado != null) {
                    $result["exoneration"] = (string) $xml->ResumenFactura->TotalExonerado;
                }
                $result["subTotal"]  = (string) $xml->ResumenFactura->TotalVenta;
                $result["tax"]  = (string) $xml->ResumenFactura->TotalImpuesto;
                $result["total"]  = (string) $xml->ResumenFactura->TotalComprobante;
                $result["date"]  = (string) $xml->FechaEmision;
                $result["otrosCargos"] = (isset($xml->OtrosCargos))?$xml->OtrosCargos:"";
                return $result;
        }else{
            return 0;
        }
        
     }
     public function changeCategory($value, $id){
         $exp["category"] = $value;
         return Expenses::where('id', '=', $id)->update($exp);
     }
 public function getAccount($dataService,$data)
	    {
	      try{
	            // Run a query
	            $entities = $dataService->Query("select * from Account where Name = '".$data."'");
	            $error = $dataService->getLastError();
	            if ($error) {
	                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
	                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
	                echo "The Response message is: " . $error->getResponseBody() . "\n";
	            }
	            // Echo some formatted output
	            return json_encode($entities);
	            
	        }
	        catch(Exception $e)
	        {
	            die($e->getMessage());
	        }
	    }

    public function saveEQB($key){
        $config = include('config.php');
        try {
          $oauth2LoginHelper = new OAuth2LoginHelper($config['client_id'], $config['client_secret']);
            $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken(session('company')->token);
            $this->saveToken(session('company')->realmId, $accessTokenObj->getRefreshToken());
            $comp = DB::table('companies')
                ->join('users_companies', 'companies.id', '=', 'users_companies.id_company')
                ->select('companies.*', 'users_companies.roll')
                ->where('companies.id', session('company')->id)->get();
            session('company', $comp[0]);
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' => $config['client_secret'],
                'RedirectURI' => $config['oauth_redirect_uri'],
                'accessTokenKey' => $accessTokenObj->getAccessToken(),
                'refreshTokenKey' => $accessTokenObj->getRefreshToken(),
                'QBORealmID' => session('company')->realmId,
                'baseUrl' => "production"
            ));
        //abrir xml
            $carpeta = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key . '/';
            $nombre_fichero = $carpeta . $key . '.xml';
        if ($xml = simplexml_load_file($carpeta. '/' . basename($key . '.xml'))) { 
            $mon = (isset($xml->ResumenFactura->CodigoTipoMoneda))?''.$xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda:"CRC";
            //vendor
            $vendor = "";
            $vendors = $dataService->Query("select * from Vendor where active = true and CompanyName like '%" . $xml->Emisor->Identificacion->Numero . "%'");
            if(isset($vendors)){
                foreach ($vendors as $c) {
                    if (isset($c->AlternatePhone)) {
                        $idcard = str_replace(["R", "-", " "], ["", "", ""], $c->CompanyName);
                        if ($idcard == $xml->Emisor->Identificacion->Numero && $c->CurrencyRef== $mon) {
                            $vendor = $c->Id;
                            break;
                        }
                    }
                }
            }
            if ($vendor == "" || $vendor == null) {
                $vendor = $this->createVendor($xml, $dataService);
            }
            // Run a query
                $accountId = $this->getAccount($dataService, "Gasto sin clasificar");
                $accountId = json_decode($accountId, true);
                $accountId = $accountId[0]['Id'];

                $AccountRef = array(
                    "value" => $accountId
                );
                $AccountBasedExpenseLineDetail = array(
                    "AccountRef" => $AccountRef
                );
                $line = array();
                $countL = 1;
                foreach ($xml->DetalleServicio->LineaDetalle as $l) {
                    $lineData = array(
                        "Id" => (string) $countL++,
                        "Description" => (string)$l->Detalle,
                        "Amount" => (string)$l->MontoTotalLinea,
                        "DetailType" => "AccountBasedExpenseLineDetail",
                        "AccountBasedExpenseLineDetail" => $AccountBasedExpenseLineDetail
                    );
                    array_push($line, $lineData);
                }
                if(isset($xml->OtrosCargos)){
                    foreach ($xml->OtrosCargos as $o) {
                        $lineData = array(
                            "Id" => $countL++,
                            "Description" =>(string) $o->Detalle,
                            "Amount" => (string) $o->MontoCargo,
                            "DetailType" => "AccountBasedExpenseLineDetail",
                            "AccountBasedExpenseLineDetail" => $AccountBasedExpenseLineDetail
                        );
                        array_push($line, $lineData);
                    }
                }
                $vendor = array(
                    "Value" => $vendor
                );
                $ExchangeRate = 1;
                if (isset($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)) {
                    if ($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda == "USD") {
                        $ExchangeRate = $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio;
                        $CurrencyRef = array(
                            "value" => "USD",
                            "name" => "United States Dollar"
                            
                        );
                        
                    } else {
                        $ExchangeRate = 1;
                        $CurrencyRef = array(
                            "value" => "CRC",
                            "name" => "Colón costarricense"
                        );
                    }
                } else {
                    $ExchangeRate = 1;
                    $CurrencyRef = array(
                        "value" => "CRC",
                        "name" => "Colón costarricense"
                    );
                }
                $bill = array(
                    "TxnDate" => substr($xml->FechaEmision, 0, 10),
                    "CurrencyRef" => $CurrencyRef,
                    "ExchangeRate" =>round($ExchangeRate,1),
                    "Line" => $line,
                    "VendorRef" => $vendor,
                    "DocNumber" => '' . $xml->NumeroConsecutivo
                );
                if (substr($xml->NumeroConsecutivo, 8, 2) == '03') {
                    $typeDoc = 3;
                    $theResourceObj =  VendorCredit::create($bill);
                } else {
                    $typeDoc = 1;
                    $theResourceObj =  Bill::create($bill);
                }
                $resultingObj = $dataService->Add($theResourceObj);
                $error = $dataService->getLastError();
                if ($error != null) {
                    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    echo "The Response message is: " . $error->getResponseBody() . "\n";
                    return $result = array("status" => "400", "message" => $error->getResponseBody());
                }else{
                    $expense = ["qb"=>"guardado"];
                    Expenses::where('key', '=', $key)->update($expense);
                }
                $dataService->throwExceptionOnError(true);
        }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $this->uploadPDF($dataService, $xml, $resultingObj->Id, $typeDoc); //funciona bien
        $this->uploadXML($dataService, $xml, $resultingObj->Id, $typeDoc); //funciona bien
        $this->uploadXMLR($dataService, $xml, $resultingObj->Id, $typeDoc);
        return Redirect::back()->with('message', 'Documento ingrasado con exito!!');
    }
    public function createVendor($xml, $dataService){
        $id ="";
        try {
           $mon = (isset($xml->ResumenFactura->CodigoTipoMoneda))?''.$xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda:"CRC";
            $nmon = ($mon == "CRC")?"Colón costarricense":"Dólar estadounidense";
            $num = ($xml->Emisor->Identificacion->Tipo == "01")?$xml->Emisor->Identificacion->Numero:"R".$xml->Receptor->Identificacion->Numero;
            $name = str_replace(["´","&"],["",""],$xml->Emisor->Nombre);
            $theResourceObj = Vendor::create([
                    "DisplayName" => $name ." PR ".$mon ,
                    "CurrencyRef" =>[
                        "value"=> $mon
                        ],
                       "BillAddr" => [		       
            		        "Country" => "COSTA RICA"
            		    ],
            		    "TaxIdentifier" =>"".$xml->Emisor->Identificacion->Numero,
                        "CompanyName" => "".$xml->Emisor->Identificacion->Numero,
                        
                        "PrintOnCheckName" => ''.$xml->Emisor->Nombre.' '.$mon,
                        "PrimaryPhone" => [
                            "FreeFormNumber" => "".$xml->Emisor->Telefono->NumTelefono
                        ],
                        "AlternatePhone" => [
                            "FreeFormNumber" => "".$xml->Emisor->Identificacion->Numero
                        ],
                        "PrimaryEmailAddr" => [
                            "Address" => "".$xml->Emisor->CorreoElectronico
                        ]
            ]);
            $resultingObj = $dataService->Add($theResourceObj);
            $error = $dataService->getLastError();
            if ($error) {
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";
            } else {
                return $resultingObj->Id;
            }
        } catch (Exception $e) {
            echo ($e->getMessage());
        }
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function saveToken($realmId, $token) {
        $c = array();
        $c["token"]=$token;
        Companies::where('realmId', '=', $realmId)->update($c);
        return 1;
    }
   public function uploadXML($dataService,$xml,$id,$type)    {
        
	    $path = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'.xml';
	    $randId = $xml->Clave;
	        try
	        {
        		$xmlBase64['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = $xml;		
        		$sendMimeType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";	
        		
        		// Create a new IPPAttachable
        		if($type==1){
        		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Bill'));
        		}
        		if($type==3){
        		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'VendorCredit'));
        		}
        		$attachableRef = new IPPAttachableRef(array('EntityRef'=>$entityRef));
        		$objAttachable = new IPPAttachable();
        		$objAttachable->FileName ="FA-".$randId.".xml";
        		$objAttachable->AttachableRef = $attachableRef;
        		
        		// Upload the attachment to the Bill
        		$resultObj = $dataService->Upload(base64_encode (file_get_contents($path)),
        		                                  $objAttachable->FileName,
        		                                  $sendMimeType,
        		                                  $objAttachable);
        		$error = $dataService->getLastError();
        			if ($error) {			  
        			   return $error;
        			}
        			else {
        			   return 'insertado';
        			}
        	        }
        	        catch(Exception $e)
        	        {
        	            die($e->getMessage());
        	        }
	    }//fin uploadxml
	    public function uploadXMLR($dataService,$xml,$id,$type)
	    { 
	      
	      $path = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'-R.xml';	 
	      if(file_exists($path)){   
	        try
	        {
	       // Prepare entities for attachment upload				
        		$sendMimeType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";	
		
		// Create a new IPPAttachable
		$randId = $xml->Clave;
		if($type==1){
		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Bill'));
		}
		if($type==3){
		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'VendorCredit'));
		}
		$attachableRef = new IPPAttachableRef(array('EntityRef'=>$entityRef));
		$objAttachable = new IPPAttachable();
		$objAttachable->FileName ="FA-Respuesta".$randId.".xml";
		$objAttachable->AttachableRef = $attachableRef;
		
		// Upload the attachment to the Bill
		$resultObj = $dataService->Upload(base64_encode (file_get_contents($path)),
		                                  $objAttachable->FileName,
		                                  $sendMimeType,
		                                  $objAttachable);
		$error = $dataService->getLastError();
			if ($error) {
			   return $error;
			}
			else {
			   return 'insertado';
			}
	        }
	        catch(Exception $e)
	        {
	            die($e->getMessage());
	        }
	    }}
	    public function uploadPDF($dataService,$xml,$id,$type)
	    { 	  
	      
	      $path = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'.pdf';	 
	      if(file_exists($path)){
	        try
	        {				
    		$sendMimeType = "application/pdf";		
    		
    		// Create a new IPPAttachable
    		$randId = $xml->Clave;
    		if($type==1){
		        $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Bill'));
    		}
    		if($type==3){
    		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'VendorCredit'));
    		}
    		$attachableRef = new IPPAttachableRef(array('EntityRef'=>$entityRef));
    		$objAttachable = new IPPAttachable();
    		$objAttachable->FileName ="FA-".$randId.".pdf";
    		$objAttachable->AttachableRef = $attachableRef;
    		
    		// Upload the attachment to the Bill
    		$resultObj = $dataService->Upload(base64_encode (file_get_contents($path)),
    		                                  $objAttachable->FileName,
    		                                  $sendMimeType,
    		                                  $objAttachable);
    		$error = $dataService->getLastError();
			if ($error) {
			   return $error;
			}
			else {
			   return 'insertado';
			}
	        }
	        catch(Exception $e)
	        {
	            die($e->getMessage());
	        }
        }
	    }
}
