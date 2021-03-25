<?php
/**
 * Customizations for Storefront
 *
 * @package dottxado\theartisinner
 */

namespace dottxado\theartisinner;

/**
 * Class StorefrontMods
 *
 * @package dottxado\theartisinner
 */
class StorefrontMods {

	/**
	 * Singleton instance
	 *
	 * @var StorefrontMods $instance This instance.
	 */
	private static $instance = null;

	/**
	 * Get class instance
	 *
	 * @return StorefrontMods
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			$c              = __CLASS__;
			self::$instance = new $c();
		}

		return self::$instance;
	}

	/**
	 * StorefrontMods constructor.
	 */
	private function __construct() {
		add_filter( 'storefront_credit_link', '__return_false' );
		add_filter( 'storefront_customizer_css', array( $this, 'added_inline_css_customizations' ), 999 );
		add_filter(
			'storefront_customizer_woocommerce_css',
			array(
				$this,
				'added_inline_woo_css_customizations',
			),
			999
		);
		add_action( 'storefront_footer', 'storefront_footer_widgets', 30 );
		add_filter( 'storefront_copyright_text', '__return_empty_string' );
		add_filter( 'storefront_credit_links_output', array( $this, 'add_logo_in_footer' ) );
		add_action( 'wp', array( $this, 'remove_actions' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'add_my_account_to_secondary_menu' ) );
		add_action( 'storefront_header', 'storefront_header_cart', 30 );
		add_action( 'storefront_header', 'storefront_site_branding', 40 );
		add_action( 'storefront_header', 'storefront_secondary_navigation', 20 );
		add_action( 'storefront_header', 'storefront_product_search', 35 );
		add_filter( 'storefront_setting_default_values', array( $this, 'change_storefront_default_colors' ) );
	}

	/**
	 * Remove Storefront actions
	 */
	public function remove_actions() {
		remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
		remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'storefront_header', 'storefront_header_cart', 60 );
		remove_action( 'storefront_header', 'storefront_site_branding', 20 );
		remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
		remove_action( 'storefront_header', 'storefront_product_search', 40 );

	}

	/**
	 * Change the Storefront default colors
	 *
	 * @param array $defaults The default Storefront colors.
	 *
	 * @return array
	 */
	public function change_storefront_default_colors( $defaults ) {
		$defaults['storefront_text_color']    = '#F1B51C';
		$defaults['storefront_heading_color'] = '#F1B51C';
		$defaults['storefront_accent_color']  = '#DE5807';

		return $defaults;
	}

	/**
	 * Display the site logo in footer instead of the text
	 *
	 * @param string $text The default text to be displayed.
	 *
	 * @return string
	 */
	public function add_logo_in_footer( $text ) {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {

			return get_custom_logo();
		}

		return '';
	}

	/**
	 * Add inline style to Storefront for WooCommerce
	 *
	 * @param string $style The default style.
	 *
	 * @return string $styles the css
	 * @since 2.4.0
	 * @see get_storefront_theme_mods()
	 */
	public function added_inline_woo_css_customizations( $style ) {
		$storefront_theme_mods = self::get_storefront_theme_mods();
		$brighten_factor       = apply_filters( 'storefront_brighten_factor', 25 );
		$darken_factor         = apply_filters( 'storefront_darken_factor', - 25 );

		return $style . '
			
			a.cart-contents:hover,
			.site-header-cart .widget_shopping_cart a:hover,
			.site-header-cart:hover > li > a {
				color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_link_color'],
				( is_color_light( $storefront_theme_mods['header_background_color'] ) ) ? $darken_factor : $brighten_factor ) . ';
			}';
	}

	/**
	 * Get all of the Storefront theme mods.
	 *
	 * @return array $storefront_theme_mods The Storefront Theme Mods.
	 */
	public static function get_storefront_theme_mods() {
		$storefront_theme_mods = array(
			'background_color'            => storefront_get_content_background_color(),
			'accent_color'                => get_theme_mod( 'storefront_accent_color' ),
			'hero_heading_color'          => get_theme_mod( 'storefront_hero_heading_color' ),
			'hero_text_color'             => get_theme_mod( 'storefront_hero_text_color' ),
			'header_background_color'     => get_theme_mod( 'storefront_header_background_color' ),
			'header_link_color'           => get_theme_mod( 'storefront_header_link_color' ),
			'header_text_color'           => get_theme_mod( 'storefront_header_text_color' ),
			'footer_background_color'     => get_theme_mod( 'storefront_footer_background_color' ),
			'footer_link_color'           => get_theme_mod( 'storefront_footer_link_color' ),
			'footer_heading_color'        => get_theme_mod( 'storefront_footer_heading_color' ),
			'footer_text_color'           => get_theme_mod( 'storefront_footer_text_color' ),
			'text_color'                  => get_theme_mod( 'storefront_text_color' ),
			'heading_color'               => get_theme_mod( 'storefront_heading_color' ),
			'button_background_color'     => get_theme_mod( 'storefront_button_background_color' ),
			'button_text_color'           => get_theme_mod( 'storefront_button_text_color' ),
			'button_alt_background_color' => get_theme_mod( 'storefront_button_alt_background_color' ),
			'button_alt_text_color'       => get_theme_mod( 'storefront_button_alt_text_color' ),
		);

		return apply_filters( 'storefront_theme_mods', $storefront_theme_mods );
	}

	/**
	 * Add inline style to Storefront
	 *
	 * @param string $style The default style.
	 *
	 * @return string
	 */
	public function added_inline_css_customizations( $style ) {
		$storefront_theme_mods = self::get_storefront_theme_mods();
		$brighten_factor       = apply_filters( 'storefront_brighten_factor', 25 );
		$darken_factor         = apply_filters( 'storefront_darken_factor', - 25 );

		return $style . '
		.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a,
			.site-title a:hover,
			.site-header ul.menu li.current-menu-item > a {
				color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_link_color'],
				( is_color_light( $storefront_theme_mods['header_background_color'] ) ) ? $darken_factor : $brighten_factor ) . ';
			}
		.has-caption-style-light .coblocks-gallery--item figcaption {
				background: linear-gradient(0deg, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['accent_color'] ) ) . ', 0.93) 6.3%, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['accent_color'] ) ) . ',0.5) 61%, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['accent_color'] ) ) . ',0)) !important
			}
			
			.has-caption-style-dark .coblocks-gallery--item figcaption {
				background: linear-gradient(0deg, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['text_color'] ) ) . ', 0.93) 6.3%, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['text_color'] ) ) . ',0.5) 61%, rgba(' . implode( ',',
				get_rgb_values_from_hex( $storefront_theme_mods['text_color'] ) ) . ',0)) !important
			}
			
			.caption-on-slide .coblocks-gallery--caption a {
			    display: inline-block;
			    padding: 10px;
			    text-decoration: none;
			    background-color:' . $storefront_theme_mods['accent_color'] . ';
			    color: #fff;
			    text-shadow: none;
			}
			
			.storefront-sticky-add-to-cart {
				background-color: ' . $storefront_theme_mods['background_color'] . ';
			}
			
			.storefront-product-pagination a {
				background-color: ' . $storefront_theme_mods['background_color'] . ';
			}
			
			.secondary-navigation a {
				color: ' . $storefront_theme_mods['header_link_color'] . ';
			}
			
			@media screen and ( min-width: 768px ) {
				.secondary-navigation ul.menu a:hover {
					color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_link_color'],
				( is_color_light( $storefront_theme_mods['header_background_color'] ) ) ? $darken_factor : $brighten_factor ) . ';
				}
				.secondary-navigation ul.menu a {
					color: ' . $storefront_theme_mods['header_link_color'] . ';
				}
			}
			';
	}

	/**
	 * Add the link for "My Account" to the Storefront secondary menu
	 *
	 * @param array $args The menu arguments.
	 *
	 * @return mixed
	 */
	public function add_my_account_to_secondary_menu( $args ) {
		if ( 'secondary' !== $args['theme_location'] ) {
			return $args;
		}

		$account_link       = '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '"><i class="fas fa-user"></i></a></li>';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s' . $account_link . '</ul>';

		return $args;
	}

}
