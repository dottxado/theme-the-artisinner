<?php
/**
 * All the necessary to setup the theme
 *
 * @package dottxado\theartisinner
 */

namespace dottxado\theartisinner;

/**
 * Class ThemeSetup
 *
 * @package dottxado\theartisinner
 */
class ThemeSetup {

	/**
	 * Singleton instance
	 *
	 * @var ThemeSetup $instance This instance.
	 */
	private static $instance = null;

	/**
	 * Get class instance
	 *
	 * @return ThemeSetup
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			$c              = __CLASS__;
			self::$instance = new $c();
		}

		return self::$instance;
	}

	/**
	 * ThemeSetup constructor.
	 */
	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 11 );
		add_action( 'wp_head', array( $this, 'add_fonts' ) );
		add_action( 'wp_head', array( $this, 'add_masonry' ) );
		add_filter( 'storefront_customizer_css', array( $this, 'add_gutenberg_palette' ), 1000 );
	}

	/**
	 * Enqueue parent theme style
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'parent-style',
			get_template_directory_uri() . '/style.css',
			array(),
			filemtime( get_template_directory() . '/style.css' )
		);
	}

	/**
	 * Enqueue public scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'artisinner-scripts',
			get_stylesheet_directory_uri() . '/scripts.min.js',
			array( 'jquery' ),
			filemtime( get_stylesheet_directory() . '/scripts.min.js' ),
			true
		);
	}

	/**
	 * Setups for the child theme
	 */
	public function theme_setup() {
		load_theme_textdomain( 'dottxado-the-artiinner', get_stylesheet_directory() . '/languages' );
		add_theme_support(
			'editor-color-palette',
			$this->get_palette_colors_for_gutenberg()
		);

	}

	/**
	 * Add google fonts
	 */
	public function add_fonts() {
		?>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Allura&display=swap" rel="stylesheet">
		<?php
	}

	/**
	 * Included masonry library
	 */
	public function add_masonry() {
		?>
		<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
		<?php
	}

	/**
	 * Add to the inline style the classes for the palette used in Gutenberg
	 *
	 * @param string $style The default style.
	 *
	 * @return string
	 */
	public function add_gutenberg_palette( $style ) {
		$palette = $this->get_palette_colors_for_gutenberg();
		$tmp     = '';
		foreach ( $palette as $color ) {
			$tmp .= '.has-' . $color['slug'] . '-color {color: ' . $color['color'] . '}';
			$tmp .= '.has-' . $color['slug'] . '-background-color {background-color: ' . $color['color'] . '}';
		}

		return $style . $tmp;
	}

	/**
	 * Get the palette configured into the customizer
	 *
	 * @return array
	 */
	private function get_palette_colors_for_gutenberg() {
		$palette              = StorefrontMods::get_storefront_theme_mods();
		$palette              = array_unique( array_values( $palette ) );
		$editor_color_palette = array();
		$counter              = 1;
		foreach ( $palette as $single_color ) {
			$editor_color_palette[] = array(
				'name'  => 'Palette ' . $counter,
				'slug'  => 'artisinner-palette-' . $counter,
				'color' => $single_color,
			);
			$counter ++;
		}

		return $editor_color_palette;
	}

	/**
	 * Check if the dependency of this theme is satisfied
	 *
	 * @return bool
	 */
	public static function check_dependency() {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$site_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$dependency   = 'woocommerce/woocommerce.php';
		if ( is_multisite() ) {

			if ( is_plugin_active_for_network( $dependency ) ) {
				return true;
			}
		}
		if ( stripos( implode( $site_plugins ), $dependency ) ) {
			return true;
		}

		return false;
	}

}
