<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model {
  
    protected $fillable = [
        "id_card","type_id_card","name_company","user_mh","pass_mh","cryptographic_key","pin"
    ];
}
