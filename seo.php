<?php

function generateSeoTags($postId,$siteName){
    global $wpdb;

    $typePost = get_post_type($postId);

    if($typePost === 'page') {

        $data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}seo WHERE page_id = $postId");
        if (get_the_post_thumbnail_url($postId) !== false) {
            $image = get_the_post_thumbnail_url($postId);
        } else {
            $image = assets('img/og-image.png');
        }

        $seo = '<title>' . stripslashes($data->titre) . '</title>' . "\n" . '
            <meta name="description" content="' . stripslashes($data->description) . '"/>' . "\n" . '
            <link rel="canonical" href="' . get_the_permalink($postId) . '"/>' . "\n" . '
            <meta property="og:locale" content="fr_FR"/>' . "\n" . '
            <meta property="og:type" content="website"/>' . "\n" . '
            <meta property="og:title" content="' . stripslashes($data->titre) . '"/>' . "\n" . '
            <meta property="og:description" content="' . stripslashes($data->description) . '"/>' . "\n" . '
            <meta property="og:url" content="' . get_the_permalink($postId) . '"/>' . "\n" . '
            <meta property="og:site_name" content="' . $siteName . '"/>' . "\n" . '
            <meta property="og:image" content="' . $image . '"/>' . "\n" . '
            <meta name="twitter:card" content="summary_large_image"/>';

        $json = file_get_contents(get_template_directory() . "/inc/seo/schema.json");
        $decoded = json_decode($json, true);
        $decoded["@graph"][0]['@id'] = get_home_url() . "#website";
        $decoded["@graph"][0]['url'] = get_home_url();
        $decoded["@graph"][0]['name'] = $siteName;
        $decoded["@graph"][1]['@id'] = get_the_permalink($postId) . "#webpage";
        $decoded["@graph"][1]['url'] = get_the_permalink($postId);
        $decoded["@graph"][1]['name'] = $data->titre;
        $decoded["@graph"][1]['isPartOf']["@id"] = get_the_permalink($postId) . "#website";
        $decoded["@graph"][1]['datePublished'] = get_the_date('c', $postId);
        $decoded["@graph"][1]['description'] = $data->description;
        $decoded["@graph"][1]['breadcrumb']["@id"] = get_the_permalink($postId) . "#breadcrumb";
        $decoded["@graph"][1]['potentialAction'][0]["target"][0] = get_the_permalink($postId);
        $decoded["@graph"][2]['@id'] = get_the_permalink($postId) . "#breadcrumb";
        $decoded["@graph"][2]['itemListElement'][0]['name'] = $data->titre;

        $encoded = json_encode($decoded);

        $seo .= "\n" . '<script type="application/ld+json">' . $encoded . '</script>';

    } else {
        if (get_the_post_thumbnail_url($postId) !== false) {
            $image = get_the_post_thumbnail_url($postId);
        } else {
            $image = assets('img/og-image.png');
        }
        $thumbnailId = get_post_thumbnail_id($postId);
        $attachment = wp_get_attachment_image_src($thumbnailId);
        $width = $attachment[1];
        $height = $attachment[2];
        $seo = '<title>' . get_the_title($postId) . '</title>' . "\n" . '
            <meta name="description" content="' . wp_trim_words(get_the_content(null,false,$postId),40) . '"/>' . "\n" . '
            <link rel="canonical" href="' . get_the_permalink($postId) . '"/>' . "\n" . '
            <meta property="og:locale" content="fr_FR"/>' . "\n" . '
            <meta property="og:type" content="article"/>' . "\n" . '
            <meta property="og:title" content="' . get_the_title($postId) . '"/>' . "\n" . '
            <meta property="og:description" content="' . wp_trim_words(get_the_content(null,false,$postId),40) . '"/>' . "\n" . '
            <meta property="og:url" content="' . get_the_permalink($postId) . '"/>' . "\n" . '
            <meta property="og:site_name" content="' . $siteName . '"/>' . "\n" . '
            <meta property="og:image" content="' . $image . '"/>' . "\n" . '
            <meta name="twitter:card" content="summary_large_image"/>';

        $json = file_get_contents(get_template_directory() . "/inc/seo/schemaSingle.json");
        $decoded = json_decode($json, true);
        $decoded["@graph"][0]['@id'] = get_home_url() . "#website";
        $decoded["@graph"][0]['url'] = get_home_url();
        $decoded["@graph"][0]['name'] = $siteName;
        $decoded["@graph"][1]['@id'] = get_the_permalink($postId) . "#primaryimage";
        $decoded["@graph"][1]['url'] = $image;
        $decoded["@graph"][1]['contentUrl'] = $image;
        $decoded["@graph"][1]['width'] = $width;
        $decoded["@graph"][1]['height'] = $height;
        $decoded["@graph"][2]['@id'] = get_the_permalink($postId) . "#webpage";
        $decoded["@graph"][2]['url'] = get_the_permalink($postId);
        $decoded["@graph"][2]['name'] = get_the_title($postId);
        $decoded["@graph"][2]['isPartOf']['@id'] = get_home_url()."#website";
        $decoded["@graph"][2]['primaryImageOfPage']['@id'] = get_the_permalink($postId)."#primaryimage";
        $decoded["@graph"][2]['datePublished'] = get_the_date('c', $postId);
        $decoded["@graph"][2]['breadcrumb']["@id"] = get_the_permalink($postId) . "#breadcrumb";
        $decoded["@graph"][2]['potentialAction'][0]["target"][0] = get_the_permalink($postId);
        $decoded["@graph"][3]['@id'] = get_the_permalink($postId) . "#breadcrumb";
        $decoded["@graph"][3]['itemListElement'][0]["name"] = $typePost;
        $decoded["@graph"][3]['itemListElement'][0]["item"] = get_post_type_archive_link($typePost);
        $decoded["@graph"][3]['itemListElement'][1]["name"] = get_the_title($postId);

        $encoded = json_encode($decoded);

        $seo .= "\n" . '<script type="application/ld+json">' . $encoded . '</script>';
    }


    return $seo;
}