<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exonerations extends Model
{
    protected $fillable = [
        "id_company","id_type_document_exoneration","document_number","institutional_name","date",
        "exemption_percentage","description"
    ];
}
