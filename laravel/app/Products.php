<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        "id","id_company","description","id_sku","ids_discounts","ids_taxes","price_unid","total_price"
        ,"total_discount","total_tax","total_exoneration","tax_base","tariff_heading","cabys", "type_internal_code","internal_code"
    ];
}
