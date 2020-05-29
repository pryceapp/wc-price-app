<?php


final class QuotationEntity extends AbstractEntity
{
    /**
     * Product price without discount
     * 
     * @var float
     */
    public $price;

    /**
     * Product price with discount
     * 
     * @var float
     */
    public $selling_price;
}
