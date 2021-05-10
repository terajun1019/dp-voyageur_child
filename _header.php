<?php
global $options, $options_visual, $IS_MOBILE_DP, $COLUMN_NUM, $SIDEBAR_FLOAT;?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js"><?php
if ( is_singular() ) : ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"><?php
else: ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/website#"><?php
endif; ?>
<meta charset="utf-8" /><?php
if (!$options['disable_mobile_fast']) : ?>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,user-scalable=yes" /><?php
endif;

if ( (is_front_page() || is_archive()) && is_paged()) : ?>
<meta name="robots" content="noindex,follow" /><?php
elseif ( is_singular() ) :
	if (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex,nofollow,noarchive" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) : ?>
<meta name="robots" content="noindex,nofollow" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="nofollow,noarchive" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noarchive" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="nofollow" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex,noarchive" />
<?php
	endif;
endif;

// **********************************
// Meta title, keyword, desc, etc
// **********************************
// show keyword and description
dp_meta_kw_desc();
// show OGP
dp_show_ogp();
// Canonical
dp_show_canonical();
// **********************************
// WordPress header
// **********************************
wp_head();?>
<script>var j$=jQuery;</script><?php
// **********************************
// Custom header
// **********************************
echo $options['custom_head_content'];
// **********************************
// wow.js
// **********************************
$wow_title_css = '';
$wow_desc_css = '';
$wow_eyecatch_css = '';
$wow_menu_css = '';
$attr_delay1 = '';
$attr_delay2 = ' data-wow-delay="0.3s"';
if (!(bool)$options['disable_wow_js']) {
	$wow_title_css = ' wow fadeInDown';
	$wow_desc_css = ' wow fadeInUp';
	$wow_eyecatch_css = ' wow fadeInDown';
	$wow_menu_css = ' wow fadeInLeft';
	if (is_front_page() && !is_paged()) {
		$attr_delay1 = ' data-wow-delay="1.2s"';
		$attr_delay2 = ' data-wow-delay="1.6s"';
	}
}
// **********************************
// Add by Atsushi 2020.11.23
// **********************************
?>
<script>
	$(function(){
	$(window).scroll(function (){
		$('.fade').each(function(){
			var targetElement = $(this).offset().top;
			var scroll = $(window).scrollTop();
			var windowHeight = $(window).height();
			if (scroll > targetElement - windowHeight + 200){
				$(this).css('opacity','1');
				$(this).css('transform','translateY(0)');
			}
		});
	});
});
</script>
</head><?php
// **********************************
// Main Body
// **********************************
// @ params
// class for body tag 
$body_class = $IS_MOBILE_DP ? 'main-body mb' : 'main-body';
// share count JS
if ( isset($options['disable_sns_share_count']) && !empty($options['disable_sns_share_count'])) {
	$body_class .= ' no-sns-count';
}

/**
 * SNS share count setting
 */
$data_share_cache = '';
if ( !isset($options['share_count_cache']) || (bool)$options['share_count_cache'] ){
	$share_count_cache_time = isset($options['share_count_cache_time']) ? (int)$options['share_count_cache_time'] : 86400000;
	$data_share_cache = ' data-ct-sns-cache="true" data-ct-sns-cache-time="' . $share_count_cache_time . '"';
}

// Header flag
$has_header_class = '';
if ( is_front_page() && !is_paged() ) {
	if ($options_visual['dp_header_content_type'] !== "none" ) {
		$has_header_class = ' has-header';
	}
}

// Fake loader color
$body_attr_data = ' data-loader-bg-color="' . $options_visual['header_menu_bgcolor'] . '"'; ?>
<body <?php body_class($body_class); echo $body_attr_data . $data_share_cache; ?>><?php
/**
 *  inject code immediately following the opening <body> tag. * WP 5.2 over
 */
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
} else {
	do_action( 'wp_body_open' );
}

// Loading... Only top page
if (is_front_page() && !is_paged()) :?>
<div id="fakeloader"></div><?php
endif;?>
<div class="main-wrap<?php echo $has_header_class; ?>"><?php

// **********************************
// Site header
// **********************************
include_once(TEMPLATEPATH . "/site-header.php");

// **********************************
// Full screen site banner
// **********************************
dp_banner_contents();

// **********************************
// Site container
// **********************************
// Front page

$top_no_content_class = '';
if ( (is_home() || is_front_page()) && empty($options['show_top_under_content']) ){
	$top_no_content_class = ' no-content';
}

if (is_front_page() && !is_paged() && !isset( $_REQUEST['q']) ) :
	$show_header_class = '';
	if ( is_active_sidebar('widget-container-top') 
		|| is_active_sidebar('widget-content-top')
		|| is_active_sidebar('widget-content-bottom')
		|| is_active_sidebar('widget-container-bottom')
		|| is_active_sidebar('widget-on-top-banner')
		|| (isset($options['header_img_h2']) && !empty($options['header_img_h2'])) ) :

		if ($options_visual['dp_header_content_type'] !== 'none') :
			$show_header_class = ' show-header';
		endif;
	endif;	// is_active_sidebar?>
<div id="container" class="dp-container home clearfix<?php echo $show_header_class.$top_no_content_class; ?>"><?php
elseif (is_singular()) :?>
<div id="container" class="dp-container singular clearfix"><?php
else :?>
<div id="container" class="dp-container not-home clearfix"><?php
endif;

// **********************************
// Container widget
// **********************************
if (!is_404() && is_active_sidebar('widget-container-top')) : ?>
<div class="widget-container top clearfix"><?php dynamic_sidebar( 'widget-container-top' ); ?></div><?php
endif;

// **********************************
// Main content
// **********************************
// Check column
$col_class				= ' two-col';
$sidebar_float_class	= ' '.$SIDEBAR_FLOAT;
if ( $COLUMN_NUM === 1 || is_404() ) {
	$col_class = ' one-col';
	$sidebar_float_class = '';
} else if ($COLUMN_NUM === 3) {
	$col_class = ' three-col';
}?>
<div class="content-wrap clearfix"><?php
	// **********************************
	// Show eyecatch on container 
	// **********************************
	if (is_single() || is_page()) {
		// get post type
		$post_type 			= get_post_type();
		// Show eyecatch on top 
		$show_eyecatch_force 	= get_post_meta(get_the_ID(), 'dp_show_eyecatch_force', true);
		// Show eyecatch upper the title
		$eyecatch_on_container 	= get_post_meta(get_the_ID(), 'dp_eyecatch_on_container', true);
		if( has_post_thumbnail() && $show_eyecatch_force && $eyecatch_on_container && ($post_type === 'post' || $post_type === 'page') ) {
			$image_id_f		= get_post_thumbnail_id();
			$image_data_f	= wp_get_attachment_image_src($image_id_f, 'full', true);
			$image_url_f 	= is_ssl() ? str_replace('http:', 'https:', $image_data_f[0]) : $image_data_f[0];
			$img_tag_f	= '<img src="' . $image_url_f . '" class="wp-post-image aligncenter" alt="' . the_title_attribute('before=&after=&echo=0') . '" width="'.$image_data_f[1].'" height="' . $image_data_f[2] . '" />';
			echo '<div class="single-eyecatch-container' . $wow_eyecatch_css . '">' . $img_tag_f . '</div>';
		}
	}?>
<div id="content" class="content<?php echo $col_class.$sidebar_float_class; ?>"><?php

// **********************************
// Content widget
// **********************************
if (!is_404() && is_active_sidebar('widget-content-top')) { ?>
<div class="widget-content top clearfix"><?php dynamic_sidebar( 'widget-content-top' ); ?></div><?php
}