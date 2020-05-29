<?php


class Discount extends AbstractApi
{
    /**
     * Get all discounts
     *
     * @param   array  $filters  (optional) Filters Array
     * @return  array  DiscountEntity Array
     */
    public function getAll(array $filters = [])
    {
        $customers = wp_remote_get(
            sprintf(
                '%s/benefits/?%s',
                $this->endpoint,
                http_build_query($filters)
            ),
            $this->header
        );

        $customers = json_decode($customers);

        $this->extractMeta($customers);

        return array_map(function ($customer) {
            return new DiscountEntity($customer);
        }, $customers->data);
    }

    /**
     * Get Discount By Code
     *
     * @param   int  $id  Discount Code
     * @return  DiscountEntity
     */
    public function getByCode($code)
    {
        $customer = wp_remote_get(
            sprintf('%s/benefits/%s', $this->endpoint, $code),
            $this->header
        );

        $customer = json_decode($customer);

        return new DiscountEntity($customer);
    }
    /**
     * Create new customer
     *
     * @param   array  $data  Customer Data
     * @return  DiscountEntity
     */
    public function create(array $data)
    {
        $customer = wp_remote_post(
            sprintf('%s/benefits/', $this->endpoint),
            ['body' => json_encode($data), 'headers' => $this->header]
        );

        $customer = json_decode($customer);

        return new DiscountEntity($customer);
    }

    /**
     * Update Discount By Code
     *
     * @param   string  $id    Discount Code
     * @param   array   $data  Discount Data
     * @return  DiscountEntity
     */
    public function update($id, array $data)
    {
        $customer = wp_remote_post(
            sprintf('%s/benefits/%s', $this->endpoint, $id),
            ['body' => json_encode($data), 'headers' => $this->header]
        );

        $customer = json_decode($customer);

        return new DiscountEntity($customer);
    }

    /**
     * Delete Discount By Code
     *
     * @param  string|int  $code  Discount Code
     */
    public function delete($code)
    {
        wp_remote_request(
            sprintf('%s/benefits/%s', $this->endpoint, $code),
            ['method' => 'DELETE', 'headers' => $this->header]
        );
    }

    /**
     * Disable Discount By Code
     * 
     * @param string $code Discount Code
     */
    public function disable($code)
    {
        wp_remote_request(
            sprintf('%s/benefits/%s', $this->endpoint, $code),
            [
                'method' => 'PATCH',
                'body' => json_encode(['is_active' => false]),
                'headers' => $this->header
            ]
        );
    }
}
