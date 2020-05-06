<?php

function pryceapp_get_request_parameter($key, $default = '')
{
    if (!isset($_REQUEST[$key]) || empty($_REQUEST[$key])) {
        return $default;
    }

    return strip_tags((string) wp_unslash($_REQUEST[$key]));
}
