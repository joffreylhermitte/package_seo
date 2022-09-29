<?php
add_action('wp_ajax_ajaxaddseo','addSeoData');
add_action('wp_ajax_nopriv_ajaxaddseo','addSeoData');

function addSeoData(){
    global $wpdb;
    $page = intval($_POST['page']);
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    $success = $wpdb->insert(
        $wpdb->prefix . 'seo',
        array(
            'titre'=>$titre,
            'description'=>$description,
            'page_id' => $page
        ),
        array("%s","%s","%d")
    );

    if($success){
        showJson('ok');
    } else {
        showJson('erreur');
    }





}