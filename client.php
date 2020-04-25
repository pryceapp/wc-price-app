<?php

class PryceClient
{
    const ENDPOINT = "https://sheltered-sea-90487.herokuapp.com/api/quotation/";
    private static $requestToken;

    public function __construct($requestToken)
    {
        self::$requestToken = $requestToken;
    }

    public static function get_quotation($price, $product, $affiliate)
    {
        $requestContent = self::build_payload($price, $product, $affiliate);
        $responseContent = self::do_request($requestContent, self::$requestToken);

        if (self::has_response_error($responseContent, $responseContent)) {
            return $price;
        }

        $result = json_decode($responseContent["body"]);
        return $result[0]->selling_price;
    }

    private static function build_payload($price, $product, $affiliate)
    {
        $productCategories = get_terms([
            'taxonomy'   => 'product_cat',
            'include' => wp_list_pluck($product->get_category_ids(), 0)
        ]);

        $categories = wp_list_pluck($productCategories, 'name');

        return [
            "data" => [
                [
                    "sku" => $product->get_sku(),
                    "price" => $price,
                    "categories" => array_values($categories),
                    "affiliate" => $affiliate
                ]
            ]
        ];
    }

    private static function do_request($requestContent, $token)
    {
        $encodedRequest = json_encode($requestContent);

        return wp_remote_post(self::ENDPOINT, [
            'body' => $encodedRequest,
            'headers' => [
                'Authorization' => 'Token ' . $token,
                'Content-Type' => 'application/json',
                'Content-Length' => strlen($encodedRequest)
            ]
        ]);
    }

    private static function has_response_error($response, $requestContent)
    {
        $encodedRequest = json_encode($requestContent);
        $errorMessage = "[pryce.app] could not get price, error: " . json_encode($response['body']) . " request: " . $encodedRequest;

        if (is_wp_error($response) || !is_array($response)) {
            error_log($errorMessage);
            return true;
        }

        if ($response["http_response"]->get_status() !== 200) {
            error_log($errorMessage);
            return true;
        }

        return false;
    }
}
