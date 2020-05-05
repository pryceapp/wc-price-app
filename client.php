<?php

class PryceClient
{
    const ENDPOINT = "https://pryce.app/api/quotation/";
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
            return false;
        }

        $result = json_decode($responseContent["body"]);
        return $result->data[0]->selling_price;
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
                    "affiliate" => !$affiliate ? "" : $affiliate
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

        if (is_wp_error($response) || !is_array($response)) {
            error_log("[pryce.app] could not get pryce, error: " . json_encode($response));
            return true;
        }

        if ($response["http_response"]->get_status() !== 200) {
            error_log("[pryce.app] could not get pryce, error: " . json_encode($response["body"]));
            return true;
        }

        return false;
    }
}
