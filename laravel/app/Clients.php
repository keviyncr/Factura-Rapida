<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        "id_company","id_card","type_id_card","name_client","id_province","id_canton","id_district",
        "other_signs","id_country_code","phone","emails","id_sale_condition","time","id_currency","id_payment_method"
    ];
}
