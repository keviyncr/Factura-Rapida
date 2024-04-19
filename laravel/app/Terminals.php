<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminals extends Model
{
    protected $fillable = [
        "id_company","id_branch_office","number"
    ];
}
