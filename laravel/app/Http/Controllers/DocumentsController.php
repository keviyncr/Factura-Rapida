<?php

namespace App\Http\Controllers;
use App\Hacienda\Firmador;
use Illuminate\Support\Facades\Redirect;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use App\Documents;
use App\References;
use App\Taxes;
use App\Consecutives;
use App\Exonerations;
use App\TypeDocumentExonerations;
use App\TaxesCode;
use App\RateCode;
use App\Clients;
use App\Products;
use App\EconomicActivities;
use App\SaleConditions;
use App\PaymentMethods;
use App\Currencies;
use App\Companies;
use App\Expenses;
use App\TypeDocumentOtherCharge;
use App\BranchOffices;
use App\ReferenceTypeDocuments;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use App\SimpleXLSX;
use Dompdf\Dompdf;

class DocumentsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }
    public function printTicket($key) {
        $result = array();
        $path='laravel/storage/app/public/Files/creados/'.session('company')->id_card .'/'.$key.'/'.$key.'.xml';
        if (file_exists($path)) {
                $xml = simplexml_load_file($path);
                $result["key"] = (string) $xml->Clave;
                $result["consecutive"] = (string) $xml->NumeroConsecutivo;
                $result["emisor"] = $xml->Emisor->Nombre;
                $result["telefono"] = $xml->Emisor->Telefono->NumTelefono;
                $result["detail"] = $xml->DetalleServicio;
                $result["typeDoc"] = $this->typeDoc(substr($result["consecutive"],8,2));
                $result["subTotal"]  = (string) $xml->ResumenFactura->TotalVenta;
                $result["tax"]  = (isset($xml->ResumenFactura->TotalImpuesto))?(string) $xml->ResumenFactura->TotalImpuesto:0;
                $result["desc"]  = (isset($xml->ResumenFactura->TotalDescuentos))?(string) $xml->ResumenFactura->TotalDescuentos:0;
                $result["total"]  = (string) $xml->ResumenFactura->TotalComprobante;
                $result["date"]  = (string) $xml->FechaEmision;
                $result["otrosCargos"] = (isset($xml->OtrosCargos))?$xml->OtrosCargos:"";
                
        }else{
            
           echo "Documento XML no encontrado";
           return 0;
        }
        $dompdf = new Dompdf();
        $dompdf->setPaper('b7', 'portrait');
        ob_start();
        include "generar_ticket.php";
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->render();
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=documento.pdf");
        // Output the generated PDF (1 = download and 0 = preview)
        $dompdf->stream($result["key"].".pdf",array("Attachment"=>0));
        //echo $dompdf->output();
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
        $datos ['products'] = Products::where('products.id_company', session('company')->id)->get();
        $datos ['clients'] = Clients::where('clients.id_company', session('company')->id)->
                        where('active', '1')->get();
        if(isset($_GET['f1']) && isset($_GET['f2'])){  
            $datos ['documents'] = DB::table('documents')
            ->join('clients', 'clients.id', '=', 'documents.id_client')
            ->select('documents.*', 'clients.name_client as client','clients.emails as client_mail')
            ->where('documents.id_company', session('company')->id)
            ->whereBetween("documents.created_at",[$_GET['f1'],$_GET['f2']])
            ->orderBy('created_at', 'DESC')->get();
            $datos["f1"] = $_GET['f1'];
            $datos["f2"] = $_GET['f2'];
        }else{
            $datos["f1"] =  \Carbon\Carbon::now()->subDays(30)->format("Y-m-d");; 
            $datos["f2"] = date("Y-m-d"); 
            $datos ['documents'] = DB::table('documents')
            ->join('clients', 'clients.id', '=', 'documents.id_client')
            ->select('documents.*', 'clients.name_client as client','clients.emails as client_mail')
            ->where('documents.id_company', session('company')->id)
            ->where("documents.created_at", '>=', $datos["f1"])
            ->orderBy('created_at', 'DESC')->get();
        }
        $datos['allTypeDocumentOtherCharges'] = TypeDocumentOtherCharge::all();
        $datos ['sale_conditions'] = SaleConditions::all();
        $datos ['payment_methods'] = PaymentMethods::all();
        $datos ['reference_type_documents'] = ReferenceTypeDocuments::all();
        $datos ['reference_codes'] = References::all();
        $datos ['currencies'] = Currencies::all();
        $datos["today"] = \Carbon\Carbon::now()->format("Y/m/d");
        $datos ['branch_offices'] = BranchOffices::where("id_company", "=", session('company')->id)->get();
        $datos ["bo"] = $datos ['branch_offices'][0]["id"];
        return view('company.document', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }
public function typeDoc($type) {
        $header = "";
        switch ($type) {
            case '01':
                $header = 'FacturaElectronica';
                break;
            case '02':
                $header = 'NotaDebitoElectronica';
                break;
            case '03':
                $header = 'NotaCreditoElectronica';
                break;
            case '04':
                $header = 'TiqueteElectronico';
                break;
            case '09':
                $header = 'FacturaElectronicaExportacion';
                break;
            default:
                $header = 'FacturaElectronica';
        }
        return $header;
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
        $client = Clients::where("clients.id", "=", $p["id_client"])
                ->join('type_id_cards', 'type_id_cards.id', '=', 'clients.type_id_card')
                ->join('provinces', 'provinces.id', '=', 'clients.id_province')
                ->join('cantons', 'cantons.id', '=', 'clients.id_canton')
                ->join('districts', 'districts.id', '=', 'clients.id_district')
                ->join('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
                ->select('clients.*', 'provinces.code as province_code', 'cantons.code as canton_code', 'districts.code as district_code',
                        'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict', 'country_codes.phone_code as phone_code', 'type_id_cards.code as type_id_card_code')
                ->get();
        $p["id_company"] = session('company')->id;
        $p["e_a"] = EconomicActivities::findOrFail($p["id_ea"])["number"];
        $p["sc"] = SaleConditions::findOrFail($p["id_sale_condition"])["sale_condition"];
        $p["currency"] = Currencies::findOrFail($p["id_currency"])["code"];
        $p["pm"] = PaymentMethods::findOrFail($p["id_payment_method"])["payment_method"];
        $p["key"] = $this->getKey($p, $company[0]);
        $p["detail_mh"] = "Ninguno";
        $p["answer_mh"] = "Procesando";
        $p["state_send"] = "No enviado";

        // Start transaction!
        DB::beginTransaction();
        try {

            $saveXML = $this->invoiceXML($p, $company[0], $bo[0], $client[0]);
            //firmar Documento
            $firmador = new Firmador();
           
            $XMLFirmado = $firmador->firmarXml('laravel/storage/app/public/' . session('company')->cryptographic_key, session('company')->pin, trim($saveXML), $firmador::TO_XML_STRING);
            $saveXML = simplexml_load_string(trim($XMLFirmado));
            $carpeta = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $saveXML->Clave . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            
            //agregar envio aqui
            $nombre_fichero = $carpeta . $saveXML->Clave . '.xml';
            $saveXML->asXML($nombre_fichero);
            $p["ruta"] = 'Files/creados/' . session('company')->id_card . '/' . $saveXML->Clave . '/';      
            $token = $this->tokenMH($company[0]);     
            $result = $this->send($company[0], $client[0], $saveXML->Clave, $XMLFirmado, json_decode($token)->access_token);

            if(isset($saveXML->InformacionReferencia)){
                $p["reference"] = $saveXML->InformacionReferencia->Numero;
            }
            if ($result["status"] == "202" || $result["status"] == "200") {
                $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                if ($result["ind-estado"] != "") {
                    $p["answer_mh"] = $result["ind-estado"];
                    if ($p["answer_mh"] != "procesando") {
                        $xml = simplexml_load_string(trim($result["message"]));
                        if ($p["answer_mh"] == "rechazado") {
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
                $p["answer_mh"] = "rechazado";
                $p["detail_mh"] = $result["result"];
                $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                if ($result["ind-estado"] != "") {
                    $p["answer_mh"] = $result["ind-estado"];
                    if ($p["answer_mh"] != "procesando") {
                        $xml = simplexml_load_string(trim($result["message"]));
                        if ($p["answer_mh"] == "rechazado") {
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
           
            // Validate, then create if valid 
           // echo json_encode($p);
           $p["iva_returned"] =(isset($p["ivareturn"]))?$p["ivareturn"]:0;
            $doc = Documents::create($p);
            $this->generatePDF($company[0], $bo[0], $client[0], $p, $doc);
            $this->nextConseutive(substr($p["consecutive"], 8, 2), $bo[0]["id"]);
        } catch (ValidationException $e) {
            // back to form with errors
            DB::rollback();
            echo $e->getErrors();
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
        DB::commit();
        //return Redirect::back()->with('message', 'Documento ingrasado con exito!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Documents  $documents
     * @return \Illuminate\Http\Response
     */
    public function show(Documents $documents) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documents  $documents
     * @return \Illuminate\Http\Response
     */
    public function edit(Documents $documents) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documents  $documents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $p = $request->except("_token", "_method");
        Documents::where('id', '=', $id)->update($p);
        return Redirect::back()->with(['message' => 'Documento actualizado con exito!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documents  $documents
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function getKey($data, $company) {
        $tipo_envio = 1;
        $cod_seguridad = 19890717;
        $key = "506" . date("d") . date("m") . date("y") . str_pad($company["id_card"], 12, "0", STR_PAD_LEFT) . $data["consecutive"] . $tipo_envio . $cod_seguridad;
        return $key;
    }

    public function invoiceXML($data, $company, $bo, $client) {
        $d = date('Y-m-d\TH:i:s');
        $stringXML = '';
        $stringXML .= $this->header(substr($data["consecutive"], 8, 2));
        $stringXML .= '
            <Clave>' . $data["key"] . '</Clave>
            <CodigoActividad>' . str_pad($data["e_a"], 6, "0", STR_PAD_LEFT) . '</CodigoActividad>
            <NumeroConsecutivo>' . $data["consecutive"] . '</NumeroConsecutivo>
            <FechaEmision>' . $d . '</FechaEmision>
            <Emisor>
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
            <NumTelefono>' . $bo["phone"] . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $bo["emails"] . '</CorreoElectronico>
            </Emisor>
            ';
        if ($client != "" && $client["id_card"] != '000000000') {
            if (substr($data["consecutive"], 8, 2) != '09') {
                if(substr($data["consecutive"], 8, 2) == '04' && $client["id_card"]=="000000000" ){}else{
                    $stringXML .= '<Receptor>
                    <Nombre>' . $client["name_client"] . '</Nombre>
                    <Identificacion>
                    <Tipo>' . $client["type_id_card_code"] . '</Tipo>
                    <Numero>' . $client["id_card"] . '</Numero>
                    </Identificacion>
                    <NombreComercial>' . $client["name_client"] . '</NombreComercial>
                    <Ubicacion>
                    <Provincia>' . $client["province_code"] . '</Provincia>
                    <Canton>' . $client["canton_code"] . '</Canton>
                    <Distrito>' . $client["district_code"] . '</Distrito>
                    <OtrasSenas>' . $client["other_signs"] . '</OtrasSenas>
                    </Ubicacion>
                    <Telefono>
                    <CodigoPais>' . $client["phone_code"] . '</CodigoPais>
                    <NumTelefono>' . $client["phone"] . '</NumTelefono>
                    </Telefono>
                    <CorreoElectronico>' . $client["emails"] . '</CorreoElectronico>
                    </Receptor>
                    ';
                }
            } else {
                $stringXML .= '<Receptor>
                <Nombre>' . $client["name_company"] . '</Nombre>
                <IdentificacionExtranjero>' . $client["id_card"] . '</IdentificacionExtranjero>
                <OtrasSenasExtranjero>' . $client["other_signs"] . '</OtrasSenasExtranjero>
                </Receptor>
                ';
            }
        }
        $stringXML .= '<CondicionVenta>' . SaleConditions::findOrFail($data["id_sale_condition"])['code'] . '</CondicionVenta>';
        if($data["time"]>0){
            $stringXML .= '<PlazoCredito>' . $data["time"] . '</PlazoCredito>';
        }
            $stringXML .= '<MedioPago>' . PaymentMethods::findOrFail($data["id_payment_method"])['code'] . '</MedioPago>        
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
        $exoneracionAcumulada = 0;
        foreach ($data['details'] as $detail) {
            $stringXML .= '<LineaDetalle>
            <NumeroLinea>' . $detail[0] . '</NumeroLinea>
            <Codigo>' . $detail[2] . '</Codigo>
            ';
            $product = Products::findOrFail($detail[11]);
            if (substr($data["consecutive"], 8, 2) == '09' && ($detail[4] != "Sp" && $detail[4] != "Spe")) {
                $stringXML .= '<PartidaArancelaria>' . $product["tariff_heading"] . '</PartidaArancelaria>
                 ';
            }
             if($product["internal_code"] != "0" && $product["internal_code"] != ""){
                    $stringXML .= '<CodigoComercial>
                        <Tipo>'.$product["type_internal_code"].'</Tipo>
                        <Codigo>'.$product["internal_code"].'</Codigo>
                    </CodigoComercial>
                    '; 
                }
            $stringXML .= '<Cantidad>' . $detail[5] . '</Cantidad>
            <UnidadMedida>' . $detail[4] . '</UnidadMedida>
            <Detalle>' . str_replace("&","_",$detail[3]) . '</Detalle>
            <PrecioUnitario>' . $detail[6] . '</PrecioUnitario>
            <MontoTotal>' . $detail[6] * $detail[5] . '</MontoTotal>
            ';
            $discount = 0;
            if ($detail[6] != 0 && $detail[7] != "") {
                $discount = $detail[7];
                $stringXML .= '<Descuento>
                <MontoDescuento>' . $discount . '</MontoDescuento>
                <NaturalezaDescuento>Descuento General a este producto</NaturalezaDescuento>
                </Descuento>
                ';
                $TotalDescuentos = $TotalDescuentos + $discount;
            }
            $subTotal = (($detail[5] * $detail[6]) - $discount);
            $stringXML .= '<SubTotal>' . $subTotal . '</SubTotal>
            ';
            $impNeto = 0;

            if ($product["ids_taxes"] != "" && $product["ids_taxes"] != null) {
                $temp = "";
                $totalImp = 0;
                $exoTotal = 0;
                if ($product["tax_base"] != "0" && $product["tax_base"] != null) {
                    $stringXML .= '<BaseImponible>' . $product["tax_base"] . '</BaseImponible>                                ';
                }
                foreach (json_decode($product["ids_taxes"]) as $idTax) {
                    $tax = Taxes::findOrFail($idTax);
                    $taxesCode = TaxesCode::findOrFail($tax["id_taxes_code"]);
                    if ($taxesCode["code"] == "01") {
                        $temp = $tax;
                    } else {
                        $stringXML .= '<Impuesto>
                    <Codigo>' . $taxesCode["code"] . '</Codigo>
                    ';
                        if ($taxesCode["code"] == "07") {
                            $rateCode = RateCode::findOrFail($tax["id_rate_code"]);
                            $stringXML .= '<CodigoTarifa>' . $rateCode["code"] . '</CodigoTarifa>
                                ';
                        }
                        $montoImp = 0;
                        if ($product["tax_base"] != "0" && $product["tax_base"] != null) {
                            $montoImp = ($tax["rate"] * $product["tax_base"] / 100);
                            $stringXML .= '<Tarifa>' . $montoImp . '</Tarifa>
                            <Monto>' . round($montoImp,5) . '</Monto>
                        ';
                        } else if ($tax["rateIVA"] != "" && $tax["rateIVA"] != null) {
                            $montoImp = $subTotal * ($tax["rateIVA"] - 1);
                            $stringXML .= '<FactorIVA>' . $tax["rateIVA"] . '</FactorIVA>
                        <Monto>' . round($montoImp,5) . '</Monto>
                        ';
                        } else {
                            //$rateCode = Rate_Code::findOrFail($tax["id_rate_code"]);
                            $montoImp = ($tax["rate"] * $subTotal) / 100;
                            $stringXML .= '<Tarifa>' . $tax["rate"] . '</Tarifa>   
                            <Monto>' . round($montoImp,5) . '</Monto>
                        ';
                        }
                        if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                            $exo = Exonerations::findOrFail($tax["id_exoneration"]);
                            $tde = TypeDocumentExonerations::findOrFail($exo["id_type_document_exoneration"]);
                            $fechaDoc = \Carbon\Carbon::parse($exo["date"])->format('Y-m-d\TH:i:s');
                            $exoTotal = $exoTotal + $exo["exemption_percentage"];
                            $stringXML .= '<Exoneracion>
                        <TipoDocumento>' . str_pad($tde["code"], 2, "0", STR_PAD_LEFT) . '</TipoDocumento>
                        <NumeroDocumento>' . $exo["document_number"] . '</NumeroDocumento>
                        <NombreInstitucion>' . $exo["institutional_name"] . '</NombreInstitucion>
                        <FechaEmision>' . $fechaDoc . '</FechaEmision>
                        <PorcentajeExoneracion>' . $exo["exemption_percentage"] . '</PorcentajeExoneracion>
                        <MontoExoneracion>' . ($subTotal * $exo["exemption_percentage"] / 100) . '</MontoExoneracion>
                        </Exoneracion>
                        </Impuesto>
                        ';
                            $exoneracionAcumulada = $exoneracionAcumulada + ($subTotal * $exo["exemption_percentage"] / 100);
                            $impNeto = $impNeto + ( $montoImp - ($subTotal * $exo["exemption_percentage"] / 100));
                        } else {
                            $stringXML .= '</Impuesto>
                        ';
                        }
                        $totalImp = $totalImp + $montoImp;
                    }
                }
                if ($temp != "") {
                    $tax = $temp;
                    $taxesCode = TaxesCode::findOrFail($tax["id_taxes_code"]);

                    $stringXML .= '<Impuesto>
                    <Codigo>' . $taxesCode["code"] . '</Codigo>
                    ';
                    $montoImp = 0;
                    if ($product["tax_base"] != "0" && $product["tax_base"] != null) {
                        $montoImp = ($tax["rate"] * (($product["tax_base"] + $totalImp) - $exoneracionAcumulada) / 100);
                        $rateCode = RateCode::findOrFail($tax["id_rate_code"]);
                        $stringXML .= '<CodigoTarifa>' . $rateCode["code"] . '</CodigoTarifa>
                            <Tarifa>' . $tax["rate"] . '</Tarifa>                            
                            <Monto>' . round($montoImp,5) . '</Monto>
                        ';
                    } else {
                        $montoImp = ($tax["rate"] * (($subTotal + $totalImp) - $exoneracionAcumulada) / 100);
                        $rateCode = RateCode::findOrFail($tax["id_rate_code"]);
                        $stringXML .= '<CodigoTarifa>' . $rateCode["code"] . '</CodigoTarifa>
                            <Tarifa>' . $tax["rate"] . '</Tarifa> 
                            <Monto>' . round($montoImp,5) . '</Monto>
                        ';
                    }
                    if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                        $exo = Exonerations::findOrFail($tax["id_exoneration"]);
                        $tde = TypeDocumentExonerations::findOrFail($exo["id_type_document_exoneration"]);
                        $fechaDoc = \Carbon\Carbon::parse($exo["date"])->format('Y-m-d\TH:i:s');
                        $exoTotal = $exoTotal + $exo["exemption_percentage"];
                        $stringXML .= '<Exoneracion>
                        <TipoDocumento>' . str_pad($tde["code"], 2, "0", STR_PAD_LEFT) . '</TipoDocumento>
                        <NumeroDocumento>' . $exo["document_number"] . '</NumeroDocumento>
                        <NombreInstitucion>' . $exo["institutional_name"] . '</NombreInstitucion>
                        <FechaEmision>' . $fechaDoc . '</FechaEmision>
                        <PorcentajeExoneracion>' . $exo["exemption_percentage"] . '</PorcentajeExoneracion>
                        <MontoExoneracion>' . ($subTotal * $exo["exemption_percentage"] / 100) . '</MontoExoneracion>
                        </Exoneracion>
                        </Impuesto>
                        ';
                        $impNeto = $impNeto + ( $montoImp - ($subTotal * $exo["exemption_percentage"] / 100));
                    } else {
                        $stringXML .= '</Impuesto>
                        ';
                    }
                    $totalImp = $totalImp + $montoImp;
                    if ($detail[4] == 'Spe' || $detail[4] == 'Sp') {
                        if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                            $TotalServExonerado = $TotalServExonerado + ($detail[6] * $detail[5]);
                        }
                    } else {
                        if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                            $TotalMercExonerada = $TotalMercExonerada + ($detail[6] * $detail[5]);
                        }
                    }
                }
                if ($detail[4] == 'Spe' || $detail[4] == 'Sp') {
                    if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                       
                    } else {
                        $TotalServGravados = $TotalServGravados + ($detail[6] * $detail[5]);
                    }
                } else {
                    if ($tax["id_exoneration"] != "" && $tax["id_exoneration"] != null) {
                      
                    } else {
                        $TotalMercanciasGravadas = $TotalMercanciasGravadas + ($detail[6] * $detail[5]);
                    }
                }
            } else {
                if ($detail[4] == 'Spe' || $detail[4] == 'Sp') {
                    $TotalServExentos = $TotalServExentos + ($detail[6] * $detail[5]);
                } else {
                    $TotalMercanciasExentas = $TotalMercanciasExentas + ($detail[6] * $detail[5]);
                }
            }
            $stringXML .= '<ImpuestoNeto>' . $impNeto . '</ImpuestoNeto>
            <MontoTotalLinea>' . ($detail[10]) . '</MontoTotalLinea>
            </LineaDetalle>';
            $TotalImpuesto = $TotalImpuesto + ($detail[8] - $detail[9]);
        }
        $stringXML .= '</DetalleServicio>
        ';
        $totalOtherCharges = 0;
        if ($data['others']) {
            foreach ($data['others'] as $otherCharge) {             
                $stringXML .= '<OtrosCargos>
                <TipoDocumento>' .str_pad($otherCharge["6"], 2, "0", STR_PAD_LEFT). '</TipoDocumento>
                <NumeroIdentidadTercero>' . $otherCharge["2"] . '</NumeroIdentidadTercero>
                <NombreTercero>' . $otherCharge["3"] . '</NombreTercero>
                <Detalle>' . $otherCharge["4"] . '</Detalle>
                <MontoCargo>' . $otherCharge["5"] . '</MontoCargo>
                </OtrosCargos>
                ';
                 $totalOtherCharges += $otherCharge["5"];
            }
        }
         $stringXML .= '<ResumenFactura>
        ';
        if ($data["id_currency"] != "55") {
            $currency = Currencies::findOrFail($data["id_currency"]);
            $stringXML .= '<CodigoTipoMoneda>
        <CodigoMoneda>' . $currency["code"] . '</CodigoMoneda>
        <TipoCambio>' . $data["type_change"] . '</TipoCambio>
        </CodigoTipoMoneda>
        ';
        }
        $stringXML .= '<TotalServGravados>' . $TotalServGravados . '</TotalServGravados>
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
        
        $ivareturn = (isset($data["ivareturn"]))?$data["ivareturn"]:0;
        $stringXML .= '<TotalVenta>' . (($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) . '</TotalVenta>
        <TotalDescuentos>' . $TotalDescuentos . '</TotalDescuentos>
        <TotalVentaNeta>' . ((($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) - $TotalDescuentos) . '</TotalVentaNeta>
         <TotalImpuesto>' . $TotalImpuesto . '</TotalImpuesto>
        <TotalIVADevuelto>' . $ivareturn . '</TotalIVADevuelto>
         <TotalOtrosCargos>' . $totalOtherCharges . '</TotalOtrosCargos>
        <TotalComprobante>' . (($TotalImpuesto + ((($TotalServGravados + $TotalMercanciasGravadas) + ($TotalServExentos + $TotalMercanciasExentas) + ($TotalServExonerado + $TotalMercExonerada)) - $TotalDescuentos))-$ivareturn + $totalOtherCharges) . '</TotalComprobante>
        </ResumenFactura>
        ';
        if (isset($data["id_reference_type_document"])) {
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
        if ($data["gln"] != '' && $data["order"] != '') {
            $stringXML .= '<Otros>
            <OtroContenido>
                <CompraEntrega xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.gs1cr.org/esquemas/CompraEntrega/" xsi:schemaLocation="http://www.gs1cr.org/esquemas/CompraEntrega/   http://www.gs1cr.org/esquemas/CompraEntrega/CR_GS1_CompraEntrega_V3_0.xsd">
                    <EnviarGLN>' . $data["gln"] . '</EnviarGLN>
                    <NumeroOrden>' . $data["order"] . '</NumeroOrden>
                </CompraEntrega>
            </OtroContenido>
            </Otros>
            ';
        }
        $stringXML .= $this->footer(substr($data["consecutive"], 8, 2));
        return $stringXML;
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
            default:
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        }
        return $header;
    }

    function send($emisor, $receptor, $clave, $xmlFirmado, $token) {
        $xml64 = base64_encode($xmlFirmado);
        $d = '';
        $d = date('Y-m-d\TH:i:s');

        $datos = array(
            'clave' => trim($clave),
            'fecha' => $d,
            'emisor' => array(
                'tipoIdentificacion' => $emisor["type_id_card_code"],
                'numeroIdentificacion' => $emisor["id_card"]
            ),
            'receptor' => array(
                'tipoIdentificacion' => $receptor["type_id_card_code"],
                'numeroIdentificacion' => $receptor["id_card"]
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
            $document["answer_mh"] = $indEstado;
            $xml = simplexml_load_string(trim($xml));
            if ($indEstado == "rechazado") {
                $document["detail_mh"] = $xml->DetalleMensaje;
            }
             $carpeta = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' .  $clave . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            //agregar envio aqui
            $nombre_fichero = $carpeta . $clave . '-R.xml';
            $xml->asXML($nombre_fichero);
            Documents::where('key', '=', $clave)->update($document);
        }
        return Redirect::back()->with('message', 'Documento ingrasado con exito!!');
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
            case '09':
                $consecutive["c_fex"] = ($cons[0]["c_fex"] + 1);
                break;
            default:
                $consecutive["c_fe"] = ($cons[0]["c_fe"] + 1);
        }
        Consecutives::where('id_branch_offices', '=', $id)->update($consecutive);
    }

    public function generatePDF($company, $bo, $client, $p, $doc) {
        try {
            $carpeta = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $p["key"] . '/';
            $data = ['title' => 'Factura Electronica'];
            $pdf = PDF::loadView('invoice.invoice_pdf',
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
    public function importDocView(){
        return view('company.importDocumentG');
    }
    public function importChargeFile(Request $request){
        $data = array();
        $archivo = $_FILES["fileInvoices"]["name"];
        $archivo_ruta = $_FILES["fileInvoices"]["tmp_name"];
        $archivo_guardado = "laravel/imports/fileInvoices.xlsx";
        if(copy($archivo_ruta, $archivo_guardado)){
            $xlsx = new SimpleXLSX($archivo_guardado);
            $datos["lines"] =  $xlsx->rows();
            foreach ($datos["lines"] as $index => $line) {
                if($line[1]!="" && $index != 0){
                    $company = Companies::where("companies.id_card", "=", $line[0])
                    ->join('type_id_cards', 'type_id_cards.id', '=', 'companies.type_id_card')
                    ->select('companies.*', 'type_id_cards.code as type_id_card_code')
                    ->first();
                    if($company){
                        $bo = BranchOffices::where("branch_offices.id_company", "=", $company["id"])
                                    ->join('provinces', 'provinces.id', '=', 'branch_offices.id_province')
                                    ->join('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
                                    ->join('districts', 'districts.id', '=', 'branch_offices.id_district')
                                    ->join('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
                                    ->select('branch_offices.*', 'provinces.code as province_code', 'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict',
                                            'cantons.code as canton_code', 'districts.code as district_code', 'country_codes.phone_code as phone_code')->first();
                        if($bo){
                            $client = Clients::where("clients.id_company", "=", $company["id"])->where("clients.id_card", "=", $line[1])
                                ->join('type_id_cards', 'type_id_cards.id', '=', 'clients.type_id_card')
                                ->join('provinces', 'provinces.id', '=', 'clients.id_province')
                                ->join('cantons', 'cantons.id', '=', 'clients.id_canton')
                                ->join('districts', 'districts.id', '=', 'clients.id_district')
                                ->join('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
                                ->select('clients.*', 'provinces.code as province_code', 'cantons.code as canton_code', 'districts.code as district_code',
                                        'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict', 'country_codes.phone_code as phone_code', 'type_id_cards.code as type_id_card_code')
                                ->first();
                                if($client){
                                   $data['consecutive'] = str_pad($bo->number, 3, "0", STR_PAD_LEFT).'0000101'.str_pad(Consecutives::where('consecutives.id_branch_offices', $bo->id)->first()->c_fe, 10, "0", STR_PAD_LEFT);
                                   $data['key'] = '506'.$d = date('dmy').str_pad($company->id_card, 12, "0", STR_PAD_LEFT).$data['consecutive'].'119890717';
                                   
                                   $data["e_a"] = DB::table('companies_economic_activities')
                                    ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                                    ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                                    ->where('companies_economic_activities.id_company', $company->id)
                                    ->where('economic_activities.number', $line[9])
                                    ->first();
                                    $data["detail_mh"] = "Ninguno";
                                    $data["answer_mh"] = "Procesando";
                                    $data["state_send"] = "No enviado";
                            
                                    // Start transaction!
                                    DB::beginTransaction();
                                    try {
                            
                                       $saveXML = $this->createInvoiceXML($data, $company, $bo, $client, $line);
                                        return;
                                        //firmar Documento
                                        $firmador = new Firmador();
                                       
                                        $XMLFirmado = $firmador->firmarXml('laravel/storage/app/public/' . $company->cryptographic_key, $company->pin, trim($saveXML), $firmador::TO_XML_STRING);
                                        $saveXML = simplexml_load_string(trim($XMLFirmado));
                                        $carpeta = 'laravel/storage/app/public/Files/creados/' . $company->id_card . '/' . $saveXML->Clave . '/';
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        //agregar envio aqui
                                        $nombre_fichero = $carpeta . $saveXML->Clave . '.xml';
                                        $saveXML->asXML($nombre_fichero);
                                        $data["ruta"] = 'Files/creados/' . $company->id_card . '/' . $saveXML->Clave . '/';      
                                        $token = $this->tokenMH($company[0]);     
                                        $result = $this->send($company[0], $client[0], $saveXML->Clave, $XMLFirmado, json_decode($token)->access_token);
                            
                                        
                                        if ($result["status"] == "202" || $result["status"] == "200") {
                                            $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                                            if ($result["ind-estado"] != "") {
                                                $p["answer_mh"] = $result["ind-estado"];
                                                if ($p["answer_mh"] != "procesando") {
                                                    $xml = simplexml_load_string(trim($result["message"]));
                                                    if ($p["answer_mh"] == "rechazado") {
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
                                            $p["answer_mh"] = "rechazado";
                                            $p["detail_mh"] = $result["result"];
                                            $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                                            if ($result["ind-estado"] != "") {
                                                $p["answer_mh"] = $result["ind-estado"];
                                                if ($p["answer_mh"] != "procesando") {
                                                    $xml = simplexml_load_string(trim($result["message"]));
                                                    if ($p["answer_mh"] == "rechazado") {
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
                                       
                                        // Validate, then create if valid 
                                        $doc = Documents::create($p);
                                        $this->generatePDF($company[0], $bo[0], $client[0], $p, $doc);
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
                                   return;
                                }else{
                                    echo "cliente no encontrado";
                                }
                        }else{
                            echo "No se ha encontrado la sucursal";
                            return;
                        }
                    }else{
                        echo "Emisor no encontrado";
                         return ;
                    }
                     
                }
            }
        }
        
        
      // return view('company.importDocumentG', $datos);
        
    }
    public function createInvoiceXML($data, $company, $bo, $client, $line) {
        $d = date('Y-m-d\TH:i:s');
        $stringXML = '';
        $stringXML .= '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $stringXML .= '
            <Clave>' . $data["key"] . '</Clave>
            <CodigoActividad>' . str_pad($data["e_a"]->number, 6, "0", STR_PAD_LEFT) . '</CodigoActividad>
            <NumeroConsecutivo>' . $data["consecutive"] . '</NumeroConsecutivo>
            <FechaEmision>' . $d . '</FechaEmision>
            <Emisor>
            <Nombre>' . $company->name_company . '</Nombre>
            <Identificacion>
            <Tipo>' . $company->type_id_card_code . '</Tipo>
            <Numero>' . $company->id_card . '</Numero>
            </Identificacion>
            <NombreComercial>' . $company->name_company . '</NombreComercial>
            <Ubicacion>
            <Provincia>' . $bo->province_code . '</Provincia>
            <Canton>' . $bo->canton_code . '</Canton>
            <Distrito>' . $bo->district_code . '</Distrito>
            <OtrasSenas>' . $bo->other_signs . '</OtrasSenas>
            </Ubicacion>
            <Telefono>
            <CodigoPais>' . $bo->phone_code . '</CodigoPais>
            <NumTelefono>' . $bo->phone . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $bo->emails . '</CorreoElectronico>
            </Emisor>
            ';
        if ($client) {
            $stringXML .= '<Receptor>
            <Nombre>' . $client->name_client . '</Nombre>
            <Identificacion>
            <Tipo>' . $client->type_id_card_code . '</Tipo>
            <Numero>' . $client->id_card . '</Numero>
            </Identificacion>
            <NombreComercial>' . $client->name_client . '</NombreComercial>
            <Ubicacion>
            <Provincia>' . $client->province_code . '</Provincia>
            <Canton>' . $client->canton_code . '</Canton>
            <Distrito>' . $client->district_code . '</Distrito>
            <OtrasSenas>' . $client->other_signs . '</OtrasSenas>
            </Ubicacion>
            <Telefono>
            <CodigoPais>' . $client->phone_code . '</CodigoPais>
            <NumTelefono>' . $client->phone . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $client->emails . '</CorreoElectronico>
            </Receptor>
            ';
        }
        $stringXML .= '<CondicionVenta>01</CondicionVenta>
            <PlazoCredito>1</PlazoCredito>
            <MedioPago>04</MedioPago>        
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
        $exoneracionAcumulada = 0;
            $stringXML .= '<LineaDetalle>
                <NumeroLinea>1</NumeroLinea>
                <Cantidad>' . $line[4] . '</Cantidad>
                <UnidadMedida>' . $line[3] . '</UnidadMedida>
                <Detalle>' . $line[2] . '</Detalle>
                <PrecioUnitario>' . $line[5] . '</PrecioUnitario>
                <MontoTotal>' . $line[5] . '</MontoTotal>
                <SubTotal>' .  $line[5] . '</SubTotal>
                <Impuesto>
                    <Codigo>01</Codigo>
                    <CodigoTarifa>08</CodigoTarifa>
                    <Tarifa>13</Tarifa>
                    <Monto>' .  round($line[5]*0.13, 5) . '</Monto>
                </Impuesto>
            </LineaDetalle>
        </DetalleServicio>
        <ResumenFactura>
            <TotalServGravados>' . $line[5] . '</TotalServGravados>
            <TotalServExentos>0</TotalServExentos>
            <TotalServExonerado>0</TotalServExonerado>
            <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
            <TotalMercanciasExentas>0</TotalMercanciasExentas>
            <TotalMercExonerada>0</TotalMercExonerada>
            <TotalGravado>' . $line[5] . '</TotalGravado>
            <TotalExento>0</TotalExento>
            <TotalExonerado>0</TotalExonerado>
            <TotalVenta>'. $line[5] .'</TotalVenta>
            <TotalDescuentos>' .$line[7] . '</TotalDescuentos>
            <TotalVentaNeta>'. $line[5] .'</TotalVentaNeta>
            <TotalImpuesto>' . round($line[5]*0.13, 5) . '</TotalImpuesto>
            <TotalComprobante>'. $line[8] .'</TotalComprobante>
        </ResumenFactura>
        </FacturaElectronica>';
        
        return $stringXML;
    }
    public function saveQB($key, $status){
        $config = include('config.php');
        try {
            //The first parameter of OAuth2LoginHelper is the ClientID, second parameter is the client Secret
            $oauth2LoginHelper = new OAuth2LoginHelper($config['client_id'],$config['client_secret']);
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
            $carpeta = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' .$key . '/';
            $nombre_fichero = $carpeta . $key . '.xml';
            if ($xml = simplexml_load_file($carpeta. '/' . basename($key . '.xml'))) {  
            //Add a new Invoice
            $line = array();
            $customer = "";
            $cantidad = $dataService->Query("select count(*) from Customer where active = true");
            $cantidad = $cantidad / 1000 * 1000;
            $customers = array();
            for ($i = 1; $i < $cantidad; $i = $i + 1000) {
                $customers = array_merge($customers, $dataService->findAll("Customer", $i, 1000));
            }
            if($customers != null && $customers != ""){
                foreach ($customers as $c) {
                    if(isset($c->AlternatePhone)){
                        $idcard = str_replace(["R","-"," "],["","",""],$c->AlternatePhone->FreeFormNumber);
                        if(isset($xml->Receptor)){
                            if($idcard == $xml->Receptor->Identificacion->Numero){
                             $customer = $c;
                             break;
                            }
                        }else{
                            if($idcard == "000000000"){
                             $customer = $c;
                             break;
                            }
                        }
                    }
                }
            }
            if($customer == "" || $customer == null){
                $customer = $this->createCustomer($xml, $dataService);
            }
            foreach ($xml->DetalleServicio->LineaDetalle as $l) {
                $items = $dataService->Query("select * from Item where Name like '".$l->Codigo."%'");
                if(count($items)>1){
                    $detalle = str_replace('-NC','', $l->Detalle);
                    $items = $dataService->Query("select * from Item where Name like '".$l->Codigo."-".$detalle."%'");
                }
                
                if(isset($l->Impuesto)){
                    $taxes = $dataService->Query("select * From TaxCode where Description = '".$l->Impuesto->Codigo.$l->Impuesto->CodigoTarifa."' and Active = true");
                }else{
                    $taxes = $dataService->Query("select * From TaxCode where Description = '0101' and Active = true");
                }
                if($items != null){
                    $lineData = [
                        "Amount" => (string)$l->PrecioUnitario*$l->Cantidad,
                        "Description" => $l->Codigo."-".$l->Detalle,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "value" => $items[0]->Id,//falta crear u obtener item
                                "name" => "".$l->Codigo."-".$l->Detalle
                            ],
                            "UnitPrice" => "".$l->PrecioUnitario,
                            "Qty" => "".$l->Cantidad,
                            "TaxCodeRef" => [
                                "value" => $taxes[0]->Id//buscar id del impuesto
                            ]
                        ]
                    ];
                    array_push($line, $lineData);
                }else{
                    $line = array();
                    $error = "Uno de los productos o servicio no se encuentra en Quickbooks";
                }
            }
           $discount = $dataService->Query("select * from Account where AccountSubType = 'DiscountsRefundsGiven'");
           $desc= 0;
           if(isset($xml->ResumenFactura->TotalDescuentos)){
                 $discount2 =  array(
                        "Amount" => "".$xml->ResumenFactura->TotalDescuentos,
                        "DetailType" => "DiscountLineDetail",
                        "DiscountLineDetail" => [
                            "PercentBased"=> false,
                            "DiscountAccountRef" => [
                                "value" => $discount[0]->Id,
                                "name" => $discount[0]->Name
                            ]
                        ]
                   );
                   array_push($line, $discount2);
            }
            if($line != ""){
                if( substr($xml->NumeroConsecutivo,8,2) == '03'){
                    $typeDoc = 3;
                $theResourceObj = CreditMemo::create([
                            "DocNumber" => "".$xml->NumeroConsecutivo,
                            "TxnDate" => substr($xml->FechaEmision, 0, 10),
                            "CustomField" =>  [
                		       [
                		       "DefinitionId" => "1",
                		       "Type"=> "StringType",
                		      	"StringValue" => $status
                		       ] 		      
                		      ],
                            "Line" => $line,
                             
                            "TxnTaxDetail" => [
                                "TotalTax" => "".$l->Impuesto->Monto,
                                "TaxLine" => [
                                    [
                                        "Amount" => "".$l->Impuesto->Monto,
                                        "DetailType" => "TaxLineDetail",
                                        "TaxLineDetail" => [
                                            "TaxRateRef" => [
                                                "value" => $taxes[0]->SalesTaxRateList->TaxRateDetail->TaxRateRef //id del rate
                                            ],
                                            "PercentBased" => true,
                                            "TaxPercent" => "".$l->Impuesto->Tarifa,
                                            "NetAmountTaxable" => "".$l->SubTotal// id faltante
                                        ]
                                    ]
                                ]
                            ],
                            "CustomerRef" => [
                                "value" => $customer->Id,
                                "name" => $customer->DisplayName
                            ],
                            "SalesTermRef" => [
                                "value" => "5"
                            ]
                ]);
            }else{
                $typeDoc = 1;
             $theResourceObj = Invoice::create([
                "DocNumber" => "".$xml->NumeroConsecutivo,
                "TxnDate" => substr($xml->FechaEmision, 0, 10),
                "CustomField" =>  [
    		       [
    		       "DefinitionId" => "1",
    		       "Type"=> "StringType",
    		      	"StringValue" => $status
    		       ] 		      
    		      ],
                "Line" => $line,
                "CustomerRef" => [
                    "value" => $customer->Id,
                    "name" => $customer->DisplayName
                ],
                "SalesTermRef" => [
                    "value" => "5"
                ]
                ]);
            }
                $resultingObj = $dataService->Add($theResourceObj);
                $error = $dataService->getLastError();
                if ($error != null) {
                    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    echo "The Response message is: " . $error->getResponseBody() . "\n";
                    return $result = array("status" => "400", "message" => 'Error en datos de acceso a QB');
                }else{
                    //actualiza el estado en qb
                    
                }
                $dataService->throwExceptionOnError(true);
            }else{
                return "Uno de los productos suministrados no se encuentran en Quickbooks";
            }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $this->uploadXML($dataService,$xml, $resultingObj->Id,$typeDoc); //funciona bien
        $this->uploadXMLR($dataService,$xml, $resultingObj->Id,$typeDoc);
        $this->uploadPDF($dataService,$xml, $resultingObj->Id,$typeDoc);
        Documents::where('key', '=', $key)->update(['state_mh'=>'Guardado']);
        return Redirect::back()->with('message', 'Documento ingresado con exito!!');
    }
    public function createCustomer($xml, $dataService){
        $id ="";
        try {
             $mon = (isset($xml->ResumenFactura->CodigoTipoMoneda))?$xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda:"CRC";
             $nmon = ($mon == "CRC")?"Colón costarricense":"Dólar estadounidense";
             $num = ($xml->Receptor->Identificacion->Tipo == "01")?$xml->Receptor->Identificacion->Numero:"R".$xml->Receptor->Identificacion->Numero;
            $name = str_replace(["´","&"],["",""],$xml->Receptor->Nombre);
            $theResourceObj = Customer::create([
                        "BillAddr" => [
                            "Line1" => "",
                            "City" => "",
                            "Country" => "Costa Rica",
                            "CountrySubDivisionCode" => ""
                        ],
                        "ShipAddr" => [
                            "Line1" => ""
                        ],
                        "SalesTermRef" => [
                            "value" => "5"
                        ],
                        "PaymentMethodRef" => [
                            "value" => "5"
                        ],
                        "CurrencyRef" => [
                            "value" => $mon,
                            "name" => $nmon
                        ],
                        "FullyQualifiedName" => $name,
                        "CompanyName" => "".$name,
                        "DisplayName" => $name . " CL " ,
                        "PrimaryPhone" => [
                            "FreeFormNumber" => "".$xml->Receptor->Telefono->NumTelefono
                        ],
                        "AlternatePhone" => [
                            "FreeFormNumber" => "".$xml->Receptor->Identificacion->Numero
                        ],
                        "PrimaryEmailAddr" => [
                            "Address" => "".$xml->Receptor->CorreoElectronico
                        ]
            ]);
            $resultingObj = $dataService->Add($theResourceObj);
            $error = $dataService->getLastError();
            if ($error) {
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";
            } else {
                return $resultingObj;
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
        
	    $path = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'.xml';
	    $randId = $xml->Clave;
	        try
	        {
        		$xmlBase64['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = $xml;		
        		$sendMimeType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";	
        		
        		// Create a new IPPAttachable
        		if($type==1){
        		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Invoice'));
        		}
        		if($type==3){
        		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'CreditMemo'));
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
	      
	      $path = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'-R.xml';	 
	      if(file_exists($path)){   
	        try
	        {
	       // Prepare entities for attachment upload				
        		$sendMimeType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";	
		
		// Create a new IPPAttachable
		$randId = $xml->Clave;
		if($type==1){
		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Invoice'));
		}
		if($type==3){
		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'CreditMemo'));
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
	      
	      $path = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $xml->Clave.'/'.$xml->Clave.'.pdf';	 
	      if(file_exists($path)){
	        try
	        {				
    		$sendMimeType = "application/pdf";		
    		
    		// Create a new IPPAttachable
    		$randId = $xml->Clave;
    		if($type==1){
		        $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'Invoice'));
    		}
    		if($type==3){
    		    $entityRef = new IPPReferenceType(array('value'=>$id, 'type'=>'CreditMemo'));
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
