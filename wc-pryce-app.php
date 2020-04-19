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

defined('ABSPATH') || exit;

require 'helpers.php';

const PRYCE_URL = "http://pryce.app/api/quotation/";

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

    $cached = get_transient(get_transient_key($product->get_sku()));
    if ($cached) {
        return $cached;
    }

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

    $response = wp_remote_post(PRYCE_URL, [
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
    $price = $responseContent[0]->selling_price;

    set_transient(
        get_transient_key($product->get_sku()),
        $price,
        5 * MINUTE_IN_SECONDS
    );

    return $price;
}
add_filter('woocommerce_product_get_price', 'wc_pryce_app_integration', 10, 2);

function wc_pryce_app_adds_utm_source_to_add_to_cart_link($button, $product)
{
    $utm_source = get_request_parameter('utm_source');
    if (empty($utm_source)) {
        return $button;
    }
    $pattern = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
    $matches = array();
    preg_match($pattern, $button, $matches);
    $newHref = $matches[0] . "&utm_source=" . $utm_source;
    return preg_replace($pattern, $newHref, $button);
}
add_filter('woocommerce_loop_add_to_cart_link', 'wc_pryce_app_adds_utm_source_to_add_to_cart_link', 10, 2);

function wc_pryce_app_product_link($buttonLink, $product)
{
    $utm_source = get_request_parameter('utm_source');
    if (empty($utm_source)) {
        return $buttonLink;
    }

    return $buttonLink . "&utm_source=" . $utm_source;
}
add_filter('woocommerce_loop_product_link', 'wc_pryce_app_product_link', 10, 2);


function wc_pryce_app_add_to_card_item($cart, $cartItemKey)
{
    $cacheKey = get_transient_key($cart['data']->get_sku());
    $cached = get_transient($cacheKey);

    $cart['data']->set_price($cached);

    return $cart;
}
add_filter('woocommerce_add_cart_item', 'wc_pryce_app_add_to_card_item', 10, 2);
