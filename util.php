<?php

date_default_timezone_set('Asia/Singapore');

function get_root_url() {
    $page_url = 'http';
    if ($_SERVER["HTTPS"] == "on") 
        $page_url .= "s";
    $page_url .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    } else {
        $page_url .= $_SERVER["SERVER_NAME"];
    }

    // if 'eureka' folder is found, use it as root, otherwise, use the real root
    $uri = $_SERVER['REQUEST_URI'];
    $index = strpos($uri, 'eureka');
    if ($index !== false)
        return $page_url . substr($uri, 0, $index) . 'eureka/';
    return $page_url . '/';
}

function get_file_url($relative_path) {
    return get_root_url() . $relative_path;
}

function get_file_path($relative_path) {
    return __DIR__ . '/' . $relative_path;
}

function get_current_page() {
    return empty($_GET['page']) ? 1 : $_GET['page'];
}

function get_post_page_number(){
    return 10;
}

function get_tag_page_number(){
    return 2;
}

function get_credit_by_type($type){
    switch ($type) {
        case 'answer':
            $credit = 1;
            break;
        case 'thumb_up':
            $credit = 1;
            break;
        case 'accept':
            $credit = 5;
            break;
        default:
            $credit = 0;
            break;
    }
    return $credit;
}

function get_small_bulb_element() {
    return '<img class="bulb-small" src="'. get_file_url('img/eureka_on_32.png') . '">';
}

function get_big_bulb_on_element() {
    return '<img class="bulb-big" src="'. get_file_url('img/eureka_on_32.png') . '">';
}

function get_big_bulb_off_element() {
    return '<img class="bulb-big" src="'. get_file_url('img/eureka_off_32.png') . '">';
}
