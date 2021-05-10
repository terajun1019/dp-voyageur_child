<?php
// **********************************
// Header (main-wrap > container > content)
// **********************************
get_header();

//For thumbnail size
$width = 1680;
$height = 1200;

// wow.js
$wow_title_css 		= 'no-wow';
$wow_meta_css 		= '';
$wow_eyecatch_css 	= '';
if (!(bool)$options['disable_wow_js']) {
	$wow_title_css		= 'wow fadeInDown';
	$wow_meta_css 		= ' wow fadeInUp';
	$wow_eyecatch_css 	= ' wow fadeInUp';
}

// **********************************
// Params
// **********************************
// Common Parameters
$show_eyecatch_first 	= $options['show_eyecatch_first'];

// Meta under the title
$show_date_under_post_title = $options['show_date_under_post_title'];
$show_views_under_post_title = $options['show_views_under_post_title'];
$show_author_under_post_title = $options['show_author_under_post_title'];
$show_cat_under_post_title 	= $options['show_cat_under_post_title'];
$sns_button_under_title 	= $options['sns_button_under_title'];

// Meta bottom
$show_date_on_post_meta 	= $options['show_date_on_post_meta'];
$show_views_on_post_meta 	= $options['show_views_on_post_meta'];
$show_author_on_post_meta 	= $options['show_author_on_post_meta'];
$show_cat_on_post_meta 		= $options['show_cat_on_post_meta'];
$sns_button_on_meta 		= $options['sns_button_on_meta'];


// **********************************
// show posts
// **********************************
if (have_posts()) :

	$this_id = get_the_ID();

	// Count Post View
	if (function_exists('dp_count_post_views')) {
		dp_count_post_views($this_id, true);
	}

	// get post type
	$post_type 			= get_post_type();
	// Post format
	$post_format 		= get_post_format();
	// Title
	$post_title 		=  the_title('', '', false) ? the_title('', '', false) : __('No Title', 'DigiPress');
	// Hide title flag
	$hide_title 		= get_post_meta($this_id, 'dp_hide_title', true);
	// Show eyecatch on top 
	$show_eyecatch_force 	= get_post_meta($this_id, 'dp_show_eyecatch_force', true);
	// Show eyecatch upper the title
	$eyecatch_on_container 	= get_post_meta($this_id, 'dp_eyecatch_on_container', true);

	// **********************************
	// Get post meta codes (Call this function written in "meta_info.php")
	// **********************************
	$first_row		= '';
	$sns_code 		= '';
	$meta_code_top 	= '';
	$meta_code_end 	= '';

	// **********************************
	//  Create meta data
	// **********************************
	if (!(bool)post_password_required()) {
		$arr_meta = get_post_meta_for_single_page();
		// **********************************
		//  Meta No.1
		// **********************************
		// Date
		if ((bool)$show_date_under_post_title && !empty($arr_meta['date'])) {
			$first_row = '<div class="meta meta-date">' . $arr_meta['date'] . $arr_meta['last_update'] . '</div>';
		}
		// Author
		if ((bool)$show_author_under_post_title) {
			$first_row .= $arr_meta['author'];
		}
		// edit link
		$first_row .= $arr_meta['edit_link'];
		// First row
		if (!empty($first_row)) {
			$first_row = '<div class="first_row clearfix">' . $first_row . '</div>';
		}
		//*** filter hook
		if ($post_type === 'page') {
			$filter_top_first = apply_filters('dp_single_meta_top_first',  $this_id);
			if (!empty($filter_top_first) && $filter_top_first !=  $this_id) {
				$first_row .= $filter_top_first;
			}
		}

		// SNS buttons
		if ((bool)$sns_button_under_title) {
			$sns_code = $arr_meta['sns_btn'];
		}
		//*** filter hook
		if ($post_type === 'page') {
			$filter_top_end = apply_filters('dp_single_meta_top_end',  $this_id);
			if (!empty($filter_top_end) && $filter_top_end !=  $this_id) {
				$first_row .= $filter_top_end;
			}
		}
		// meta on top
		if (!empty($first_row) || !empty($sns_code)) {
			$meta_code_top = '<div class="single_post_meta' . $wow_meta_css . '">' . $first_row . $sns_code . '</div>';
		}

		// **********************************
		//  Meta No.2
		// **********************************
		// Reset params
		$first_row		= '';
		$sns_code 		= '';
		//*** filter hook
		if ($post_type === 'page') {
			$filter_bottom_first = apply_filters('dp_single_meta_bottom_first',  $this_id);
			if (!empty($filter_bottom_first) && $filter_bottom_first !=  $this_id) {
				$first_row = $filter_bottom_first;
			}
		}
		// Date
		if ((bool)$show_date_on_post_meta && !empty($arr_meta['date'])) {
			$first_row .= '<div class="meta meta-date">' . $arr_meta['date'] . $arr_meta['last_update'] . '</div>';
		}
		// Author
		if ((bool)$show_author_on_post_meta) {
			$first_row .= $arr_meta['author'];
		}

		// edit link
		$first_row .= $arr_meta['edit_link'];
		// First row
		if (!empty($first_row)) {
			$first_row = '<div class="first_row">' . $first_row . '</div>';
		}

		//*** filter hook
		if ($post_type === 'page') {
			$filter_bottom_end = apply_filters('dp_single_meta_bottom_end',  $this_id);
			if (!empty($filter_bottom_end) && $filter_bottom_end !=  $this_id) {
				$first_row .= $filter_bottom_end;
			}
		}
		// SNS buttons
		if ((bool)$sns_button_on_meta) {
			$sns_code = $arr_meta['sns_btn'];
		}
		// meta on bottom
		if (!empty($sns_code) || !empty($first_row)) {
			$meta_code_end = '<footer class="single_post_meta bottom">' . $sns_code . $first_row . '</footer>';
		}
	}

	// ***********************************
	// Article area start
	// ***********************************
	while (have_posts()) : the_post(); ?>
		<article id="<?php echo $post_type . '-' . $this_id; ?>" <?php post_class('single-article'); ?>><?php
			// ***********************************
			// Post title
			// ***********************************
			if (!$hide_title) : ?>
				<header>
					<h1 class="entry-title single-title"><span class="<?php echo $wow_title_css; ?>"><?php echo $post_title; ?></span></h1><?php
					// ***********************************
					// Post meta info
					// ***********************************
					echo $meta_code_top;
					?>
				</header><?php
			endif;	// !$hide_title

			// ***********************************
			// Single header widget
			// ***********************************
			if (($post_type === 'page') && is_active_sidebar('widget-post-top') && !post_password_required()) : ?>
				<div class="widget-content single clearfix"><?php dynamic_sidebar('widget-post-top'); ?></div><?php
			endif;	// End of widget

			// ***********************************
			// Main entry
			// *********************************** 
				?>
			<div class="entry entry-content"><?php
				// ***********************************
				// Show eyecatch image
				// ***********************************
				// $flag_eyecatch_first = false;
				// if (has_post_thumbnail() && $post_type === 'page') {
				// 	if ($show_eyecatch_first) {
				// 		if (!($show_eyecatch_force && $eyecatch_on_container)) {
				// 			$flag_eyecatch_first = true;
				// 		}
				// 	} else {
				// 		if ($show_eyecatch_force && !$eyecatch_on_container) {
				// 			$flag_eyecatch_first = true;
				// 		}
				// 	}
				// }

				// if ($flag_eyecatch_first) {
				// 	$image_id	= get_post_thumbnail_id();
				// 	$image_data	= wp_get_attachment_image_src($image_id, array($width, $height), true);
				// 	$image_url 	= is_ssl() ? str_replace('http:', 'https:', $image_data[0]) : $image_data[0];
				// 	$img_tag	= '<img src="' . $image_url . '" class="wp-post-image aligncenter" alt="' . the_title_attribute('before=&after=&echo=0') . '" width="' . $image_data[1] . '" height="' . $image_data[2] . '" />';
				// 	echo '<div class="eyecatch-under-title' . $wow_eyecatch_css . '">' . $img_tag . '</div>';
				// }

				//    $all_ids = get_posts( array(
				// 	'post_type' => 'product',
				// 	'numberposts' => -1,
				// 	'post_status' => 'publish',
				// 	'fields' => 'ids',
				// 	'tax_query' => array(
				// 	   array(
				// 		  'taxonomy' => 'product_tag',
				// 		  'field' => 'slug',
				// 		  'terms' => 'size_new-born',
				// 		  'operator' => 'IN',
				// 	   )
				// 	),
				//  ) );
				//  foreach ( $all_ids as $id ) {
				// 	echo $id.'<br>';
				//  }
				// echo "<br>";

				// $terms = get_terms('product_tag');
				// foreach ($terms as $term) {
				// 	echo urldecode($term->slug).'X<br>';
				// }

				the_content();
				?>
				<div class="container">
					<form id="ch22-search-form">

						<div>
						<?php
							$tags = get_terms('product_tag');
							if ($tags) : ?>
								<h3>お探しの性別は？</h3>
								<div id="checks_xtag">
								<?php
									$checkboxes = '';
									foreach ($tags as $tag) {
										// xmas性別タグのみ抽出
										if(strstr($tag->slug, 'xmas_')){
											$checkboxes .= '<label for="tag-' . $tag->term_id . '">' . 
												'<input type="checkbox" name="tagx[]" value="' . $tag->slug . '" id="tag-' . $tag->term_id . '" class="check_xtag" />' .
												str_replace('Xmas','',$tag->name) . '</label>' . ' ';
										}
									}
									print $checkboxes;?>
								</div>
							<?php endif;	
								$tags = get_terms('product_tag');
								if ($tags) : ?>
									<h3>お探しのサイズは？</h3>
									<label for ="check_all_tag"><input type="checkbox" id="check_all_tag" name="check_all_tag">すべて</label>
									<div id="checks_tag">
									<?php
										$checkboxes = '';
										foreach ($tags as $tag) {
											// サイズタグのみ抽出
											if(strstr($tag->slug, 'size_')){
												$checkboxes .= '<label for="tag-' . $tag->term_id . '">' . 
													'<input type="checkbox" name="tag[]" value="' . $tag->slug . '" id="tag-' . $tag->term_id . '" class="check_tag" />' .
													$tag->name . '</label>' . ' ';
											}
										}
										print $checkboxes;?>
									</div>
								<?php endif;								
								$blands = get_terms('product_cat', 'parent=66'); // &number=9');
								if ($blands) : ?>
									<h3>お好みのブランドは？</h3>
									<label for ="check_all_bland"><input type="checkbox" id="check_all_bland" name="check_all_bland">すべて</label>
									<div id="checks_bland">
									<?php
									$checkboxes = '';
									foreach ($blands as $bland) {
										// 上位ブランドタグのみ抽出
										$checkboxes .= '<input type="checkbox" name="bland[]" value="' . $bland->slug . '" id="bland-' . $bland->term_id . '" class="check_bland" />' .
											'<label for="bland-' . $bland->term_id . '">' . $bland->name . '</label>' . '<BR>';
										if($bland->slug === 'rock-your-baby'){
										break;
										}
									}
									// $checkboxes .= '<input type="checkbox" name="" value="" id="bland-any" />' .
									// 	'<label for="bland-any">何でもＯＫ</label>';
									print $checkboxes; ?>
									</div>
								<?php endif; ?>
						<!-- </div>
						<div>
							<select name="and-or" id="select-and-or">
								<option value="OR">いずれかのタグを含む</option>
								<option value="AND">チェックした全てのタグを含む</option>
							</select> -->
							<button class="btn btn-danger btn-lg btn-block" id="ch22-search-button" type="button">この条件で検索！</button>
						</div>

					</form>
				</div>

				<div id="ch22-search-result">
					<!-- ここが書き変わる！ -->
				</div>



				<?php
				// ***********************************
				// Paged navigation
				// ***********************************
				$link_pages = wp_link_pages(array(
					'before' => '',
					'after' => '',
					'next_or_number' => 'number',
					'echo' => '0'
				));
				if ($link_pages != '') {
					echo '<nav class="navigation"><div class="dp-pagenavi clearfix">';
					if (preg_match_all("/(<a [^>]*>[\d]+<\/a>|[\d]+)/i", $link_pages, $matched, PREG_SET_ORDER)) {
						foreach ($matched as $link) {
							if (preg_match("/<a ([^>]*)>([\d]+)<\/a>/i", $link[0], $link_matched)) {
								echo "<a class=\"page-numbers\" {$link_matched[1]}><span>{$link_matched[2]}</span></a>";
							} else {
								echo "<span class=\"page-numbers current\">{$link[0]}</span>";
							}
						}
					}
					echo '</div></nav>';
				} ?>
			</div><?php 	// End of class="entry"
					// ***********************************
					// Single footer widget
					// ***********************************
					if ($post_type === 'page' && is_active_sidebar('widget-post-bottom') && !post_password_required()) : ?>
				<div class="widget-content single clearfix"><?php dynamic_sidebar('widget-post-bottom'); ?></div><?php
					endif;
					// **********************************
					// Meta
					// **********************************
					echo $meta_code_end; ?>
		</article><?php
					// ***********************************
					// Comments
					// ***********************************
					comments_template();
				endwhile;	// End of (have_posts())

				// ***********************************
				// Content bottom widget
				// ***********************************
				if (is_active_sidebar('widget-content-bottom')) : ?>
		<div class="widget-content bottom clearfix"><?php dynamic_sidebar('widget-content-bottom'); ?></div><?php
			endif;
		else :	// have_posts()
			// Not found...
			include_once(TEMPLATEPATH . '/not-found.php');
		endif;	// End of have_posts()
				?>
</div>

<script type="text/javascript">
	window.addEventListener("load", function() {
		document.getElementById("ch22-search-button").addEventListener("click",
			function() {
				var formDatas = document.getElementById("ch22-search-form");
				var resultDatas = new FormData(formDatas);

				var XHR = new XMLHttpRequest();
				XHR.open("POST", "<?php echo home_url('christmas2020-searchresult'); ?>", true);
				XHR.send(resultDatas);
				XHR.onreadystatechange = function() {
					if (XHR.readyState == 4 && XHR.status == 200) {
						document.getElementById("ch22-search-result").innerHTML = XHR.responseText;
					}
				};
			}, false);
	}, false);

	$(function(){
		$('#check_all_tag').on('change', function() {
			// 「選択肢」のチェック状態を切替える
			$('.check_tag').prop('checked', $(this).is(':checked'));
		});
		$('#check_all_bland').on('change', function() {
			// 「選択肢」のチェック状態を切替える
			$('.check_bland').prop('checked', $(this).is(':checked'));
		});
	});
	
</script>

<?php // end of .content
		// **********************************
		// Sidebar
		// **********************************
		if ($COLUMN_NUM === 2) get_sidebar();
		// **********************************
		// Footer
		// **********************************
		get_footer();
