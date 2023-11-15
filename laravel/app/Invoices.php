<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model {

    protected $fillable = [
        "id", "id_company", "key", "consecutive", "ruta", "invoiceXML", "answerXML", "total_discount"
        , "total_tax", "total_exoneration", "total_invoice", "detail_mh", "answer_mh", "state_send"
    ];

}
