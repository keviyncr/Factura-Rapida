<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompaniesEconomicActivities extends Model
{
     protected $fillable = [
        "id","id_company","id_economic_activity"
    ];
}
