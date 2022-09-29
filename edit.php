<?php
add_action('wp_ajax_ajaxeditseo','editSeoData');
add_action('wp_ajax_nopriv_ajaxeditseo','editSeoData');

function editSeoData(){
    global $wpdb;
    $page = intval($_POST['page']);
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    $success = $wpdb->update(
        $wpdb->prefix . 'seo',
        array(
            'titre'=>$titre,
            'description'=>$description,

        ),
        array("page_id"=>$page)
    );

    if($success){
        showJson('ok');
    } else {
        showJson('erreur');
    }





}