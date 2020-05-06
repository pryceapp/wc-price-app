<?php
/*
Plugin Name: Pryce.app
Plugin URI: https://github.com/pryceapp/wc-price-app
Description: Pryce.app integration
Version: 0.1.0
Author: Pryce.app
Author URI: https://pryce.app/
License: GPLv2 or later
*/

defined('ABSPATH') || exit;

require 'helpers.php';
require 'cookie.php';
require 'client.php';

function pryceapp_add_token_configuration_start_setting($settings)
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
add_filter('woocommerce_general_settings', 'pryceapp_add_token_configuration_start_setting');

function pryceapp_price_integration($price, $product)
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

    $affiliate = PryceAPPGenericCookieHandler::getInstance()->get();

    $pryceClient = new PryceAPPClient($requestToken);

    $quotatedPrice = $pryceClient->get_quotation($price, $product, $affiliate);
    if (!$quotatedPrice) {
        return $price;
    }

    return $quotatedPrice;
}
add_filter('woocommerce_product_get_price', 'pryceapp_price_integration', 10, 2);

add_action('init', function () {
    $utm_source = pryceapp_get_request_parameter(
        PryceAPPGenericCookieHandler::DEFAULT_AFFILIATE_IDENTIFIER
    );
    if (!empty($utm_source)) {
        $cookieHandler = PryceAPPGenericCookieHandler::getInstance();
        $cookieHandler->set(
            $utm_source,
            strtotime('+20 minutes')
        );
    }
});
