<?php


class Resource_Archive_Filter {

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
		// add script style 
		add_action('wp_enqueue_scripts', array($this, 'enqueue_Scripts_Styles'));

		// archive page 
		add_filter( 'archive_template', array($this,'archive_page_view') ) ;

		// ajax response
		add_action( 'wp_ajax_nopriv_get_filter_html', array($this ,'ajax_handler') );
		add_action( 'wp_ajax_get_filter_html', array($this ,'ajax_handler') );
	}

	/**
	 * Scripts Styles 
	 */
	public function enqueue_Scripts_Styles()
	{
		

		// script
		wp_enqueue_script('addweb-public-script', ADDWEB_URL.'assets/js/public.js', array('jquery'), '1.0-'.time() );
		$localizeArray = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('addweb-ajax-nonce'),
		);
		wp_localize_script('addweb-public-script', 'ADDWEB', $localizeArray);
	}


	public function archive_page_view()
	{
		if ( is_post_type_archive ( 'resource' ) ) {
			$tpl = ADDWEB_PATH . 'tmpl/resource-archive.php';
		}
		return $tpl;
	}


	public function ajax_handler()
	{
		if ( !wp_verify_nonce( $_POST['nonce'], "addweb-ajax-nonce")) {
			_e( 'Sequrity issue can not process request.', 'add-web' );
			exit();
		}  

		$post = $_POST;
		$data = array();

		$dataArray = $post['dataArray'];

		$topics = [];
		$types  = [];

		foreach ($dataArray as $val) {
			if( $val['name'] == 'search' && !empty($val['value']) ){
				$search = $val['value'];
			}
			if( str_contains($val['name'], 'topic_') ){
				array_push($topics, str_replace('topic_', '', $val['name']) );
			}
			if( str_contains($val['name'], 'type_') ){
				array_push($types, str_replace('type_', '', $val['name']) );
			}
		}
		
		// echo '<pre>';
		// print_r($search);
		// print_r($topics);
		// print_r($types);
		// print_r($post);
		// echo '</pre>';
		// die();

		$args = array(
			'post_status' => 'publish',
			'post_type' => 'resource',
			'posts_per_page' => 3,
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'resource_type',
					'field' => 'slug',
					'terms' => $types,
				),
				array(
					'taxonomy' => 'resource_topic',
					'field' => 'slug',
					'terms' => $topics,
				),
			),
			's' => $search
		);

   		$posts = new WP_Query($args);

		ob_start();
		
		if ( $posts->have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">
				<?php _e( 'Resource Archives', 'add-web' ); ?>
				</h1>
			</header><!-- .archive-header -->

			<?php
			// Start the Loop.
			while ( $posts->have_posts() ) :
				$posts->the_post();

				get_template_part( 'content', get_post_format() );

			endwhile;

			twentytwelve_content_nav( 'nav-below' );

		else : 
			get_template_part( 'content', 'none' ); 
		endif; 
		

		$html = ob_get_clean();

		wp_reset_query();

		echo $html;

		die();
	}

}