<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
add_action( 'init', 'srm_register_reviews_cpt' );
/**
 * Register a Review post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function srm_register_reviews_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Reviews', 'post type general name', 'starfish' ),
		'singular_name'      => esc_html_x( 'Review', 'post type singular name', 'starfish' ),
		'menu_name'          => esc_html_x( 'Starfish Reviews', 'admin menu', 'starfish' ),
		'name_admin_bar'     => esc_html_x( 'Starfish Review', 'add new on admin bar', 'starfish' ),
		'add_new'            => esc_html_x( 'Add New', 'Review', 'starfish' ),
		'add_new_item'       => esc_html__( 'Add New Review', 'starfish' ),
		'new_item'           => esc_html__( 'New Review', 'starfish' ),
		'edit_item'          => esc_html__( 'Edit Review', 'starfish' ),
		'view_item'          => esc_html__( 'View Review', 'starfish' ),
		'all_items'          => esc_html__( 'All Reviews', 'starfish' ),
		'search_items'       => esc_html__( 'Search Reviews', 'starfish' ),
		'parent_item_colon'  => esc_html__( 'Parent Reviews:', 'starfish' ),
		'not_found'          => esc_html__( 'No Reviews found.', 'starfish' ),
		'not_found_in_trash' => esc_html__( 'No Reviews found in Trash.', 'starfish' )
	);

	$args = array(
		'labels'             => $labels,
    'description'        => esc_html__( 'Description.', 'starfish' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'starfish-review' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 10,
		'menu_icon' => 'dashicons-star-filled',
		'supports'           => array( 'title', 'editor' )
	);

	register_post_type( 'starfish_review', $args );
}

function srm_disable_new_review_link() {
	// Hide sidebar link
	global $submenu;
	unset($submenu['edit.php?post_type=starfish_review'][10]);

}
add_action('admin_menu', 'srm_disable_new_review_link');

/**
* Reorder Reveiws admin column
*/
function srm_manage_starfish_review_posts_columns($post_columns) {
    $post_columns = array(
        'cb' => $post_columns['cb'],
        'title' => esc_html__( 'Date & Time', 'starfish' ),
				'track_id' => esc_html__( 'ID', 'starfish' ),
				'reviewer' => esc_html__( 'Reviewer', 'starfish' ),
				'feedback' => esc_html__( 'Feedback', 'starfish' ),
				'message' => esc_html__( 'Message', 'starfish' ),
        'funnel' => esc_html__( 'Funnel', 'starfish' ),
				'destination' => esc_html__( 'Destination', 'starfish' )
        );
    return $post_columns;
}
add_action('manage_starfish_review_posts_columns', 'srm_manage_starfish_review_posts_columns');

/**
* add order column to admin listing screen for reviews
*/
function srm_add_new_starfish_review_column($review_columns) {
	$review_columns['track_id'] = esc_html__( 'ID', 'starfish' );
	$review_columns['reviewer'] = esc_html__( 'Reviewer', 'starfish' );
	$review_columns['feedback'] = esc_html__( 'Feedback', 'starfish' );
	$review_columns['message'] = esc_html__( 'Message', 'starfish' );
	$review_columns['funnel'] = esc_html__( 'Funnel', 'starfish' );
	$review_columns['destination'] = esc_html__( 'Destination', 'starfish' );
  return $review_columns;
}
add_action('manage_edit-starfish_review_columns', 'srm_add_new_starfish_review_column');

/**
* show custom order column values
*/
function srm_starfish_review_show_order_column($name){
  global $post;

  switch ($name) {
		case 'track_id':
			$tracking_id = get_post_meta($post->ID, '_srm_tracking_id', true);
			echo esc_html($tracking_id);
			break;
		case 'reviewer':
			if(get_post_meta($post->ID, '_srm_reviewer_name', true) != ''){
					echo 'Name: '. esc_html(get_post_meta($post->ID, '_srm_reviewer_name', true));
			}
			if(get_post_meta($post->ID, '_srm_reviewer_email', true) != ''){
					echo '<br />Email: '. esc_html(get_post_meta($post->ID, '_srm_reviewer_email', true));
			}
			if(get_post_meta($post->ID, '_srm_reviewer_phone', true) != ''){
					echo '<br />Phone: '. esc_html(get_post_meta($post->ID, '_srm_reviewer_phone', true));
			}
			break;
		case 'feedback':
			$feedback = get_post_meta($post->ID, '_srm_feedback', true);
			$feedback = esc_html($feedback);
			if($feedback == 'Yes'){
				echo esc_html__( 'Positive', 'starfish' );
			}else{
				echo esc_html__( 'Negative', 'starfish' );
			}
			break;
		case 'message':
			$content = wp_trim_words( get_the_content($post->ID), 10, '...' );
			echo $content;
			break;
		case 'funnel':
			$srm_funnel_id = get_post_meta($post->ID, '_srm_funnel_id', true);
			$srm_funnel_title = get_the_title($srm_funnel_id);
			echo esc_html($srm_funnel_title);
			break;
		case 'destination':
			if(get_post_meta($post->ID, '_srm_desti_name', true) != ''){
					$desti_name = esc_html(get_post_meta($post->ID, '_srm_desti_name', true));
					echo $desti_name;
			}
			break;
   default:
      break;
   }
}
add_action('manage_starfish_review_posts_custom_column','srm_starfish_review_show_order_column');

/**
* make column sortable
*/
function srm_starfish_review_column_register_sortable($columns){
  $columns['track_id'] = '_srm_tracking_id';
	$columns['feedback'] = '_srm_feedback';
	$columns['funnel'] = '_srm_funnel_id';
	$columns['destination'] = '_srm_desti_name';
  return $columns;
}
add_filter('manage_edit-starfish_review_sortable_columns','srm_starfish_review_column_register_sortable');


function srm_starfish_review_add_top_review_graph() {
    global $pagenow, $post;
				?>
				<style type="text/css">
					h1.wp-heading-inline{ display: none !important; }
			   .post-type-starfish_review .page-title-action { display:none; }
				</style>
				<?php
				$total_review = $negative_review = $positive_review = 0;
				$srm_review_args = array( 'post_type' => 'starfish_review', 'posts_per_page' => '-1');
				$srm_review_query = new WP_Query( $srm_review_args );
				if ( $srm_review_query->have_posts() ) {
						while ( $srm_review_query->have_posts() ) {
							$srm_review_query->the_post();
							$review_id = get_the_ID();
							$feedback = get_post_meta($review_id, '_srm_feedback', true);
							if($feedback == 'Yes'){
									$positive_review += 1;
							}else{
								$negative_review += 1;
							}
							$total_review += 1;
						}
						wp_reset_postdata();
				}

				if($total_review <= 0){
						echo '<div class="wrap">';
						echo '<h2><span class="dashicons dashicons_reviews dashicons-star-filled"></span>'. esc_html__('Reviews', 'starfish') .'</h2>';
						echo '</div>';
						return;
				}

        echo '<div class="wrap">';
            echo '<h2><span class="dashicons dashicons_reviews dashicons-star-filled"></span>'. esc_html__('Reviews', 'starfish') .'</h2>';
						?>
							<div id="srm_review_chart_wrap">
								<div class="srm_review_rating" id="srm_review_rating">
										<?php
											$ratings_status = '';
											$ratings_arr = srm_review_status($total_review, $positive_review, $negative_review);
											if(isset($ratings_arr) && (count($ratings_arr) > 0)){
													$ratings_status = '<span class="review_status '.$ratings_arr['class'].'">'.$ratings_arr['name'].'</span>';
											}

											$parcent_color = '#FFFFFF';
											if((($positive_review == 0) && ($negative_review > 0)) || (($negative_review == 0) && ($positive_review > 0))){
												$parcent_color = '#000000';
											}

										?>
										<div id="rating_status"><span class="srm_admin_review_status_label"><?php echo esc_html__( 'Rating:', 'starfish' ); ?></span> <?php echo $ratings_status; ?></div>
										<div id="total_rating"><span class="srm_admin_label"><?php echo esc_html__( 'Total:', 'starfish' ); ?></span> <span class="srm_number srm_number_total"><?php echo $total_review; ?></span></div>
										<div id="positive_feedback"><span class="srm_admin_label"><?php echo esc_html__( 'Positive:', 'starfish' ); ?></span> <span class="srm_number srm_number_postitive"><?php echo $positive_review; ?></span></div>
										<div id="negative_feedback"><span class="srm_admin_label"><?php echo esc_html__( 'Negative:', 'starfish' ); ?></span> <span class="srm_number srm_number_negative"><?php echo $negative_review; ?></span></div>
								</div><!-- srm_review_rating -->
								<div class="srm_review_chart" id="srm_review_chart">
									<div id="piechart"></div>

									<script type="text/javascript">
									// Load google charts
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									// Draw the chart and set the chart values
									function drawChart() {
										var data = google.visualization.arrayToDataTable([
										['Task', 'Total Reviews'],
										['Positive', <?php echo $positive_review; ?>],
										['Negative', <?php echo $negative_review; ?>]
									]);



										// Optional; add a title and set the width and height of the chart
										//var options = {'title':'', 'pieHole': 0.4, 'titleTextStyle': {'color':'#000000'},  'backgroundColor':'#f1f1f1', 'colors':['#7ed026','#e96e48'], 'legend' : {'position':'right', 'alignment':'center'}, 'width':280, 'height':250, 'chartArea':{ 'left':20, 'top':0, 'width':'100%', 'height':'100%'}};
										var options = {'title':'', 'pieHole': 0.4, 'pieSliceTextStyle': { 'color': '<?php echo $parcent_color; ?>', }, 'backgroundColor':'#f1f1f1', 'colors':['#7ed026','#e96e48'], 'legend' : {'position':'right', 'alignment':'center'}, 'width':280, 'height':250, 'chartArea':{ 'left':20, 'top':0, 'width':'100%', 'height':'100%'}};

										// Display the chart inside the <div> element with id="piechart"
										var chart = new google.visualization.PieChart(document.getElementById('piechart'));
										chart.draw(data, options);
									}
									</script>
								</div><!-- srm_review_rating -->
							</div><!-- srm_review_chart_wrap -->
						<?php
        echo '</div>';
}

/**
 * Display HTML after the 'Posts' title
 * where we target the 'edit-post' screen
 */
add_filter( 'views_edit-starfish_review', function( $views ){
    srm_starfish_review_add_top_review_graph();
    return $views;
});


/**
 * Add script to reveiw admin list page
 */
 function srm_add_admin_review_listpage_script( $hook ) {
	 global $pagenow, $post;
	 if( (isset($post)) && ($post->post_type == 'starfish_review') && ($pagenow == 'edit.php') ) {
        wp_enqueue_script(  'pie-chart', SRM_PLUGIN_URL.'/js/pie-chart.js' );
				wp_enqueue_style(  'review_admin', SRM_PLUGIN_URL.'/css/reviews-admin.css' );
    }

		if( ((isset($post)) && ($post->post_type == 'funnel') && ($pagenow == 'post.php')) || (isset($_GET['post_type']) && ( $_GET['post_type'] == 'funnel')) ) {
				 wp_enqueue_style(  'review_admin', SRM_PLUGIN_URL.'/css/reviews-admin.css' );
				 wp_enqueue_script('jquery-ui-sortable');
				 wp_enqueue_script(  'review_admin_js', SRM_PLUGIN_URL.'/js/review-backend.js' );
				 wp_enqueue_style(  'fontawesome-v5', SRM_PLUGIN_URL.'/css/fontawesome-all.min.css' );
		 }
}
add_action( 'admin_enqueue_scripts', 'srm_add_admin_review_listpage_script', 10, 1 );


function srm_front_end_review_script() {
	global $post;
	wp_register_style(  'srm-review-front', SRM_PLUGIN_URL.'/css/reviews-frontend.css' );
	if(( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'starfish') ) || is_singular('funnel') ) {
			if ( ! wp_script_is( 'jquery', 'done' ) ) {
					wp_enqueue_script( 'jquery' );
			}
			wp_enqueue_style('srm-review-front');
			wp_enqueue_style(  'fontawesome-v5', SRM_PLUGIN_URL.'/css/fontawesome-all.min.css' );
	}

	/******Inline style manager*******/
	$dynamic_generated_css = '';
	if(is_singular( 'funnel' )){
		$dynamic_generated_css .= 'body{ background-color: #fff !important; background-image: none !important; }';
	}

	if($dynamic_generated_css != ''){
		wp_add_inline_style( 'srm-review-front', $dynamic_generated_css );
	}

}
add_action( 'wp_enqueue_scripts', 'srm_front_end_review_script');


function srm_review_status($total, $positive, $negative){
		if($total <= 0) return;
		$postive_percent = ( $positive * 100 ) / $total;
		$negative_percent = ( $negative * 100 ) / $total;
		$score = $postive_percent - $negative_percent;
		$score = round($score);
		$score_result = array();
		$score_text = '';
		$score_class = '';
		if($score <= 0){
				$score_text = esc_html__( 'Poor', 'starfish' );
				$score_class = 'poor';
		}elseif(($score >= 1) && ($score <= 29)){
				$score_text = esc_html__( 'Average', 'starfish' );
				$score_class = 'average';
		}elseif(($score >= 30) && ($score <= 69)){
				$score_text = esc_html__( 'Good', 'starfish' );
				$score_class = 'good';
		}elseif(($score >= 70) && ($score <= 100)){
				$score_text = esc_html__( 'Excellent', 'starfish' );
				$score_class = 'excellent';
		}elseif($score > 100){
				$score_text = esc_html__( 'Excellent', 'starfish' );
				$score_class = 'excellent';
		}
		$score_result['name'] = $score_text;
		$score_result['class'] = $score_class;
		return $score_result;
}

/**
 * Filter by Funnels
 * @since 1.0
 * @return void
 */
function srm_add_filter_by_funnel_reveiw_admin() {
  global $typenow;
  global $wp_query;
    if ( $typenow == 'starfish_review' ) {
      $current_funnel = '';
			$funnel_arr = get_option('srm_all_funnels');
      if( isset( $_GET['funnel_id'] ) ) {
        $current_funnel = $_GET['funnel_id'];
      } ?>
      <select name="funnel_id" id="funnel_id">
				<option value="all" <?php selected( 'all', $current_funnel ); ?>><?php _e( 'All Funnels', 'starfish' ); ?></option>
				<?php
					if(isset($funnel_arr) && (count($funnel_arr) > 0)){
						foreach ($funnel_arr as $funnel_id => $funnel_name) {
							?><option value="<?php echo esc_attr( $funnel_id ); ?>" <?php selected( $funnel_id, $current_funnel ); ?>><?php echo esc_attr( $funnel_name ); ?></option><?php
						}
					}
				?>
      </select>
  <?php }
}
add_action( 'restrict_manage_posts', 'srm_add_filter_by_funnel_reveiw_admin' );


/**
 * Update query
 * @since 1.0
 * @return void
 */
function srm_admin_sort_reviews_by_funnel( $query ) {
  global $pagenow;
  // Get the post type
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  if ( is_admin() && $pagenow=='edit.php' && $post_type == 'starfish_review' && isset( $_GET['funnel_id'] ) && $_GET['funnel_id'] !='all' ) {
    $query->query_vars['meta_key'] = '_srm_funnel_id';
    $query->query_vars['meta_value'] = intval($_GET['funnel_id']);
    $query->query_vars['meta_compare'] = '=';
  }
}
add_filter( 'parse_query', 'srm_admin_sort_reviews_by_funnel' );
