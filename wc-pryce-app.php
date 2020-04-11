<?php
/*
Plugin Name: Pryce.app
Plugin URI: https://github.com/pryceapp/wc-price-app
Description: Pryce.app plugin integration
Version: 0.1
Author: Pryce.app
Author URI: https://github.com/pryceapp
License: GPL
*/

if (!defined('ABSPATH')) {
    exit;
}

function add_token_configuration_start_setting($settings)
{

    $updated_settings = [];

    foreach ($settings as $section) {

        // at the bottom of the General Options section
        if (
            isset($section['id']) && 'general_options' == $section['id'] &&
            isset($section['type']) && 'sectionend' == $section['type']
        ) {
            $updated_settings[] = [
                'name'     => __('Token para pryce.app', 'wc_seq_order_numbers'),
                'desc_tip' => __('Token de requisição encontrado no painel de controle (pagina de perfil)', 'wc_seq_order_numbers'),
                'id'       => 'wc_pryce_app_token',
                'type'     => 'text',
                'css'      => 'min-width:300px;',
                'std'      => '',  // WC < 2.0
                'default'  => '',  // WC >= 2.0
                'desc'     => __('Exemplo: b4e58140b61cf086c82153f6c371668684f6ca7a'),
            ];
        }

        $updated_settings[] = $section;
    }

    return $updated_settings;
}
add_filter('woocommerce_general_settings', 'add_token_configuration_start_setting');

function wc_pryce_app_integration($price, $product)
{

    $term = get_term_by('id', $product->get_category_ids()[0], 'product_cat');

    $requestContent = [
        "data" => [
            [
                "sku" => $product->get_sku(),
                "price" => $price,
                "category" => $term->name,
                "brand" => "teste",
                "zip_code" => "07400000",
                "affiliate" => "google"
            ]
        ]
    ];

    $encodedRequest = json_encode($requestContent);

    $endpoint = "https://sheltered-sea-90487.herokuapp.com/api/quotation/";
    $headers = [
        "Authorization" => "Token b4e58140b61cf086c82153f6c371668684f6ca7a",
        "Content-Type" => "application/json",
        "Content-Length" => strlen($encodedRequest)
    ];
    $response = Requests::post(
        $endpoint,
        $headers,
        $encodedRequest
    );

    if ($response->status_code !== 200) {
        error_log(
            "could not get price on pryce error: " . json_encode($response) . " request: " . $encoded_request
        );
        return $price;
    }

    $requestBody = json_decode($response->body);

    return $requestBody[0]->selling_price;
}
add_filter('woocommerce_product_get_price', 'wc_pryce_app_integration', 10, 2);
