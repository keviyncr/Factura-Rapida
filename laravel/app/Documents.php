<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model {

    protected $fillable = [
        "id", "id_company", "id_client", "key", "consecutive", "ruta", "total_discount"
        , "total_tax", "total_exoneration", "total_invoice", "detail_mh", "answer_mh", "state_send", "e_a", "iva_returned"
    ];

}
