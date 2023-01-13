<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php wp_head(); ?>
		
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
			 (adsbygoogle = window.adsbygoogle || []).push({
				  google_ad_client: "ca-pub-3052517714648216",
				  enable_page_level_ads: true
			 });
		</script>
    </head>
    <body <?php body_class(); ?>>
        <header id="<?php if (juniper_get_option('home-slug')=='') {echo "home";} else {echo esc_attr(juniper_get_option('home-slug'));} ?>">
            <?php
            get_template_part( 'parts/header','menu');
            get_template_part( 'parts/header','banner');
            ?>
        </header>