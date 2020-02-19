<?php 
/*
Plugin Name: Gallery Captions for WooCommerce
Plugin URI: 
Description: Adds captions to the default WooCommerce gallery on the single-product page.
Version: 1.0
Author: John Beales
Author URI: https://johnbeales.com
Text Domain: woo-gallery-captions
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WC tested up to: 3.9.2
WC requires at least: 3.4




*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function gcw_insert_captions( $html, $attachment_id ) {

	$captions = '';

	$title = get_post_field( 'post_title', $attachment_id );
	if( !empty( $title ) ) {
		$captions .= '<h5>' . esc_html( $title ) . '</h5>';
	}

	$caption = get_post_field( 'post_excerpt', $attachment_id );
	if( !empty( $caption ) ) {
		$captions .= '<p>' . esc_html( $caption ) . '</p>';
	}

	if( !empty( $captions ) ) {
		$captions = '<div class="gcw-caption">' . $captions . '</div>';
		
		$html = preg_replace('~<\/div>$~', $captions . '</div>', $html );
	}

	return $html;
}

add_filter( 'woocommerce_single_product_image_thumbnail_html', 'gcw_insert_captions', 10, 2 );


function gcw_enquque_js() {

	// check if WooCommerce is actually running
	if ( ! did_action( 'before_woocommerce_init' ) ) {
        return;
    }
    if( is_product() ) {
    	$product = wc_get_product();
    	if($product->get_type() === 'variable' ) {
    		wp_enqueue_script('gcw-variable-product', plugins_url( 'js/gcw-variable-product.js', __FILE__ ), array('jquery'));
    	}
    }
}
add_action( 'wp_enqueue_scripts', 'gcw_enquque_js' );

