<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\InvoiceMail;
use App\Documents;

class ailController extends Controller {

    public function store(Request $request) {
        
    }

    public function mailsend() {
        $message = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        Mail::to('k.campos@contafast.net')->queue(new ContactMail($message));
        return redirect()->back()->with('success', "Pronto nos pondremos en contacto!");
    }

    public function sendInvoice($key) {
        $data = array();
        $data["key"] = $key;
        $data["xml"] =public_path('storage/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '.xml');
        $data["xmlR"] =public_path('storage/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '-R.xml');
        $data["pdf"] = public_path('storage/Files/creados/' . session('company')->id_card . '/' . $key . '/' . $key . '.pdf');
        $xml = simplexml_load_string(file_get_contents(public_path('storage/Files/creados/'  . session('company')->id_card . '/' . $key . '/' . $key . '.xml')));
        try {
            Mail::to((String)$xml->Receptor->CorreoElectronico)->queue(new InvoiceMail($data));
            $document["state_send"] = "Enviado";
            Documents::where('key', '=', $key)->update($document);
            return redirect()->back()->with('success', "Factura enviada al cliente!");
            
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex);
        }
    }

}
