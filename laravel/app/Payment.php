<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "id", "authorizationResult", "errorCode", "errorMessage", "purchaseOperationNumber", "authorizationCode", "cardNumber", "purchaseAmount", "purchaseCurrencyCode", "authentificationECI", "cardType", "reserved1", "reserved2", "shippingEmail", "codeVerification"
    ];
}
