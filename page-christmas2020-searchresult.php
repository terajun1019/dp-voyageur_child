<?php

// get_header();
$arg   = array(
	'posts_per_page' => 999, // 表示する件数
	'orderby'        => 'date', // 日付でソート
	'order'          => 'DESC', // DESCで最新から表示、ASCで最古から表示
	// 'tag'            => 'size_baby', // 表示したいタグのスラッグを指定
	'post_type'		=> 'product',
	// 'category_name' => 'for-boys',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'product_tag',
			'field' => 'slug',
			// 'terms' => array('size_new-born','size_baby'),
			'terms' => $_REQUEST['tagx'],
			'operator' => 'IN',
		),
		array(
			'taxonomy' => 'product_tag',
			'field' => 'slug',
			// 'terms' => array('size_new-born','size_baby'),
			'terms' => $_REQUEST['tag'],
			'operator' => 'IN',
		),
		array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => is_null($_REQUEST['bland']) ? 'any' : $_REQUEST['bland'],
			'operator' => is_null($_REQUEST['bland']) ? 'NOT IN' : 'IN',
		)
	),
);

$query1 = new WP_Query($args);

// var_dump($_REQUEST);
$posts = get_posts( $arg );
if ( $posts ):
	 ?>
<div class="container">
	<div class="row">
		<?php
		foreach ( $posts as $post ) :
			setup_postdata( $post ); ?>
			<div class="col-lg-3 col-md-4 col-6 animated swing">
				<div class="card my-1">
					<div class="card-body card-shadow">
						<a href="<?php the_permalink();?>">
							<img src="<?php echo get_the_post_thumbnail_url( $post->ID, 'medium');?>" class="card-img" alt="<?php the_title(); ?>">
							<div class="card-img-overlay">
								<?php the_title();?>
							</div>
						</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
	
<?php
endif;
wp_reset_postdata();
the_content();

// get_footer();
// wp_footer();
