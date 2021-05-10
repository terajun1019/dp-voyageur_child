<?php
/*
 * enqueue script for parent theme stylesheeet
 */
add_action( 'wp_enqueue_scripts', 'childtheme_parent_styles');        
function childtheme_parent_styles() {
 
    // enqueue style
    wp_enqueue_style( 'parent', get_template_directory_uri().'/style.css' );      
    //  Bootstrapのスクリプトとスタイルをエンキュー（特定のページのみ、通常読込すると支払画面が崩れるので要注意！！）
    if(is_page("christmas2020")){
      wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/lib/bootstrap-4.5.3-dist/css/bootstrap.min.css');
      wp_enqueue_script( 'bootstrap-script', get_stylesheet_directory_uri() . '/lib/bootstrap-4.5.3-dist/js/bootstrap.min.js', array(), '1.0.0', true );                 
    }
    // 子テーマ用スタイルシート
    wp_enqueue_style( 'childstyle', get_stylesheet_directory_uri().'/style.css' );    

}

///////////////////////////////////////
// カスタムボックスの追加
///////////////////////////////////////
add_action('admin_menu', 'add_redirect_custom_box');
if ( !function_exists( 'add_redirect_custom_box' ) ):
function add_redirect_custom_box(){

  //リダイレクト
  // add_meta_box( 'singular_redirect_settings', 'リダイレクト', 'redirect_custom_box_view', 'post', 'side' );
  // add_meta_box( 'singular_redirect_settings', 'リダイレクト', 'redirect_custom_box_view', 'page', 'side' );
  add_meta_box( 'singular_redirect_settings', 'リダイレクト', 'redirect_custom_box_view', 'news', 'side' );
}
endif;

///////////////////////////////////////
// リダイレクト
///////////////////////////////////////
if ( !function_exists( 'redirect_custom_box_view' ) ):
function redirect_custom_box_view(){
  $redirect_url = get_post_meta(get_the_ID(),'redirect_url', true);

  echo '<label for="redirect_url">リダイレクトURL</label>';
  echo '<input type="text" name="redirect_url" size="20" value="'.esc_attr(stripslashes_deep(strip_tags($redirect_url))).'" placeholder="https://" style="width: 100%;">';
  echo '<p class="howto">このページに訪れるユーザーを設定したURLに301リダイレクトします。</p>';
}
endif;

add_action('save_post', 'redirect_custom_box_save_data');
if ( !function_exists( 'redirect_custom_box_save_data' ) ):
function redirect_custom_box_save_data(){
  $id = get_the_ID();
  //リダイレクトURL
  if ( isset( $_POST['redirect_url'] ) ){
    $redirect_url = $_POST['redirect_url'];
    $redirect_url_key = 'redirect_url';
    add_post_meta($id, $redirect_url_key, $redirect_url, true);
    update_post_meta($id, $redirect_url_key, $redirect_url);
  }
}
endif;

//リダイレクトURLの取得
if ( !function_exists( 'get_singular_redirect_url' ) ):
function get_singular_redirect_url(){
  return trim(get_post_meta(get_the_ID(), 'redirect_url', true));
}
endif;

//リダイレクト処理
if ( !function_exists( 'redirect_to_url' ) ):
function redirect_to_url($url){
  header( "HTTP/1.1 301 Moved Permanently" );
  header( "location: " . $url  );
  exit;
}
endif;

//URLの正規表現
define('URL_REG_STR', '(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)');
define('URL_REG', '/'.URL_REG_STR.'/');

//リダイレクト
add_action( 'wp','wp_singular_page_redirect', 0 );
if ( !function_exists( 'wp_singular_page_redirect' ) ):
function wp_singular_page_redirect() {
  //リダイレクト
  if (is_singular() && $redirect_url = get_singular_redirect_url()) {
    //URL形式にマッチする場合
    if (preg_match(URL_REG, $redirect_url)) {
      redirect_to_url($redirect_url);
    }
  }
}
endif;

// **************************
// 20.12.31 soldoutバッジ追加
add_action('woocommerce_before_shop_loop_item_title', 'lb_display_badge_on_archive');
function lb_display_badge_on_archive(){
  global $product;
  $newness_days = 30; // NEWタグを表示する日数
  $created = strtotime($product->get_date_created()); // 商品作成日

  if(!$product->is_in_stock()){
    // 売り切れ
    echo '<div class="lb_badge badge_soldout"><p>SOLD OUT</p></div>';
  }elseif((time()-(60*60*24*$newness_days)) < $created){
    // 新商品
    echo '<div class="lb_badge badge_new"><p>NEW!</p></div>';
  }
}

add_action('woocommerce_before_single_product_summary', 'lb_display_badge_on_single');
function lb_display_badge_on_single(){
  global $product;
  $newness_days = 30; // NEWタグを表示する日数
  $created = strtotime($product->get_date_created()); // 商品作成日

  if(!$product->is_in_stock()){
    // 売り切れ
    echo '<div class="lb_badge_single badge_soldout"><p>SOLD OUT</p></div>';
  }elseif((time()-(60*60*24*$newness_days)) < $created){
    // 新商品
    echo '<div class="lb_badge_single badge_new"><p>NEW!</p></div>';
  }
}

// test comments-3