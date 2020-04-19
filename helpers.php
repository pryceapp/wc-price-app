<?php

const TRANSIENT_KEY = "pryce_app_%s";

function get_transient_key($productSku)
{
    return str_replace(
        "%s",
        $productSku,
        TRANSIENT_KEY
    );
}

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
