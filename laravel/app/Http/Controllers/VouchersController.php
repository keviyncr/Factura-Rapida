<?php

namespace App\Http\Controllers;

use App\Hacienda\Firmador;
use App\Vouchers;
use App\Documents;
use App\Consecutives;
use App\Providers;
use App\Provinces;
use App\Cantons;
use App\Districts;
use App\TypeIdCards;
use App\CountryCodes;
use App\SaleConditions;
use App\PaymentMethods;
use App\Currencies;
use App\Expenses;
use App\BranchOffices;
use App\Companies;
use App\EconomicActivities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefuseMail;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Data\IPPReferenceType;
use QuickBooksOnline\API\Data\IPPAttachableRef;
use QuickBooksOnline\API\Data\IPPAttachable;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class VouchersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware(['auth', 'verified']);
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
        
        $result = array();
        $path = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . session('company')->id_card . '/';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                $count = 0;
                while (($file = readdir($dir)) !== false) {
                    if (is_dir($path . $file) && $file != '.' && $file != '..') {
                        if (file_exists($path . $file . '/' . basename($file . '.xml'))) {
                                $xml = file_get_contents($path . $file . '/' . basename($file . '.xml'));
                                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                                $xml = simplexml_load_string($xml);
                              
                                    $fecha = substr($xml->FechaEmision, 0, 4).'-'.substr($xml->FechaEmision, 5, 2).'-'.substr($xml->FechaEmision, 8, 2);
                                    
                                        $clave = (string) $xml->Clave;
                                        $consecutivo = (string) $xml->NumeroConsecutivo;
                                        $emisor = (isset($xml->Emisor))?$xml->Emisor:null;
                                        $receptor = $xml->Receptor;
                                        $condicionVenta = $xml->CondicionVenta;
                                        $medioPago = $xml->MedioPago;
                                        $detalle = $xml->DetalleServicio;
                                        $neto = (string) $xml->ResumenFactura->TotalVentaNeta;
                                        $moneda = "CRC";
                                        if (isset($xml->ResumenFactura->CodigoTipoMoneda) && (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != null) {
                                            $moneda = (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                                        }
                                        $tipoCambio = (isset($xml->ResumenFactura->CodigoTipoMoneda->TipoCambio))?(string)$xml->ResumenFactura->CodigoTipoMoneda->TipoCambio:1;
                                        $descuentos = 0;
                                        if ((string) $xml->ResumenFactura->TotalDescuentos != null) {
                                            $descuentos = (string) $xml->ResumenFactura->TotalDescuentos;
                                        }
                                        $exoneracion = 0;
                                        if ((string) $xml->ResumenFactura->TotalExonerado != null) {
                                            $exoneracion = (string) $xml->ResumenFactura->TotalExonerado;
                                        }
                                        $subTotal = (string) $xml->ResumenFactura->TotalVenta;
                                        $impuestos = (isset($xml->ResumenFactura->TotalImpuest))?(string) $xml->ResumenFactura->TotalImpuesto:0;
                                        $total = (string) $xml->ResumenFactura->TotalComprobante;
                                        $fecha = (string) $xml->FechaEmision;
                                        $result[$count] = array(
                                            "clave" => $clave,
                                            "consecutivo" => $consecutivo,
                                            "fecha" => $fecha,
                                            "emisor" => $emisor,
                                            "receptor" => $receptor,
                                            "condicionVenta" => $condicionVenta,
                                            "medioPago" => $medioPago,
                                            "detalle" => $detalle,
                                            "neto" => $neto,
                                            "moneda" => $moneda,
                                            "subTotal" => $subTotal,
                                            "tipoCambio" => $tipoCambio,
                                            "descuento" => $descuentos,
                                            "exoneracion" => $exoneracion,
                                            "impuesto" => $impuestos,
                                            "total" => $total,
                                            "ruta" => $path
                                        );
                                        $count = $count + 1;
                        }
                    }
                }
                closedir($dir);
            }
        }
        $datos ['e_as'] = DB::table('companies_economic_activities')
                ->join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                ->select('economic_activities.*', 'companies_economic_activities.id as id_c_ea')
                ->where('companies_economic_activities.id_company', session('company')->id)
                ->get();
        $datos ['branch_offices'] = BranchOffices::where("id_company", "=", session('company')->id)->get();
        $datos ["vouchers"] = $result;
        return view('company.voucher', $datos);
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
     * @param  \App\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function show(Vouchers $vouchers) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function edit(Vouchers $vouchers) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vouchers $vouchers) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function proccess(Request $request, $action, $key) {
        $v = $request->except('_token');
        $filename = "laravel/storage/app/public/Files/recibidos/sin procesar/" . session('company')->id_card . "/" . $key . "/" . $key . ".xml";
        $xml = file_get_contents($filename);
        $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
        $xml = simplexml_load_string($xml);

        //Busqueda o registro del proveedor
        $provider = Providers::where('id_company', session('company')->id)
                        ->where('id_card', $xml->Emisor->Identificacion->Numero)
                        ->where("active", "1")->get();
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            if (!sizeof($provider) > 0) {
                $providerData = array();
                $providerData["id_company"] = session('company')->id;
                $providerData["id_card"] = $xml->Emisor->Identificacion->Numero;
                $providerData["type_id_card"] = TypeIdCards::where('code', $xml->Emisor->Identificacion->Tipo)->get()[0]["id"];
                $providerData["name_provider"] = $xml->Emisor->Nombre;
                $providerData["id_province"] = Provinces::where('code', $xml->Emisor->Ubicacion->Provincia)->get()[0]["id"];
                $providerData["id_canton"] = Cantons::where('id_province', $providerData["id_province"])->where('code', $xml->Emisor->Ubicacion->Canton)->get()[0]["id"];
                
                $providerData["id_district"] = Districts::where('id_canton', $providerData["id_canton"])->where('code', $xml->Emisor->Ubicacion->Distrito)->get()[0]["id"];
                $providerData["other_signs"] = $xml->Emisor->Ubicacion->OtrasSenas;
                $providerData["id_country_code"] = (isset($xml->Emisor->Telefono))?CountryCodes::where('phone_code', $xml->Emisor->Telefono->CodigoPais)->get()[0]["id"]:CountryCodes::where('phone_code', "506")->get()[0]["id"];
                $providerData["phone"] = (isset($xml->Emisor->Telefono))?substr($xml->Emisor->Telefono->NumTelefono, -8, 8):"22222222";
                $providerData["emails"] = $xml->Emisor->CorreoElectronico;
                $providerData["id_sale_condition"] = SaleConditions::where('code', $xml->CondicionVenta)->get()[0]["id"];
                $providerData["time"] = 1;
                if (isset($xml->PlazoCredito)) {
                   $providerData["time"] =  intval(preg_replace('/[^0-9]+/', '', $xml->PlazoCredito), 10);
                }
                $providerData["id_currency"] = "55";
                if (isset($xml->ResumenFactura->CodigoTipoMoneda)) {
                    $providerData["id_currency"] = Currencies::where('code', $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)->get()[0]["id"];
                }
                $providerData["id_payment_method"] = PaymentMethods::where('code', $xml->MedioPago)->get()[0]["id"];
                $providerData["active"] = 1;

                // Validate, then create if valid
                $provider = Providers::create($providerData);
            }
            $provider = Providers::where('id_company', session('company')->id)
                        ->where('id_card', $xml->Emisor->Identificacion->Numero)
                        ->where("active", "1")->get();
            if(isset($v["category"])){
             $voucherData["category"] = $v["category"];
            }
            $voucherData["id_company"] = session('company')->id;
            $voucherData["id_provider"] = $provider[0]->id;
            $voucherData["key"] = $key;
            $voucherData["consecutive"] = $xml->NumeroConsecutivo;
            $voucherData["ruta"] = "Files/recibidos/procesados/" . session('company')->id_card . "/" . $key . "/";
            $voucherData["total_discount"] =(isset($xml->ResumenFactura->TotalDescuentos))?$xml->ResumenFactura->TotalDescuentos:0;
            $voucherData["total_tax"] = isset($xml->ResumenFactura->TotalImpuesto)?$xml->ResumenFactura->TotalImpuesto:0;
            $voucherData["total_exoneration"] = (isset($xml->ResumenFactura->TotalExonerado))?$xml->ResumenFactura->TotalExonerado:0;
            $voucherData["total_invoice"] = $xml->ResumenFactura->TotalComprobante;
            $voucherData["e_a"] = EconomicActivities::findOrFail($v["id_ea"])["number"];
            $voucherData["state"] = "guardado";
            $voucherData["state_send"] = "No enviado";
            $voucherData["condition"] = "guardado";
            $bo = BranchOffices::findOrFail($v["id_branch_office"]);
            
            if ($action == "onlySave") {
                $voucherData["condition"] = "guardado";
            } else {
               if ($action == "accept") {
                 $consecutive = Consecutives::where("id_branch_offices", $v["id_branch_office"])->get()[0]["c_mra"];
                    $type = "05";
                    $consecutive = str_pad($bo["number"], 3, "0", STR_PAD_LEFT) . "0000105" . str_pad($consecutive, 10, "0", STR_PAD_LEFT);
                    $voucherData["condition"] = "aceptado";
                    $voucherData["consecutive"] = $consecutive;
                    
                } if ($action == "refuse") {
                    $consecutive = Consecutives::where("id_branch_offices", $v["id_branch_office"])->get()[0]["c_mrr"];
                    $type = "07";
                    $consecutive = str_pad($bo["number"], 3, "0", STR_PAD_LEFT) . "0000107" . str_pad($consecutive, 10, "0", STR_PAD_LEFT);
                    $voucherData["condition"] = "rechazado";
                    $voucherData["consecutive"] = $consecutive;
                }
                $mr = $this->createXMLMenssage($xml, $voucherData["condition"], $consecutive, $voucherData["e_a"]);
             
                $token = $this->tokenMH(Companies::findOrFail(session('company')->id));

                //firmar Documento
                $firmador = new Firmador();
                $XMLFirmado = $firmador->firmarXml('laravel/storage/app/public/' . session('company')->cryptographic_key, session('company')->pin, trim($mr), $firmador::TO_XML_STRING);
                $saveXML = simplexml_load_string(trim($XMLFirmado));
                $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $saveXML->Clave . '/';
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                //agregar envio aqui
                $nombre_fichero = $carpeta . $saveXML->Clave . '-F.xml';
                $saveXML->asXML($nombre_fichero);
                
                $result = $this->sendMessage($consecutive,$xml, $XMLFirmado, json_decode($token)->access_token);
                if ($result["status"] == "202" || $result["status"] == "200") {
                        $voucherData["state"] = "aceptado";
                        $voucherData["detail_mh"]=$result["result"];
                }
                if ($result["status"] == "401" || $result["status"] == "400") {
                    $voucherData["state"] = "rechazado";
                    $voucherData["detail_mh"]=$result["result"];
                }  
                
                 
            }
            $e = Expenses::where('id_company', session('company')->id)->where('key', $key)->get();
            if (sizeof($e) > 0) {
               $voucher = Expenses::where('id', '=', $e[0]->id)->update($voucherData);
            } else {
                $voucher = Expenses::create($voucherData);
            }
            if($voucher){
                if(file_exists ('laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key)){
                   $path = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $key;
                    foreach (glob($path . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }
                    
                }else{
                    $path = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key;
                    foreach (glob($path . "/*") as $archivos_carpeta) {
                        if (is_dir($archivos_carpeta)) {
                            rmDir_rf($archivos_carpeta);
                        } else {
                            unlink($archivos_carpeta);
                        }
                    }
                    $carpeta = 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/';
                    if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                rename('laravel/storage/app/public/Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $key, 'laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key);
                }
            }
            if ($action == "refuse") {
                $this->sendRefuse($key, $provider[0]->emails,$v["observation"]);
            }  
         
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
        if(isset($type)){
            $this->nextConseutive($type, $bo["id"]); 
        }
        return Redirect::back()->with(['message' => 'Comprobante procesado con exito!!']);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vouchers  $vouchers
     * @return \Illuminate\Http\Response
     */
    public function destroy($key) {
        try {
            //Datos necesarios para la firma del xml
            $path = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $key;
            foreach (glob($path . "/*") as $archivos_carpeta) {
                if (is_dir($archivos_carpeta)) {
                    rmDir_rf($archivos_carpeta);
                } else {
                    unlink($archivos_carpeta);
                }
            }
            return Redirect::back()->with(['message' => 'Comprobante eliminado con exito!!']);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    function nextConseutive($type, $id) {
        $cons = Consecutives::where('consecutives.id_branch_offices', $id)->get();
        $consecutive = Array();
        switch ($type) {
            case '05':
                $consecutive["c_mra"] = ($cons[0]["c_mra"] + 1);
                break;
            case '07':
                $consecutive["c_mrr"] = ($cons[0]["c_mrr"] + 1);
                break;
            default:
                $consecutive["c_mra"] = ($cons[0]["c_mra"] + 1);
        }
        Consecutives::where('id_branch_offices', '=', $id)->update($consecutive);
    }

    public function all() {
        // Connect to gmail
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "facturarapida.recepcion@gmail.com";
        $password = "jlqyrpjfrfsipvau";
        echo "conectandos.... ";
         /* try to connect */
       if($username != null){
            $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
        echo "conectado <br>";
        /* grab emails */
        $emails = imap_search($inbox, 'FROM ' . $username);
        $emails = imap_search($inbox, 'UNSEEN');

        /* if emails are returned, cycle through each... */
        if ($emails) {
            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);
            $cont = 1;
            foreach ($emails as $email_number) {//recorre emails
                echo "<br>Correo #" . $cont++ . "<br>";
                /* get information specific to this email */
                $structure = imap_fetchstructure($inbox, $email_number);
                $claveXML = "";
                $archXMLF = "";
                $archXMLM = "";
                $archPDFF = "";
                $bandera = "";

                $attachments = array();
                if (isset($structure->parts) && (count($structure->parts)-1)>0) {//if partes email
                    echo "Adjuntos: " . (count($structure->parts)-1) . "<br>";
                    for ($i = 1; $i < count($structure->parts); $i++) {// for recorre partes
                       
                        if (strcasecmp($structure->parts[$i]->subtype, "XML") === 0 || strcasecmp($structure->parts[$i]->subtype, "PDF") === 0 || strcasecmp($structure->parts[$i]->subtype, "OCTET-STREAM") === 0 || strcasecmp($structure->parts[$i]->subtype, "zip") === 0) {//if tipo de estructuras
                         echo "Adjunto: " . $i . $structure->parts[$i]->subtype."<br>";
                            $attachments[$i] = array(
                                'is_attachment' => false,
                                'filename' => '',
                                'name' => '',
                                'attachment' => '');
                            if ($structure->parts[$i]->ifdparameters) {
                                foreach ($structure->parts[$i]->dparameters as $object) {
                                    if (strtolower($object->attribute) == 'filename') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['filename'] = $object->value;
                                    }
                                }
                            }
                            if ($structure->parts[$i]->ifparameters) {
                                foreach ($structure->parts[$i]->parameters as $object) {
                                    if (strtolower($object->attribute) == 'name') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['filename'] = $object->value;
                                    }
                                }
                            }                          
                            if ($attachments[$i]['is_attachment']) {// if adjunto
                                $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
                                if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                                  $attachments [$i] ['attachment'] = base64_decode ( $attachments [$i] ['attachment'] );
                                }//fin encode 3 
                                else { // 4 = QUOTED-PRINTABLE
                                  $attachments [$i] ['attachment'] = quoted_printable_decode ( $attachments [$i] ['attachment'] );
                                }// fin encode 4
                            }//fin if adjunto
                    }// fin tipos de archivo
                     if (strcasecmp($structure->parts[$i]->subtype, "MIXED") === 0 ) {//if MIXED
                          echo "Adjuntos MIXEDs: " . $i . $structure->parts[$i]->subtype."<br>";
                          for ($j = 1; $j < count($structure->parts[$i]->parts); $j++) {// for recorre partes                            
                            $attachments2[$j] = array(
                                'is_attachment' => false,
                                'filename' => '',
                                'name' => '',
                                'attachment' => ''
                            );
                            if ($structure->parts[$i]->parts[$j]->ifdparameters) {
                                foreach ($structure->parts[$i]->parts[$j]->dparameters as $object) {
                                    if (strtolower($object->attribute) == 'filename') {
                                        $attachments2[$j]['is_attachment'] = true;
                                        $attachments2[$j]['filename'] = $object->value;
                                    }
                                }
                            }
                            if ($structure->parts[$i]->parts[$j]->ifparameters) {
                                foreach ($structure->parts[$i]->parts[$j]->parameters as $object) {
                                    if (strtolower($object->attribute) == 'name') {
                                        $attachments2[$j]['is_attachment'] = true;
                                        $attachments2[$j]['filename'] = $object->value;
                                    }
                                }
                            }
                            if ($attachments2[$j]['is_attachment']) {// if adjunto
                                $attachments2[$j]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
                                if ($structure->parts[$i]->parts[$j]->encoding == 3) { // 3 = BASE64
                                  $attachments2 [$j] ['attachment'] = base64_decode ( $attachments2 [$j] ['attachment'] );
                                }//fin encode 3 
                                else { // 4 = QUOTED-PRINTABLE
                                  $attachments2 [$j] ['attachment'] = quoted_printable_decode ( $attachments2 [$j] ['attachment'] );
                                }// fin encode 4
                            }//fin if adjunto
                          }// end if MIXED
                        }
                    }//fin for partes
                    
                
                    for ($h = 1; $h <= count($attachments); $h++) {
                        if(isset($attachments[$h])){
                            if(count($attachments) > 1 && (strpos(strtolower($attachments[1]['filename']), ".pdf") )){ $temp = $attachments[1];
                            $attachments[1] = $attachments[2];
                            $attachments[2] = $temp;
                        }
                        
                        if ($attachments[$h] ['is_attachment']) {
                             
                            if((strpos(strtolower($attachments[$h]['filename']), ".xml") )){
                                libxml_use_internal_errors(true);
                                $attachments[$h]['attachment'] = str_replace("o;?", "", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("C", "O", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("E", "O", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("o?=", "O",$attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = base64_encode ($attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = base64_decode ($attachments[$h]['attachment']);
                                libxml_use_internal_errors(true);

                                $object = simplexml_load_string(trim($attachments[$h]['attachment']));
                                
                                if ($object === false) {
                                    echo "Error cargando XML\n";
                                    foreach(libxml_get_errors() as $error) {
                                        echo "\t", $error->message;
                                    }
                                }
                                
                                if ($xml = simplexml_load_string($attachments[$h]['attachment'])) {
                                    $claveXML = $xml->Clave;
                                    
                                    if (strrpos($attachments[$h]['attachment'], 'mensajeHacienda') || strrpos($attachments[$h]['attachment'], 'MensajeHacienda')) {
                                        $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $xml->NumeroCedulaReceptor . '/' . $xml->Clave . '/';
                                        $cedula = $xml->NumeroCedulaReceptor;
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        $nombre_fichero = $carpeta . $xml->Clave . '-R.xml';
                                        $xml->asXML($nombre_fichero);
                                        echo "Nombre archivo : ".$nombreFichero =$attachments[$h] ['filename']."<br>";
                                        echo "Guardado xml respuesta <br>";
                                    }
                                    if (strrpos($attachments[$h]['attachment'], 'facturaElectronica') || strrpos($attachments[$h]['attachment'], 'FacturaElectronica') || strrpos($attachments[$h]['attachment'], 'tiqueteElectronico') || strrpos($attachments[$h]['attachment'], 'TiqueteElectronico') ||
                                            strrpos($attachments[$h]['attachment'], 'NotaCreditoElectronica') || strrpos($attachments[$h]['attachment'], 'notaCreditoElectronica')) {
                                        $claveXML = $xml->Clave;
                                        if(isset($xml->Receptor)){
                                        $cedula = $xml->Receptor->Identificacion->Numero;
                                        $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $xml->Receptor->Identificacion->Numero . '/' . $xml->Clave . '/';
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        $nombre_fichero = $carpeta . $xml->Clave . '.xml';
                                        $xml->asXML($nombre_fichero);
                                        echo "Nombre archivo : ".$nombreFichero =$attachments[$h] ['filename']."<br>";
                                        echo "Guardado xml factura <br>";
                                        }else{
                                            echo "Nombre archivo : ".$nombreFichero =$attachments[$h] ['filename']."<br>";
                                            echo "Sin receptor para guardar <br>";
                                        }
                                    }
                                } else {
                                    echo "Nombre archivo : ".$nombreFichero =$attachments[$h] ['filename']."<br>";
                                    echo "error al abrir <br>";
                                }
                            }
                            if((strpos(strtolower($attachments[$h]['filename']), ".pdf")) && $claveXML != "" ){
                              
                                $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $cedula . '/' . $xml->Clave . '/';
                                    if (!file_exists($carpeta)) {
                                        mkdir($carpeta, 0777, true);
                                    }
                                    $nombre_fichero = $carpeta .  $claveXML . '.pdf';
                                    if (file_put_contents($nombre_fichero, $attachments[$h]['attachment'])) {
                                        echo "Nombre archivo : ".$nombreFichero =$attachments[$h] ['filename']."<br>";
                                        echo "Guardado  pdf de factura <br>";
                                    }
                                
                            }
                            
                            if ((strpos(strtolower($attachments[$h]['filename']), ".zip"))) {
                               
                                echo "ZIP ".str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename'])." <br>";
                                $zip = new ZipArchive;
                                $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $xml->NumeroCedulaReceptor ;
                                if (!file_exists($carpeta)) {
                                    mkdir($carpeta, 0777, true);
                                }
                                $nombre_fichero = $carpeta . '/' . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . '.zip';
                                file_put_contents($nombre_fichero, $attachments[$h]['attachment']);
                                if ($zip->open($nombre_fichero) === TRUE) {
                                    $zip->extractTo($carpeta . '/');
                                    $zip->close();
                                    if ($xml = simplexml_load_file($carpeta . "/" . str_replace(".zip", "", $attachments[$h]['filename']) . ".xml")) {
                                    $carpeta = $carpeta. '/' . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']);
                                    rename($carpeta . "/" . str_replace(".zip", "", $attachments[$h]['filename']) . ".xml", $carpeta . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".xml");
                                    rename($carpeta . "/ATV_eFAC_Respuesta_" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".xml", $carpeta . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . "-R.xml");
                                    rename($carpeta . "/" . str_replace(".zip", "", $attachments[$h]['filename']) . ".pdf", $carpeta . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".pdf");
                                    echo 'OK';
                                    }
                                } else {
                                    echo 'failed';
                                }
                            }//fin if pdf
                            
                           
                        } // Fin de es adjunto
                    }
                    }
                   if(isset($attachments2)){
                   for ($m = 1; $m <= count($attachments2); $m++) {
                        if(count($attachments2) > 1 && (strpos(strtolower($attachments2[1]['filename']), ".pdf") )){ 
                            $temp = $attachments2[1];
                            $attachments2[1] = $attachments2[2];
                            $attachments2[2] = $temp;
                        }
                        if ($attachments2[$m] ['is_attachment']) {
                            if((strpos(strtolower($attachments2[$m]['filename']), ".xml") )){
                                libxml_use_internal_errors(true);
                                $attachments[$h]['attachment'] = str_replace("o;?", "", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("C", "O", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("E", "O", $attachments[$h]['attachment']);
                                $attachments[$h]['attachment'] = str_replace("o?=", "O", $attachments[$h]['attachment']);
                                if ($xml = simplexml_load_string($attachments2[$m]['attachment'])) {
                                    
                                    $claveXML = $xml->Clave;
                                    if (strrpos($attachments2[$m]['attachment'], 'mensajeHacienda') || strrpos($attachments2[$m]['attachment'], 'MensajeHacienda')) {
                                        $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $xml->NumeroCedulaReceptor . '/' . $xml->Clave . '/';
                                        $cedula = $xml->NumeroCedulaReceptor;
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        $nombre_fichero = $carpeta . $xml->Clave . '-R.xml';
                                        $xml->asXML($nombre_fichero);
                                        echo "Nombre archivo : ".$nombreFichero =$attachments2[$m] ['filename']."<br>";
                                        echo "Guardado xml respuesta <br>";
                                    }
                                    if (strrpos($attachments2[$m]['attachment'], 'facturaElectronica') || strrpos($attachments2[$m]['attachment'], 'FacturaElectronica') || strrpos($attachments2[$m]['attachment'], 'tiqueteElectronico') || strrpos($attachments2[$m]['attachment'], 'TiqueteElectronico') ||
                                            strrpos($attachments2[$m]['attachment'], 'NotaCreditoElectronica') || strrpos($attachments2[$m]['attachment'], 'notaCreditoElectronica')) {
                                        $claveXML = $xml->Clave;
                                        $cedula = $xml->Receptor->Identificacion->Numero;
                                        $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $xml->Receptor->Identificacion->Numero . '/' . $xml->Clave . '/';
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        $nombre_fichero = $carpeta . $xml->Clave . '.xml';
                                        $xml->asXML($nombre_fichero);
                                        echo "Nombre archivo : ".$nombreFichero =$attachments2[$m] ['filename']."<br>";
                                        echo "Guardado xml factura <br>";
                                    }
                                } else {
                                    echo "Nombre archivo : ".$nombreFichero =$attachments2[$m] ['filename']."<br>";
                                    echo "error al abrir <br>";
                                }
                            }
                            if((strpos(strtolower($attachments2[$m]['filename']), ".pdf")) && $claveXML != "" ){
                              
                                $carpeta = 'laravel/storage/app/public/Files/recibidos/sin procesar/' . $cedula . '/' . $xml->Clave . '/';
                                    if (!file_exists($carpeta)) {
                                        mkdir($carpeta, 0777, true);
                                    }
                                    $nombre_fichero = $carpeta . $claveXML . '.pdf';
                                    if (file_put_contents($nombre_fichero, $attachments2[$m]['attachment'])) {
                                        echo "Nombre archivo : ".$nombreFichero =$attachments2[$m] ['filename']."<br>";
                                        echo "Guardado  pdf de factura <br>";
                                    }
                                
                            }
                        } // Fin de es adjunto
                    }
                }
                    $claveXML = "";
                }//fin if partes email
            }//fin recorre emails
            // echo $output;
        }

        /* close the connection */
        imap_close($inbox);
       }
    }

    public function import(Request $request) {
        $v = $request->except('_token');
        $carpeta = public_path('Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $v["key"] . '/');
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        if ($request->hasFile("v_xml")) {
            $result = $request->file("v_xml")->storeAs('Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $v["key"] . '/', $v["key"] . ".xml");
        }
        if ($request->hasFile("v_xmlr")) {
            $result = $request->file("v_xmlr")->storeAs('Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $v["key"] . '/', $v["key"] . "-R.xml");
        }
        if ($request->hasFile("v_pdf")) {
            $result = $request->file("v_pdf")->storeAs('Files/recibidos/sin procesar/' . session('company')->id_card . '/' . $v["key"] . '/', $v["key"] . ".pdf");
        }
        return Redirect::back()->with(['message' => 'Comprobante importado con exito!!']);
    }

    public function generatePDF($company, $bo, $client, $p, $doc) {
        try {
            $carpeta = public_path('storage/Files/creados/' . session('company')->id_card . '/' . $p["key"] . "/");
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

    public function createXMLMenssage($xml, $condition, $consecutive, $ae) {

        $xmlString = '<?xml version="1.0" encoding="utf-8"?>
            <MensajeReceptor xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/mensajeReceptor">
            <Clave>' . $xml->Clave . '</Clave>
            <NumeroCedulaEmisor>' . $xml->Emisor->Identificacion[0]->Numero . '</NumeroCedulaEmisor>
            <FechaEmisionDoc>' . $xml->FechaEmision . '</FechaEmisionDoc>
            <Mensaje>' . $condition . '</Mensaje>
            ';
        if ($xml->ResumenFactura->TotalImpuesto == '' || $xml->ResumenFactura->TotalImpuesto == 0) {
            $xmlString .= '<MontoTotalImpuesto>0.00</MontoTotalImpuesto>';
        } else {
            $xmlString .= '<MontoTotalImpuesto>' . $xml->ResumenFactura->TotalImpuesto . '</MontoTotalImpuesto>';
        }
        $xmlString .= '<CodigoActividad>' . $ae . '</CodigoActividad>
            <CondicionImpuesto>04</CondicionImpuesto>
            <MontoTotalDeGastoAplicable>' . $xml->ResumenFactura->TotalComprobante . '</MontoTotalDeGastoAplicable>
            <TotalFactura>' . $xml->ResumenFactura->TotalComprobante . '</TotalFactura>
            <NumeroCedulaReceptor>' . $xml->Receptor->Identificacion[0]->Numero . '</NumeroCedulaReceptor>
            <NumeroConsecutivoReceptor>' . $consecutive . '</NumeroConsecutivoReceptor>
            </MensajeReceptor>';
        return $xmlString;
    }

    function sendMessage($consecutive,$xml, $XMLFirmado, $token) {
        $fecha = date('Y-m-d\TH:i:s');
        if (auth()->user()->proof == 1) {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion");
        } else {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion");
        }
        $idE = (string) $xml->Emisor->Identificacion->Tipo;
        $datos = array(
            'clave' => (string) $xml->Clave,
            'fecha' => $fecha,
            'emisor' => array(
                'tipoIdentificacion' => (string) $xml->Emisor->Identificacion->Tipo,
                'numeroIdentificacion' => (string) $xml->Emisor->Identificacion->Numero
            ),
            'receptor' => array(
                'tipoIdentificacion' => (string) $xml->Receptor->Identificacion->Tipo,
                'numeroIdentificacion' => (string) $xml->Receptor->Identificacion->Numero
            ),
            'consecutivoReceptor' => $consecutive,
            'comprobanteXml' => base64_encode($XMLFirmado)
        );
        //$datosJ= http_build_query($datos);
        $mensaje = json_encode($datos);
        $header = array(
            'Authorization: bearer ' . $token,
            'Content-Type: application/json'
        );
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
    public function getView($key) {
        $result = array();
        $path='laravel/storage/app/public/Files/recibidos/sin procesar/'.session('company')->id_card .'/'.$key.'/'.$key.'.xml';
        
        if (file_exists($path)) {
                $xml = file_get_contents($path);
                $xml = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
                $xml = simplexml_load_string($xml);
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
     public function sendRefuse($key, $mail, $observation) {
      
        $data = array();
        $data["key"] = $key;
        $data["xml"] ='laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key . '/' . $key . '.xml';
        $data["xmlF"] ='laravel/storage/app/public/Files/recibidos/procesados/' . session('company')->id_card . '/' . $key . '/' . $key . '-F.xml'; 
        $data["observation"] = $observation;
    
        try {
            
            Mail::to($mail)->queue(new RefuseMail($data));
            $document["state_send"] = "Enviado";
            Documents::where('key', '=', $key)->update($document);
            return 1;
            
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex);
        }
    }
     
}
