<?php
add_action('wp_ajax_ajaxgetseo','getSeoData');
add_action('wp_ajax_nopriv_ajaxgetseo','getSeoData');

function getSeoData(){
    global $wpdb;
    $page = intval($_POST['page']);

    $data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}seo WHERE page_id = $page");


    showJson($data);





}