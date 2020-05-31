<?php


class Quotation extends AbstractApi
{
    /**
     * Get product price value with discounts
     * 
     * @param   array   $data  Product information
     * @return  array   DiscountEntity Array
     */
    public function get(array $data)
    {
        $quotation = wp_remote_post(
            sprintf('%s/quotation/', $this->endpoint),
            ['body' => json_encode(['data' => [$data]]), 'headers' => $this->headers]
        );

        if ($this->hasResponseError($quotation)) {
            throw new Exception($quotation['body']);
        }

        $quotations = json_decode($quotation['body']);

        return array_map(function ($quotation) {
            return new QuotationEntity($quotation);
        }, $quotations->data);
    }
}
