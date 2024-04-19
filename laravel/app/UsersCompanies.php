<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersCompanies extends Model {

    protected $fillable = [
        "id_user", "id_company", "roll"
    ];

}
