<?php
/**
 * Slide menu position
 */
$sl_menu_align = ' mleft';
if (isset($options['mb_slide_menu_position']) && $options['mb_slide_menu_position'] === 'right') {
	$sl_menu_align = ' mright';
}

/**
 * Global menu
 */
$global_menu_code = '';
$menu_class = 'main_slide_menu';
if (function_exists('wp_nav_menu')) {
	ob_start();
	// Show menu
	wp_nav_menu(array(
		'theme_location'=> 'top_menu_mobile',
		'container'	=> '',
		'after_only_parent_link' => '',
		'menu_id'	=> 'main_slide_menu',
		'menu_class'=> $menu_class,
		'fallback_cb'=> '__return_false',
		'walker'	=> new dp_custom_menu_walker()
	));
	$global_menu_code = ob_get_contents();
	$global_menu_code = '<nav id="slide_menu_nav" class="slide_menu_nav">'.$global_menu_code.'</nav>';
	ob_end_clean();
}

/**
 * SNS icon links
 */
$sns_code = '';
if ($options['show_global_menu_sns']) {
	if (!empty($options['global_menu_fb_url'])){
		$sns_code = '<li class="menu-item fb"><a href="' . $options['global_menu_fb_url'] . '" title="Share on Facebook" target="_blank" class="menu-link sns_link"><i class="menu-title icon-facebook"></i></a></li>';
	}
	if (!empty($options['global_menu_twitter_url'])){
		$sns_code .= '<li class="menu-item tw"><a href="' . $options['global_menu_twitter_url'] . '" title="Follow on Twitter" target="_blank" class="menu-link sns_link"><i class="menu-title icon-twitter"></i></a></li>';
	}
	if (!empty($options['global_menu_instagram_url'])){
		$sns_code .= '<li class="menu-item instagram"><a href="' . $options['global_menu_instagram_url'] . '" title="Instagram" target="_blank" class="menu-link sns_link"><i class="menu-title icon-instagram"></i></a></li>';
	}
	if (!empty($options['global_menu_youtube_url'])){
		$sns_code .= '<li class="menu-item youtube"><a href="' . $options['global_menu_youtube_url'] . '" title="YouTube" target="_blank" class="menu-link sns_link"><i class="menu-title icon-youtube"></i></a></li>';
	}

	if ( isset($options['show_global_menu_rss']) && !empty($options['show_global_menu_rss']) ) {
		if ( isset($options['rss_to_feedly']) && !empty($options['rss_to_feedly']) ) {
			$sns_code .= '<li class="menu-item feedly"><a href="https://feedly.com/i/subscription/feed/'.urlencode(get_feed_link()).'" target="_blank" title="Follow on feedly" class="menu-link sns_link"><i class="menu-title icon-feedly"></i></a></li>';
		} else {
			$sns_code .= '<li class="menu-item rss"><a href="'. get_feed_link() .'" title="Subscribe Feed" target="_blank" class="menu-link sns_link"><i class="menu-title icon-rss"></i></a></li>';
		}
	}
}

// Show SNS and feed icons
if (!empty($sns_code)) {
	$sns_code = '<ul class="menu_sns_links">'.$sns_code.'</ul>';
}

/**
 * Tel number
 */
$tel_code = '';
if ( isset($options['global_menu_tel_number']) && !empty($options['global_menu_tel_number']) ) {
	$tel_code = '<div class="menu_tel"><a href="tel:'.$options['global_menu_tel_number'].'" class="tel_a icon-phone"><span>'.$options['global_menu_tel_number'].'</span></a></div>';
}

/**
 * Slide panel
 */
if (!empty($global_menu_code) || !empty($sns_code) || !empty($tel_code)){
	$global_menu_code = '<input type="checkbox" role="button" title="menu" class="hidden_elem" id="main_menu_flag"><label for="main_menu_flag" class="btbar_btn btbar_trigger" aria-hidden="true" title="menu"><div class="btbtn_inner main_menu'.$sl_menu_align.'"><i class="menu_icon icon-spaced-menu"></i><span class="cap">'.__('Menu', 'DigiPress').'</span></div></label><div class="modal_wrapper main_menu'.$sl_menu_align.'">'.$global_menu_code.$tel_code.$sns_code.'</div>';
}
/**
 * Search form
 */
$search_form = '';
if ( isset($options['show_global_menu_search']) && $options['show_global_menu_search'] === 'gcs' ) {
	// Google Custom Search
	$search_form = '<div id="dp_hd_gcs"><gcse:searchbox-only></gcse:searchbox-only></div>';
} else if ( isset($options['show_global_menu_search']) && $options['show_global_menu_search'] === 'search' ) {
	// Default search form
	$preset_phrase = isset($options['global_menu_search_form_preset_kw_title']) && !empty($options['global_menu_search_form_preset_kw_title']) ? $options['global_menu_search_form_preset_kw_title'] : '';
	$preset_words = isset($options['global_menu_search_form_preset_kw']) && !empty($options['global_menu_search_form_preset_kw']) ? $options['global_menu_search_form_preset_kw'] : '';

	// Defined at /scr/search_form.php
	$search_form = dp_custom_search_form(false, array(
		'form_id' => '',
		'param_cat' => false,
		'param_tag' => false,
		'param_type' => false,
		'param_range' => false,
		'preset_phrase' => $preset_phrase,
		'preset_words' => $preset_words)
	);
}
if ( !empty($search_form) ) {
	$search_form = '<input type="checkbox" role="button" title="search" class="hidden_elem" id="search_form_flag"><label for="search_form_flag" class="btbar_btn btbar_trigger" aria-hidden="true" title="search"><div class="btbtn_inner search_form'.$sl_menu_align.'"><i class="menu_icon icon-search"></i><span class="cap">'.__('Search', 'DigiPress').'</span></div></label><div class="modal_wrapper search_form">'.$search_form .'</div>';
}

/**
 * Next / prev post link(Single page only)
 */
$bt_prev_code = $bt_next_code = '';
if (is_single()){
	if ($prev_post) {
		$nav_url_prev = get_permalink($prev_post->ID);
		$nav_title_prev = get_the_title($prev_post->ID);
		$bt_prev_code = '<li class="btbar_item"><a href="'.$nav_url_prev.'" class="btbar_btn"><i class="menu_icon icon-left-light"></i><span class="cap">'.__('Prev', 'DigiPress').'</span></a></li>';
	}
	if ($next_post) {
		$nav_url_next = get_permalink($next_post->ID);
		$nav_title_next = get_the_title($next_post->ID);
		$bt_next_code = '<li class="btbar_item"><a href="'.$nav_url_next.'" class="btbar_btn"><i class="menu_icon icon-right-light"></i><span class="cap">'.__('Next', 'DigiPress').'</span></a></li>';
	}
}

/**
 * Fixed bottom bar
 */
// Main menu and Search form(divisual content)
echo $global_menu_code.$search_form;
// Bottom bar?>
<div id="bottom_bar" class="bottom_bar">
	<ul class="btbar_ul">
		<?php echo $bt_prev_code ; ?>
		<li class="btbar_item">
			<a href="<?php echo home_url(); ?>" class="btbar_btn gohome">
				<i class="menu_icon icon-home">
				</i>
				<span class="cap">
					<?php _e('Home', 'DigiPress'); ?>
				</span>
			</a>
		</li>
		<li class="btbar_item">
			<button id="gotop" class="btbar_btn gotop">
				<i class="menu_icon mobile icon-up-open">
				</i>
				<span class="cap">
					<?php _e('Go top', 'DigiPress'); ?>
				</span>
			</button>
		</li>
		<?php echo $bt_next_code ; ?>
	</ul>
</div>