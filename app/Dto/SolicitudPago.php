<?php
namespace App\Dto;

class SolicitudPago
{
    public $merchantId;
    public $accountId;
    public $description;
    public $referenceCode;
    public $amount;
    public $tax;
    public $taxReturnBase;
    public $currency;
    public $signature;
    public $test;
    public $buyerEmail;
    public $responseUrl;
    public $confirmationUrl;
}
