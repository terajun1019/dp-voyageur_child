<?php

/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php /* translators: %s: Customer first name */ ?>
<!-- <p>
<?php
// printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name()));
?>
</p> -->
<?php /* translators: %s: Site title */ ?>
<!-- <p>
<?php
// esc_html_e('We have finished processing your order.', 'woocommerce');
?>
</p> -->

<?php
/**
 * @hooked Kadence_Woomail_Designer::email_main_text_area
 */
do_action( 'kadence_woomail_designer_email_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * 配送日時をcustom field suite から取得して表示 by Atsushi
 */

$orderid = $order->id;
global $cfs;
$number = $cfs->get('j_tracking_number',$orderid);
$methods = $cfs->get('j_shipping_method',$orderid);
$sdate = $cfs->get('j_shipping_date',$orderid); 
$msg = $cfs->get('j_shipping_msg',$orderid);
$cpurl = 'https://trackings.post.japanpost.jp/services/srv/search/?requestNo1=%s&search.x=64&search.y=26&startingUrlPatten=&locale=ja';
$ymturl = 'http://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id=%s';

// 配送方法とURLを先に取得
if(!empty($methods)){
	foreach($methods as $method){
		switch ($method) {
			case 'クリックポスト':
			case 'レターパックライト':
			case 'レターパックプラス':
				$surl = sprintf($cpurl, $number);
				$mtd = $method;
				break;
			case 'ヤマト運輸':
				$surl = sprintf($ymturl, $number);
				$mtd = $method;
				break;
			default:
				$surl = '';
				$mtd = '';
				break;
		}
	}
}

if(!empty($number)){
	echo '配送番号：'. ($surl == ''?$number:'<a href="'.$surl.'" target="_blank">'.$number.'</a>').'<br>';
}
if(!empty($mtd)){
	echo '配送方法：'.$mtd.'<br>';	
}
if(!empty($sdate)){
	echo '配送日　：'.$sdate.'<br>';
}
if(!empty($msg)){
	echo '===============<BR>';
	echo $msg;
}

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
	echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
