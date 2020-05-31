<?php


final class DiscountEntity extends AbstractEntity
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $is_active;

    /**
     * @var bool
     */
    public $is_cumulative;

    /**
     * @var date
     */
    public $start_at;

    /**
     * @var date
     */
    public $end_at;

    public $created_at;

    /**
     * @var array[string]
     */
    public $sku;

    /**
     * @var float
     */
    public $max_price;

    /**
     * @var float
     */
    public $min_price;

    /**
     * @var array[string]
     */
    public $activeDaysWeek;

    /**
     * @var array[string]
     */
    public $affiliates;

    /**
     * @var float
     */
    public $discount_value;

    /**
     * @var string
     */
    public $discount_type;

    /**
     * @var array[string]
     */
    public $categories;

    /**
     * @var array[string]
     */
    public $brands;
}
