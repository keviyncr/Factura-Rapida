<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchOffices extends Model {

    protected $fillable = [
        "number", "id_company", "name_branch_office", "id_province", "id_canton", "id_district",
        "other_signs", "id_country_code", "phone", "emails"
    ];

}
