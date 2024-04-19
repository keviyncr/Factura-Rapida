<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\InvoiceMail;
use App\Documents;
use App\Clients;

class MailController extends Controller {

    public function store(Request $request) {
        
    }

    public function mailsend() {
        $message = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        include "recaptchalib.php";
        $reCaptcha = new ReCaptcha($secret);

        // Was there a reCAPTCHA response?
        if ($_POST["g-recaptcha-response"]) {
            $resp = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $_POST["g-recaptcha-response"]
            );
        }
        if ($resp != null && $resp->success) {
            Mail::to('info@contafast.net')->queue(new ContactMail($message));
            return redirect()->back()->with('success', "Pronto nos pondremos en contacto!");
        }
       
    }

    public function sendInvoice($key) {
        $data = array();
        $data["key"] = $key;
         $data["xml"] ='laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '.xml';
        $data["xmlR"] ='laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '-R.xml';
        $data["pdf"] = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '.pdf';
        $xml = simplexml_load_string(file_get_contents('laravel/storage/app/public/Files/creados/'  . session('company')->id_card . '/' . $key . '/' . $key . '.xml'));
        try {
            $client = Clients::where("clients.id_card", "=", (String)$xml->Receptor->Identificacion->Numero)
            ->where("clients.id_company", "=", session('company')->id)->where("clients.active", "=", '1')->get();
            Mail::to($client[0]["emails"])->queue(new InvoiceMail($data));
            $document["state_send"] = "Enviado";
            Documents::where('key', '=', $key)->update($document);
            return redirect()->back()->with('success', "Factura enviada al cliente!");
            
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex);
        }
    }
    public function sendInvoicecc(Request $request) {
        $c = $request->except('_token');
        $data = array();
        $data["key"] = $c["key_send"];
         $data["xml"] ='laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $data["key"] . '/' . $data["key"] . '.xml';
        $data["xmlR"] ='laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $data["key"] . '/' . $data["key"] . '-R.xml';
        $data["pdf"] = 'laravel/storage/app/public/Files/creados/' . session('company')->id_card . '/' . $data["key"] . '/' . $data["key"] . '.pdf';
        $xml = simplexml_load_string(file_get_contents('laravel/storage/app/public/Files/creados/'  . session('company')->id_card . '/' . $data["key"] . '/' . $data["key"] . '.xml'));
        try {
            if($c["cc_mail"] != ''){
                 Mail::to($c["mail_send"])
                 ->cc($c["cc_mail"])
                 ->queue(new InvoiceMail($data));
            }else{
                Mail::to($c["mail_send"])->queue(new InvoiceMail($data));
            }
           
            $document["state_send"] = "Enviado";
            Documents::where('key', '=', $data["key"])->update($document);
            return redirect()->back()->with('success', "Factura enviada al cliente!");
            
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex);
        }
    }
    

}
