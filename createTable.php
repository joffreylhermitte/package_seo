<?php
add_action('wp_ajax_ajaxaddseotable','addSeoTable');
add_action('wp_ajax_nopriv_ajaxaddseotable','addSeoTable');

function addSeoTable(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'seo';
    $wpdb_collate = $wpdb->collate;
    $sql =
        "CREATE TABLE {$table_name} (
         id int(11) NOT NULL auto_increment ,
         titre varchar(255) NOT NULL,
         description varchar(255) NOT NULL,
         page_id int(11) NOT NULL,
         PRIMARY KEY  (id)
         )
         CHARSET=utf8mb4
         COLLATE=utf8mb4_general_ci";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    showJson('ok');

}