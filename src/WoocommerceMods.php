<?php
/**
 * Customizations for WooCommerce
 *
 * @package dottxado\theartisinner
 */

namespace dottxado\theartisinner;

/**
 * Class WoocommerceMods
 *
 * @package dottxado\theartisinner
 */
class WoocommerceMods {
	/**
	 * Singleton instance
	 *
	 * @var WoocommerceMods $instance This instance.
	 */
	private static $instance = null;

	/**
	 * Get class instance
	 *
	 * @return WoocommerceMods
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			$c              = __CLASS__;
			self::$instance = new $c();
		}

		return self::$instance;
	}

	/**
	 * WoocommerceMods constructor.
	 */
	private function __construct() {
		add_action( 'wp', array( $this, 'remove_actions' ) );
		add_filter( 'single_product_archive_thumbnail_size', array( $this, 'change_archive_thumbnail_dimension' ) );
		add_action( 'init', array( $this, 'remove_hooks' ) );
		add_filter( 'woocommerce_post_class', array( $this, 'add_class_to_product_archive_grid' ) );
	}

	public function remove_hooks() {

	}

	/**
	 * Remove WooCommerce actions
	 */
	public function remove_actions() {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}

	/**
	 * Change the dimension of the image for the archive page
	 *
	 * @param string $size The original thumbnail size.
	 *
	 * @return string
	 */
	public function change_archive_thumbnail_dimension( $size ) {
		return 'full';
	}

	/**
	 * Add a class to the container of products into the archive page
	 *
	 * @param array $classes The container classes.
	 *
	 * @return array
	 */
	public function add_class_to_product_archive_grid( $classes ) {
		$classes[] = 'grid-products-item';
		return $classes;
	}

}
