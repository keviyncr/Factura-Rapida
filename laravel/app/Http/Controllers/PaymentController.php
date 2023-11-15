<?php

namespace App\Http\Controllers;

use App\Payment;
use App\TypeIdCards;
use App\BranchOffices;
use App\UsersCompanies;
use App\Companies;
use App\Documents;
use App\Clients;
use App\Consecutives;
use App\Provinces;
use App\User;
use App\Cantons;
use App\Districts;
use App\CountryCodes;
use App\Terminals;
use App\EconomicActivities;
use App\CompaniesEconomicActivities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayMail;
use App\Hacienda\Firmador;
use PDF;

class PaymentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    public function purchaseOptions(Request $request) {
        $p = $request->except("_token");
        $datos = array();
        $datos["idCard"] = $p["id_card"];
        $datos["typeIdCard"] = $p["type_id_card"];
        $datos["consecutive"] = DB::table('consecutive_fr')->get();
        $datos["plan"] = session('purchaseData')["plan"];
        $datos["type"] = session('purchaseData')["type"];
        $datos["price"] = session('purchaseData')["price"];
        $datos["qty"] = session('purchaseData')["qty"];
        $datos["sub"] = session('purchaseData')["sub"];
        $datos["iva"] = session('purchaseData')["iva"];
        $datos["shippingFirstName"] = strtok ( $p["name_company"] , ' ' );
        $datos["shippingLastName"] = str_replace (strtok ( $p["name_company"] , ' ' ).' ','',$p["name_company"]);
        $datos["shippingEmail"] =$p["shippingEmail"];
        session(['purchaseMail' => $datos["shippingEmail"]]);
        
        $datos["acquirerId"] = '12';
        $datos["total"] = session('purchaseData')["total"];
        $datos["idCommerce"] = '12643';
        $datos["purchaseOperationNumber"] =str_pad($datos["consecutive"][0]->number, 9, "0", STR_PAD_LEFT);
        if (session('purchaseData')["plan"] == 1) {
            if (session('purchaseData')["type"] == 1) {
                $datos["description"] = 'Plan post-pago anual, años - ' . session('purchaseData')["qty"];
            } else {
                $datos["description"] = 'Plan post-pago mensual, meses - ' . session('purchaseData')["qty"];
            }
        } else {
            $datos["description"] = 'Plan pre-pago, documentos - ' . session('purchaseData')["qty"];
        }
        $datos["totalt"] = ($datos["total"]*100);
        //Clave SHA-2 de VPOS2
        $claveSecreta = 'yEfJkFPUPqrDNtV.29637689';
        $datos["purchaseVerification"] = openssl_digest($datos["acquirerId"] . $datos["idCommerce"] . $datos["purchaseOperationNumber"] . $datos["totalt"] . 188 . $claveSecreta, 'sha512');

        
        return view('auth.purchaseOptions', $datos);
    }

    public function companyData(Request $request) {
        $p = $request->except("_token");
        $datos = array();
        $datos["plan"] = 1;
        $datos["type"] = 1;
        if (isset($p["plan"]) && isset($p["type"])) {
            $datos["plan"] = $p["plan"];
            $datos["type"] = $p["type"];
        }
       
        if ($datos["plan"] == 1) {
            if ($datos["type"] == 1) {
                $datos["product"] = "Plan de facturación mensual";
                $datos["price"] = 3539.82;
                $datos["qty"] = 1;
                $datos["sub"] = round($datos["price"] * $datos["qty"], 2);
                $datos["iva"] = round($datos["price"] * (0.13), 2);
                $datos["total"] = round($datos["price"] + $datos["iva"], 2);
            }
            if ($datos["type"] == 2) {
                $datos["product"] = "Plan de facturación anual";
                $datos["price"] = 38938.05;
                $datos["qty"] = 1;
                $datos["sub"] = round($datos["price"] * $datos["qty"], 2);
                $datos["iva"] = round($datos["price"] * (0.13), 2);
                $datos["total"] = round($datos["price"] + $datos["iva"], 2);
            }
        }
        if ($datos["plan"] == 2) {
            if ($datos["type"] == 1) {
                $datos["product"] = "Plan de 20 Documentos";
                $datos["price"] = 7079.64;
                $datos["qty"] = 1;
                $datos["sub"] = round($datos["price"] * $datos["qty"], 2);
                $datos["iva"] = round($datos["price"] * (0.13), 2);
                $datos["total"] = 8000;
            }
            if ($datos["type"] == 2) {
                $datos["product"] = "Plan de 100 Documentos";
                $datos["price"] = 14159.29;
                $datos["qty"] = 1;
                $datos["sub"] = round($datos["price"] * $datos["qty"], 2);
                $datos["iva"] = round($datos["price"] * (0.13), 2);
                $datos["total"] = 16000;
            }
            if ($datos["type"] == 3) {
                $datos["product"] = "Plan de 1000 Documentos";
                $datos["price"] = 35398.23;
                $datos["qty"] = 1;
                $datos["sub"] = round($datos["price"] * $datos["qty"], 2);
                $datos["iva"] = round($datos["price"] * (0.13), 2);
                $datos["total"] = 40000;
            }
        }
        session(['purchaseData' => $datos]);
        $datos ['type_id_cards'] = TypeIdCards::all();
       
        return view('auth.companyData', $datos);
    }

    public function receivepayment(Request $request) {
       
        $result = $request->except("_token");
        //Misma clave que se usa para el envio a VPOS2
        $claveSecreta = 'yEfJkFPUPqrDNtV.29637689';
        $purchaseVericationVPOS2 = "";
        if (isset($result['purchaseVerification'])) {
            $purchaseVericationVPOS2 = $result['purchaseVerification'];
        }
        $purchaseVericationComercio = openssl_digest($result['acquirerId'] . $result['idCommerce'] . $result['purchaseOperationNumber'] . $result['purchaseAmount'] . $result['purchaseCurrencyCode'] . $result['authorizationResult'] . $claveSecreta, 'sha512');
            //purchaseVerication que genera el comercio
            //Si ambos datos son iguales
        if ($purchaseVericationVPOS2 == $purchaseVericationComercio || $purchaseVericationVPOS2 == "") {
            
           if($result["authorizationResult"]=="00"){
                $data["code"] = uniqid();
                $data["number"] = $result['purchaseOperationNumber'];
                Mail::to('k.camposr05@gmail.com')->queue(new PayMail($data));
                $result["codeVerification"] = $data["code"];
           }
            $pay = Payment::create($result);
            $this->createInvoice($result);
        } else {
            echo "<h1>TransacciÃ³n Invalida. Los datos fueron alterados en el proceso de respuesta.</h1>";
        }
        //echo json_encode($result);
        return view('auth.purchaseResult', $result);
    }
     public function plusConsecutiveFR($consecutive){
        DB::table('consecutive_fr')
            ->where('id', 1)
            ->update(array('number' => $consecutive+1)); 
     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createClient($data) {
        $c["id_company"] = 661;
        $c["id_card"] = $data["reserved3"];
        $c["type_id_card"] = $data["reserved4"];
        $c["name_client"] = $data["shippingFirstName"]." ".$data["shippingLastName"];
        
        $c["id_province"] = 1;
        $c["id_canton"] = 1;
        $c["id_district"] = 1;
        $c["other_signs"] = "ninguna";
        
        $c["id_country_code"] = 52;
        $c["phone"] = "22222222";
        $c["emails"] = $data["shippingEmail"];
        $c["id_sale_condition"] = "1";
        $c["time"] = 1;
        $c["id_currency"] = 55;
        $c["id_payment_method"] = 4;
        // Start transaction!
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $bo = Clients::create($c);
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
            return $bo;
        
    }
     
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $p = $request->except("_token");
        $datos = array();
        $datos["plan"] = $p["plan"];
        $datos["type"] = $p["type"];
        return view('auth.pay', $datos);
    }
    
     public function createInvoice($data) {
        $company = Companies::where("companies.id", '661')
                ->join('type_id_cards', 'type_id_cards.id', '=', 'companies.type_id_card')
                ->select('companies.*', 'type_id_cards.code as type_id_card_code')
                ->get();
        $bo = BranchOffices::where("branch_offices.id", "=", '583')
                        ->join('provinces', 'provinces.id', '=', 'branch_offices.id_province')
                        ->join('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
                        ->join('districts', 'districts.id', '=', 'branch_offices.id_district')
                        ->join('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
                        ->select('branch_offices.*', 'provinces.code as province_code', 'provinces.province as nameProvince', 'cantons.canton as nameCanton', 'districts.district as nameDistrict',
                                'cantons.code as canton_code', 'districts.code as district_code', 'country_codes.phone_code as phone_code')->get();
        $client = Clients::where("clients.id_company", "=", '661')
        ->where("clients.id_card", "=", $data["reserved3"] )->get();
        if(count($client)>0){
            $client = $client[0];
        }else{
            $client = $this->createClient($data);
        }
        $data["id_client"] = $client -> id;
        $cons = Consecutives::where('consecutives.id_branch_offices', '583')->get();
        echo $data["consecutive"] = "0020000101".str_pad($cons[0]["c_fe"], 10, "0", STR_PAD_LEFT);
        $data["id_company"] = '661';
        $data["e_a"] = '729001';
        $data["sc"] = '01';
        $data["currency"] = "CRC";
        $data["pm"] = '04';
        $data["key"] = $this->getKey($data, $company[0]);
        $data["detail_mh"] = "Ninguno";
        $data["answer_mh"] = "Procesando";
        $data["state_send"] = "No enviado";
        
        //datos de la factura
        $data["total_discount"] = 0.00000;
        $data["total_tax"] = round($data["purchaseAmount"]-($data["purchaseAmount"]/1.13),5);
        $data["total_exoneration"] = 0.00000;
        $data["total_invoice"] = round($data["purchaseAmount"],5);

        // Start transaction!
        DB::beginTransaction();
        try {
            $saveXML = $this->createXMLInvoice($data);
            //firmar Documento
            $firmador = new Firmador();
            $XMLFirmado = $firmador->firmarXml('laravel/storage/app/public/' . $company[0]["cryptographic_key"], $company[0]["pin"], trim($saveXML), $firmador::TO_XML_STRING);
            $saveXML = simplexml_load_string(trim($XMLFirmado));
            $carpeta = 'laravel/storage/app/public/Files/creados/' . $company[0]["id_card"] . '/' . $saveXML->Clave . '/';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            //agregar envio aqui
            $nombre_fichero = $carpeta . $saveXML->Clave . '.xml';
            $saveXML->asXML($nombre_fichero);
            $data["ruta"] = 'Files/creados/' . $company[0]["id_card"] . '/' . $saveXML->Clave . '/';      
            $token = $this->tokenMH($company[0]);     
            $result = $this->send($company[0], $client, $saveXML->Clave, $XMLFirmado, json_decode($token)->access_token);

            
            if ($result["status"] == "202" || $result["status"] == "200") {
                $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                if ($result["ind-estado"] != "") {
                    $data["answer_mh"] = $result["ind-estado"];
                    if ($data["answer_mh"] != "procesando") {
                        $xml = simplexml_load_string(trim($result["message"]));
                        if ($data["answer_mh"] == "rechazado") {
                            $data["detail_mh"] = $xml->DetalleMensaje;
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
                $data["answer_mh"] = "rechazado";
                $data["detail_mh"] = $result["result"];
                $result = $this->consult($saveXML->Clave, json_decode($token)->access_token);
                if ($result["ind-estado"] != "") {
                    $data["answer_mh"] = $result["ind-estado"];
                    if ($data["answer_mh"] != "procesando") {
                        $xml = simplexml_load_string(trim($result["message"]));
                        if ($data["answer_mh"] == "rechazado") {
                            $data["detail_mh"] = $xml->DetalleMensaje;
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
            $doc = Documents::create($data);
            $this->generatePDF($company[0], $bo[0], $client, $data, $doc);
            $this->nextConseutive(substr($data["consecutive"], 8, 2), $bo[0]["id"]);
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
    }

     public function createXMLInvoice($data) {
         $data["id_client"] = $data["id_client"];
            $d = date('Y-m-d\TH:i:s');
            $data["key"] ="506".date("dmy")."003101747416".$data["consecutive"]."119890717";
            $stringXML = '';
            $stringXML .= '
                <?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <Clave>' . $data["key"] .'</Clave>
                <CodigoActividad>' . str_pad($data["e_a"], 6, "0", STR_PAD_LEFT) . '</CodigoActividad>
                <NumeroConsecutivo>' . $data["consecutive"] . '</NumeroConsecutivo>
                <FechaEmision>' . $d . '</FechaEmision>
                <Emisor>
                    <Nombre>Contafast S.A</Nombre>
                    <Identificacion>
                        <Tipo>02</Tipo>
                        <Numero>3101747416</Numero>
                    </Identificacion>
                    <NombreComercial>Contafast S.A</NombreComercial>
                    <Ubicacion>
                        <Provincia>3</Provincia>
                        <Canton>01</Canton>
                        <Distrito>04</Distrito>
                        <OtrasSenas>Plaza Comercial Bon Geniu, Oficina #5</OtrasSenas>
                    </Ubicacion>
                    <Telefono>
                        <CodigoPais>506</CodigoPais>
                        <NumTelefono>83996444</NumTelefono>
                    </Telefono>
                    <CorreoElectronico>info@contafast.net</CorreoElectronico>
                </Emisor>
                <Receptor>
                    <Nombre>' . $data["shippingFirstName"]." ".$data["shippingLastName"] . '</Nombre>
                    <Identificacion>
                        <Tipo>' . "0".$data["reserved4"] . '</Tipo>
                        <Numero>' . $data["reserved3"] . '</Numero>
                    </Identificacion>
                    <NombreComercial>' .  $data["shippingFirstName"]." ".$data["shippingLastName"] . '</NombreComercial>
                    <CorreoElectronico>' . $data["shippingEmail"] . '</CorreoElectronico>
                </Receptor>
                <CondicionVenta>01</CondicionVenta>
                <PlazoCredito>1</PlazoCredito>
                <MedioPago>04</MedioPago>        
                <DetalleServicio>
                    <LineaDetalle>
                        <NumeroLinea>01</NumeroLinea>
                        <Cantidad>1</Cantidad>
                        <UnidadMedida>Os</UnidadMedida>
                        <Detalle>' . $data["descriptionProducts"] . '</Detalle>
                        <PrecioUnitario>' . round($data["purchaseAmount"]/1.13,5) . '</PrecioUnitario>
                        <MontoTotal>' . round($data["purchaseAmount"]/1.13,5) . '</MontoTotal>
                        <SubTotal>' . round($data["purchaseAmount"]/1.13,5) . '</SubTotal>
                        <Impuesto>
                          <Codigo>01</Codigo>
                          <CodigoTarifa>08</CodigoTarifa>
                          <Tarifa>13</Tarifa>
                          <Monto>' . round($data["purchaseAmount"]-($data["purchaseAmount"]/1.13),5) . '</Monto>
                       </Impuesto>
                       <ImpuestoNeto>' . round($data["purchaseAmount"]-($data["purchaseAmount"]/1.13),5) . '</ImpuestoNeto>
                       <MontoTotalLinea>' .  round($data["purchaseAmount"],5) . '</MontoTotalLinea>
                   </LineaDetalle>
                </DetalleServicio>
            <ResumenFactura>
                <TotalServGravados>' . round($data["purchaseAmount"]/1.13,5) . '</TotalServGravados>
                <TotalServExentos>0</TotalServExentos>
                <TotalServExonerado>0</TotalServExonerado>
                <TotalMercanciasGravadas>0</TotalMercanciasGravadas>
                <TotalMercanciasExentas>0</TotalMercanciasExentas>
                <TotalMercExonerada>0</TotalMercExonerada>
                <TotalGravado>' . round($data["purchaseAmount"]/1.13,5) . '</TotalGravado>
                <TotalExento>0</TotalExento>
                <TotalExonerado>0</TotalExonerado>
                <TotalVenta>' . round($data["purchaseAmount"]/1.13,5) . '</TotalVenta>
                <TotalDescuentos>0</TotalDescuentos>
                <TotalVentaNeta>' . round($data["purchaseAmount"]/1.13,5) . '</TotalVentaNeta>
                <TotalImpuesto>' . round($data["purchaseAmount"]-($data["purchaseAmount"]/1.13),5) . '</TotalImpuesto>
                <TotalComprobante>' . round($data["purchaseAmount"],5) . '</TotalComprobante>
            </ResumenFactura>
            </FacturaElectronica>
            ';
            // Start transaction!
            return $stringXML;
        
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
        if(isset(auth()->user()->proof)){
            if (auth()->user()->proof == 1) {
                $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion");
            } else {
                $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion");
            }
        }else{
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
        if(isset(auth()->user()->proof)){
            if (auth()->user()->proof == '1') {
                $client_id = "api-stag";
                $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token";
            } else {
                $client_id = "api-prod";
                $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token";
            }
        }else{
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
        if(isset(auth()->user()->proof)){
            if (auth()->user()->proof == '1') {
                $url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/";
            } else {
                $url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/";
            }
        }else{
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
            $carpeta = 'laravel/storage/app/public/Files/creados/' . $company["id_card"] . '/' . $p["key"] . '/';
            $data = ['title' => 'Factura Electronica'];
            $pdf = PDF::loadView('invoice.invoice_pdf2',
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

}
