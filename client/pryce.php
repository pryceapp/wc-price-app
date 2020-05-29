<?php
require 'abstract-api.php';
require 'abstract-entity.php';
require 'quotation-entity.php';
require 'discount-entity.php';
require 'meta.php';
require 'discount.php';
require 'quotation.php';

class Pryce
{
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function discounts()
    {
        return new Discount($this->token);
    }

    public function quotation()
    {
        return new Quotation($this->token);
    }
}
