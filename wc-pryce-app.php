<?php
/*
Plugin Name: Pryce.app
Plugin URI: https://github.com/pryceapp/wc-price-app
Description: Pryce.app integration
Version: 0.1
Author: Pryce.app
Author URI: https://pryce.app/
License: GPLv2 or later
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
                'desc'     => __('Exemplo: b4e58140b61cf086c82153f6c371668684f6ca71'),
            ];
        }

        $updated_settings[] = $section;
    }

    return $updated_settings;
}
add_filter('woocommerce_general_settings', 'add_token_configuration_start_setting');

function wc_pryce_app_integration($price, $product)
{
    $requestToken = get_option('wc_pryce_app_token', 1);
    if (!$requestToken) {
        error_log('[pryce.app] token not found.');
        return $price;
    }

    // For "related products" section that doesn't show the price
    if (empty($price)) {
        return $price;
    }

    $productCategories = get_terms([
        'taxonomy'   => 'product_cat',
        'include' => wp_list_pluck($product->get_category_ids(), 0)
    ]);

    $categories = wp_list_pluck($productCategories, 'name');
    $affiliate = get_request_parameter('utm_source');

    $requestContent = [
        "data" => [
            [
                "sku" => $product->get_sku(),
                "price" => $price,
                "categories" => array_values($categories),
                "affiliate" => $affiliate
            ]
        ]
    ];

    $encodedRequest = json_encode($requestContent);

    $endpoint = "https://pryce.app/api/quotation/";
    $response = wp_remote_post($endpoint, [
        'body' => $encodedRequest,
        'headers' => [
            'Authorization' => 'Token ' . $requestToken,
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($encodedRequest)
        ]
    ]);

    if (has_response_errors($response, $encodedRequest)) {
        return $price;
    }

    $responseContent = json_decode($response['body']);

    return $responseContent[0]->selling_price;
}
add_filter('woocommerce_product_get_price', 'wc_pryce_app_integration', 10, 2);


function get_request_parameter($key, $default = '')
{
    if (!isset($_REQUEST[$key]) || empty($_REQUEST[$key])) {
        return $default;
    }

    return strip_tags((string) wp_unslash($_REQUEST[$key]));
}

function has_response_errors($response, $encodedRequest)
{
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
