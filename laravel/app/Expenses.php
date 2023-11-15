<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
     protected $fillable = [
        "id", "id_company", "id_provider", "key", "consecutive", "ruta", "total_discount"
        , "total_tax", "total_exoneration", "total_invoice", "state", "state_send","condition","e_a","category"
    ];
}
