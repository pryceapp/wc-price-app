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
    public $isActive;

    /**
     * @var bool
     */
    public $isCumulative;

    /**
     * @var date
     */
    public $startAt;

    /**
     * @var date
     */
    public $endAt;

    /**
     * @var array[string]
     */
    public $sku;

    /**
     * @var float
     */
    public $maxPrice;

    /**
     * @var float
     */
    public $minPrice;

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
    public $discountValue;

    /**
     * @var string
     */
    public $discountType;

    /**
     * @var array[string]
     */
    public $categories;

    /**
     * @var array[string]
     */
    public $brands;
}
