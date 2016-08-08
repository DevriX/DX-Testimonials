<?php
/*
 * Plugin Name: DX Testimonials
 * Description: This plugin registers the CPT for Testimonials
 * Version: 1.0
 * Author: DevriX
 * Author URI: http://devrix.com
 * License: GPLv2
*/
// Define some variables
if ( ! defined( 'DX_TESTIMONIAL_POST_TYPE' ) ) {
	define( 'DX_TESTIMONIAL_POST_TYPE', 'testimonial' ); // plugin Prefix
}
if ( ! class_exists( 'DX_Testimonials' ) ) :
class DX_Testimonials {
	public function __construct() {
		//Register custom post type
		add_action( 'init', array( $this, 'register_dx_testimonial_post_type' ) );
		add_action( 'init', array( $this, 'register_dx_testimonial_category' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'dx_testimonial_add_metabox' ), 99 );
		add_action( 'save_post', array( $this, 'dx_testimonial_save_meta' ) );
	}
	
	function register_dx_testimonial_post_type() {
		$labels = array(
			'name'              => __( 'DX Testimonials', 'dx-testimonial' ),
			'singular_name'		=> __( 'Testimonial', 'dx-testimonial' ),
			'menu_name'         => __( 'DX Testimonials', 'dx-testimonial' ),
			'add_new_item'		=> __( 'Add New Testimonial', 'dx-testimonial' )
		);
		$args = array(
			'labels'              => $labels,
			'hierarchical'        => true,
			'description'         => __( 'Testimonial', 'dx-testimonial' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon'           => null,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor', 'revisions' )
		);
		register_post_type( DX_TESTIMONIAL_POST_TYPE, $args );
	}
	
	function register_dx_testimonial_category() {
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'dx-testimonial' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name' , 'dx-testimonial' ),
			'search_items'      => __( 'Search Categories', 'dx-testimonial' ),
			'all_items'         => __( 'All Categories', 'dx-testimonial' ),
			'parent_item'       => __( 'Parent Category', 'dx-testimonial' ),
			'parent_item_colon' => __( 'Parent Category:', 'dx-testimonial' ),
			'edit_item'         => __( 'Edit Category', 'dx-testimonial' ),
			'update_item'       => __( 'Update Category', 'dx-testimonial' ),
			'add_new_item'      => __( 'Add New Category', 'dx-testimonial' ),
			'new_item_name'     => __( 'New Category Name', 'dx-testimonial' ),
			'menu_name'         => __( 'Categories', 'dx-testimonial' ),
		);
		
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'category' ),
		);
		
		register_taxonomy( 'testimonial-category', array( DX_TESTIMONIAL_POST_TYPE ), $args );
	}
	
	public function dx_testimonial_add_metabox() {
		$current_screen = get_current_screen();
		$post_types = array( DX_TESTIMONIAL_POST_TYPE );
		
		if ( in_array( $current_screen->post_type, $post_types ) ) {
	
			add_meta_box(
				'dx_testimonial_metabox',
				__( 'Testimonial Details', 'dx-testimonial' ),
				array( $this, 'dx_testimonial_details' ),
				$post_types,
				'normal',
				'low'
			);
		}
	}
	
	/**
	 * Creates Testimonials fields for the metabox
	 * @param unknown $post
	 */
	public function dx_testimonial_details( $post ) {
		$post_custom = get_post_custom( $post->ID );
		$dx_testimonial_author = '';
		$dx_testimonial_author_image = '';
		$dx_testimonial_link = '';
		$dx_testimonial_date = '';
		if ( ! empty( $post_custom ) && is_array( $post_custom ) ) {

			$dx_testimonial_author = ! empty( $post_custom['dx_testimonial_author'][0] ) ? $post_custom['dx_testimonial_author'][0] : '';

			$dx_testimonial_author_image = ! empty( $post_custom['dx_testimonial_author_image'][0] ) ? $post_custom['dx_testimonial_author_image'][0] : '';

			$dx_testimonial_link = ! empty( $post_custom['dx_testimonial_link'][0] ) ? $post_custom['dx_testimonial_link'][0] : '';

			$dx_testimonial_date = ! empty( $post_custom['dx_testimonial_date'][0] ) ? $post_custom['dx_testimonial_date'][0] : '';

		}
		?>
		<div class="testimonail-metabox">
			<div>
				<label for="dx_testimonial_author"><?php _e( 'Testimonial Author', 'dx-testimonial' ); ?></label>
				<br />
				<input type="text" id="dx_testimonial_author" name="dx_testimonial_author" value="<?php echo $dx_testimonial_author; ?>" />
			</div>
			<div>
				<label for="dx_testimonial_author_image"><?php _e( 'Testimonial Author Image URL', 'dx-testimonial' ); ?></label>
				<br />
				<input type="text" id="dx_testimonial_author_image" name="dx_testimonial_author_image" value="<?php echo $dx_testimonial_author_image; ?>" />
			</div>
			<div>
				<label for="dx_testimonial_link"><?php _e( 'Testimonial Link (See More link)', 'dx-testimonial' ); ?></label>
				<br />
				<input type="text" id="dx_testimonial_link" name="dx_testimonial_link" value="<?php echo $dx_testimonial_link; ?>" />
			</div>
			<div>
				<label for="dx_testimonial_date"><?php _e( 'Testimonial Date', 'dx-testimonial' ); ?></label>
				<br />
				<input type="text" id="dx_testimonial_date" name="dx_testimonial_date" value="<?php echo $dx_testimonial_date; ?>" />
			</div>
		</div>
	    <?php
	}
	
	/**
	 * Save Testimonials Metabox details
	 * 
	 * @param unknown $post_id
	 */
	function dx_testimonial_save_meta( $post_id ) {
		$post_type = get_post_type( $post_id );
		$post_types = array(
			DX_TESTIMONIAL_POST_TYPE 
		);
		
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		
		if ( empty( $post_id ) ) {
			return;
		}
		
		if ( ! in_array( $post_type, $post_types ) ) {
			return;
		}
		
		if ( ! empty( $_POST['dx_testimonial_author'] ) ) {
			$dx_testimonial_author = esc_attr( $_POST['dx_testimonial_author'] );
			update_post_meta( $post_id, 'dx_testimonial_author', $dx_testimonial_author );
		} else {
			delete_post_meta( $post_id, 'dx_testimonial_author' );
		}
		
		if ( ! empty( $_POST['dx_testimonial_author_image'] ) ) {
			$dx_testimonial_author_image = esc_url( $_POST['dx_testimonial_author_image'] );
			update_post_meta( $post_id, 'dx_testimonial_author_image', $dx_testimonial_author_image );
		} else {
			delete_post_meta( $post_id, 'dx_testimonial_author_image' );
		}
		
		if ( ! empty( $_POST['dx_testimonial_link'] ) ) { 
			$dx_testimonial_link = esc_url( $_POST['dx_testimonial_link'] );
			update_post_meta( $post_id, 'dx_testimonial_link', $dx_testimonial_link );
		} else {
			delete_post_meta( $post_id, 'dx_testimonial_link' );
		}
		
		if ( ! empty( $_POST['dx_testimonial_date'] ) ) {
			$dx_testimonial_date = esc_attr( $_POST['dx_testimonial_date'] );
			update_post_meta( $post_id, 'dx_testimonial_date', $dx_testimonial_date );
		} else {
			delete_post_meta( $post_id, 'dx_testimonial_date' );
		}
	}
	/**
	 * Display a single testimonial by given ID
	 * 
	 * @param integer $id
	 */
	static function get_testimonial( $id ) {
		if ( empty( $id ) ) { 
			return; 
		}
		
		$defaults = array(
			'dx_testimonial_author' => '',
			'dx_testimonial_author_image' => '',
			'dx_testimonial_link' => '',
			'dx_testimonial_date' => '',
		);
		$post_custom = get_post_custom( $id );
		$testimonial = wp_parse_args( $post_custom, $defaults );
		$testimonial_content  = get_post_field( 'post_content', $id );
		$testimonial_title = get_post_field( 'post_title', $id);
		$testimonial['dx_testimonial_content'] = ! empty( $testimonial_content ) ? $testimonial_content : '';
		$testimonial['dx_testimonial_title'] = ! empty( $testimonial_title ) ? $testimonial_title : '';
		return $testimonial;		
	}
	
	/**
	 * A function that returns the result from get_posts() Query, based on Taxonomy query
	 *  
	 * @param string $taxonomy
	 * @param string $taxonomy_name
	 */
	static function get_testimonials_by_taxonomy( $taxonomy = null, $taxonomy_name = null ) {
		
		if ( empty( $taxonomy ) || empty( $taxonomy_name ) ) {
			return;
		}
		$testimonials = '';
		
		$args = array(
			'post_type' => DX_TESTIMONIAL_POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => 500,
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $taxonomy_name,
				),
			),
		);
		
		$testimonials_array = get_posts( $args );

		if ( ! empty( $testimonials_array ) ) {
			$testimonials = $testimonials_array;	
		}
		
		return $testimonials;
	}
}
$dx_testimonials = new DX_Testimonials();
endif;