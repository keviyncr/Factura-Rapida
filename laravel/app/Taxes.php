<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxes extends Model
{
    protected $fillable = [
        "id_company","id_taxes_code","id_rate_code","rate","rateIVA",
        "tax_base","description","id_exoneration"
    ];
}
