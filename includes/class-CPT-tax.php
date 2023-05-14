<?php

class ADDWEB_CPT {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function getInstance()
	{
		// If the single instance hasn't been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct()
	{
		// Register CPT 
		add_action('init', array( $this, 'register_cpt' ) );

		// Register taxonomy for CPT "Resource"
		add_action('init', array( $this, 'register_taxonomy' ) );

		// resource per page 
		add_action( 'pre_get_posts', array( $this ,'resources_per_page' ) );
	}


	public function resources_per_page()
	{
		if(!is_admin() && is_post_type_archive('resource')){
			set_query_var('posts_per_page', 3);
		}
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_cpt() {
		
		$supports = array(
			'title', // post title
			'editor', // post content
			'show_in_rest' => true,
			'author', // post author
			'thumbnail', // featured images
			'excerpt', // post excerpt
			'custom-fields', // custom fields
			'comments', // post comments
			'revisions', // post revisions
			//'post-formats', // post formats
		);
		$labels = array(
			'name' => _x('Resources', 'plural'),
			'singular_name' => _x('Resource', 'singular'),
			'menu_name' => _x('Resources', 'admin menu'),
			'name_admin_bar' => _x('Resources', 'admin bar'),
			'add_new' => _x('Add New', 'add new'),
			'add_new_item' => __('Add New Resource'),
			'new_item' => __('New Resource'),
			'edit_item' => __('Edit Resource'),
			'view_item' => __('View Resource'),
			'all_items' => __('All Resources'),
			'search_items' => __('Search Resources'),
			'not_found' => __('No Resource found.'),
		);
		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'resource'),
			'has_archive' => true,
			'hierarchical' => false,
		);

		// retister post type resource
		register_post_type('resource', $args);
	}

	/**
	 * Save ticker notification 
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function register_taxonomy( $request )
	{
		// labels for texonomy Resource Type 
		$labels_type = array(
			'name' => _x( 'Resource Types', 'taxonomy general name' ),
			'singular_name' => _x( 'Resource Type', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Resource Type' ),
			'all_items' => __( 'All Resource Types' ),
			'parent_item' => __( 'Parent Resource Type' ),
			'parent_item_colon' => __( 'Parent Resource Type:' ),
			'edit_item' => __( 'Edit Resource Type' ), 
			'update_item' => __( 'Update Resource Type' ),
			'add_new_item' => __( 'Add New Resource Type' ),
			'new_item_name' => __( 'New Resource Type Name' ),
			'menu_name' => __( 'Resource Types' ),
		);

		// labels for texonomy Resource Topic 
		$labels_topic = array(
			'name' => _x( 'Resource Topics', 'taxonomy general name' ),
			'singular_name' => _x( 'Resource Topic', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Resource Topic' ),
			'all_items' => __( 'All Resource Topics' ),
			//'parent_item' => __( 'Parent Resource Topic' ),
			//'parent_item_colon' => __( 'Parent Resource Topic:' ),
			'edit_item' => __( 'Edit Resource Topic' ), 
			'update_item' => __( 'Update Resource Topic' ),
			'add_new_item' => __( 'Add New Resource Topic' ),
			'new_item_name' => __( 'New Resource Topic Name' ),
			'menu_name' => __( 'Resource Topics' ),
		);

		// register the taxonomy type
		register_taxonomy('resource_type',array('resource'), array(
			'hierarchical' => true,
			'labels' => $labels_type,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'resource-type' ),
		));
  
		// register the taxonomy topic
		register_taxonomy('resource_topic',array('resource'), array(
			'hierarchical' => false,
			'labels' => $labels_topic,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'resource-topic' ),
		));
	}	
}




