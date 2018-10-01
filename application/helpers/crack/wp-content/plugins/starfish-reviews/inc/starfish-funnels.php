<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
add_action( 'init', 'srm_register_funnel_cpt' );
/**
 * Register a Funnel post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function srm_register_funnel_cpt() {
	$labels = array(
		'name'               => _x( 'Funnels', 'post type general name', 'starfish' ),
		'singular_name'      => _x( 'Funnel', 'post type singular name', 'starfish' ),
		'menu_name'          => _x( 'Funnels', 'admin menu', 'starfish' ),
		'name_admin_bar'     => _x( 'Funnel', 'add new on admin bar', 'starfish' ),
		'add_new'            => _x( 'Add New', 'Funnel', 'starfish' ),
		'add_new_item'       => esc_html__( 'Add New Funnel', 'starfish' ),
		'new_item'           => esc_html__( 'New Funnel', 'starfish' ),
		'edit_item'          => esc_html__( 'Edit Funnel', 'starfish' ),
		'featured_image' 		 => esc_html__( 'Funnel Logo', 'starfish' ),
		'set_featured_image' => esc_html__( 'Set Funnel Logo', 'starfish' ),
		'remove_featured_image' => esc_html__( 'Remove Funnel Logo', 'starfish' ),
		'use_featured_image' => esc_html__( 'Use as Funnel Logo', 'starfish' ),
		'view_item'          => esc_html__( 'View Funnel', 'starfish' ),
		'all_items'          => esc_html__( 'All Funnels', 'starfish' ),
		'search_items'       => esc_html__( 'Search Funnels', 'starfish' ),
		'parent_item_colon'  => esc_html__( 'Parent Funnels:', 'starfish' ),
		'not_found'          => esc_html__( 'No Funnels found.', 'starfish' ),
		'not_found_in_trash' => esc_html__( 'No Funnels found in Trash.', 'starfish' )
	);
	$reveiw_page_slug = esc_html(get_option('srm_review_slug'));
	$args = array(
		'labels'             => $labels,
    'description'        => esc_html__( 'Description.', 'starfish' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=starfish_review',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $reveiw_page_slug ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 100,
		'supports'           => array( 'title', 'thumbnail' )
	);

	register_post_type( 'funnel', $args );
}


/**
 * Add meta box
 *
 * @param post $post The post object
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 */
function srm_funnel_meta_boxes( $post ){
	add_meta_box( 'funnel_settings_meta_box', esc_html__( 'Funnel Settings', 'starfish' ), 'srm_funnel_settings_build_meta_box', 'funnel', 'normal', 'default' );
	add_meta_box( 'funnel_yes_meta_box', esc_html__( 'Positive Response', 'starfish' ), 'srm_yes_result_build_meta_box', 'funnel', 'normal', 'default' );
	add_meta_box( 'funnel_no_meta_box', esc_html__( 'Negative Response', 'starfish' ), 'srm_no_result_build_meta_box', 'funnel', 'normal', 'default' );

}
add_action( 'add_meta_boxes_funnel', 'srm_funnel_meta_boxes' );

/**
 * Build custom field meta box
 *
 * @param post $post The post object
 */
function srm_funnel_settings_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'funnel_settings_meta_box_nonce' );
	if(isset($_GET['post']) && ($_GET['action'] == 'edit')){
		// retrieve the _srm_yn_question current value
		$srm_yn_question = esc_html(get_post_meta( $post->ID, '_srm_yn_question', true ));
		// retrieve the _srm_button_text current value
	  $srm_button_style = esc_html(get_post_meta( $post->ID, '_srm_button_style', true ));
	}else{
		$latest_funnel_id = srm_get_last_funnel_id();
		// retrieve the _srm_yn_question current value
		$srm_yn_question = esc_html(get_post_meta( $latest_funnel_id, '_srm_yn_question', true ));
		if($srm_yn_question == ''){
			$srm_yn_question = esc_html(get_option('srm_default_yn_question'));
		}
		// retrieve the _srm_button_style current value
		$srm_button_style = esc_html(get_post_meta( $latest_funnel_id, '_srm_button_style', true ));
		if($srm_button_style == ''){
			$srm_button_style = 'thumbs_outline';
		}
	}
	?>
	<div class='inside'>
		<table class="form-table">
		<tr>
		<th scope="row"><label for="srm_yn_question"><?php echo esc_html__( 'Yes/No Question', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_yn_question" id="srm_yn_question" value="<?php echo $srm_yn_question; ?>" placeholder="<?php echo esc_html__( 'Would you recommend {WP site name here}?', 'starfish' ); ?>">
		<p class="description" id="srm_yn_question-description"><?php echo esc_html__( 'Shortcode {site-name} uses the site name as set in WP Settings>General.', 'starfish' ); ?></p></td>
		</tr>
		<tr>
		<th scope="row"><label for="srm_button_style"><?php echo esc_html__( 'Answer Buttons Style', 'starfish' ); ?></label></th>
		<td>
				<select name="srm_button_style" id="srm_button_style">
						<option value="thumbs_outline" <?php selected( $srm_button_style, 'thumbs_outline' ); ?> ><?php echo esc_html__( 'Thumbs outline', 'starfish' ); ?></option>
						<option value="thumbs_solid" <?php selected( $srm_button_style, 'thumbs_solid' ); ?> ><?php echo esc_html__( 'Thumbs solid', 'starfish' ); ?></option>
						<option value="faces" <?php selected( $srm_button_style, 'faces' ); ?> ><?php echo esc_html__( 'Faces', 'starfish' ); ?></option>
						<option value="scircle" <?php selected( $srm_button_style, 'scircle' ); ?> ><?php echo esc_html__( 'Symbol circles', 'starfish' ); ?></option>
				</select>
				<div class="button_style_preview">
					<?php if($srm_button_style == 'thumbs_outline'){ ?>
						<span class="faicon iconyes far fa-thumbs-up"></span><span class="faicon iconno far fa-thumbs-down faicon_flip"></span>
					<?php } ?>
					<?php if($srm_button_style == 'thumbs_solid'){ ?>
						<span class="faicon iconyes fas fa-thumbs-up"></span><span class="faicon iconno fas fa-thumbs-down faicon_flip"></span>
					<?php } ?>
					<?php if($srm_button_style == 'faces'){ ?>
						<span class="faicon iconyes far fa-smile"></span><span class="faicon iconno far fa-frown"></span>
					<?php } ?>
					<?php if($srm_button_style == 'scircle'){ ?>
						<span class="faicon iconyes fas fa-check-circle"></span><span class="faicon iconno fas fa-times-circle"></span>
					<?php } ?>
				</div>
		</td>
		</tr>
		<?php
			$funnel_id = 0;
			if(isset($_GET['post']) && ($_GET['action'] == 'edit')){
				$funnel_id = $_GET['post'];
		?>
		<tr>
		<th scope="row"><label for="blogname"><?php echo esc_html__( 'Funnel Shortcode', 'starfish' ); ?></label></th>
		<td><code><?php echo '[starfish funnel="'.$funnel_id.'"]'; ?></code></td>
		</tr>
		<?php } ?>
		</table>
	</div>
	<?php
}


function srm_yes_result_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'funnel_yes_result_meta_box_nonce' );
	if(isset($_GET['post']) && ($_GET['action'] == 'edit')){
		// retrieve the _srm_yes_review_prompt current value
		$srm_yes_review_prompt = esc_html(get_post_meta( $post->ID, '_srm_yes_review_prompt', true ));
		$srm_no_destination = esc_html(get_post_meta( $post->ID, '_srm_no_destination', true ));

		// retrieve the _srm_review_destination current value
	  $srm_review_destination = esc_url(get_post_meta( $post->ID, '_srm_review_destination', true ));

		$srm_review_auto_redirect = intval(get_post_meta( $post->ID, '_srm_review_auto_redirect', true ));

		$srm_button_text = esc_html(get_post_meta( $post->ID, '_srm_button_text', true ));

		$srm_multi_desti = get_post_meta( $post->ID, '_srm_multi_desti', true );

	}else{
		$latest_funnel_id = srm_get_last_funnel_id();
		// retrieve the _srm_yes_review_prompt current value
		$srm_yes_review_prompt = esc_html(get_post_meta( $latest_funnel_id, '_srm_yes_review_prompt', true ));
		if($srm_yes_review_prompt == ''){
			$srm_yes_review_prompt = esc_html(get_option('srm_default_review_prompt'));
		}
		// retrieve the _srm_review_destination current value
	  $srm_review_destination = '';

		$srm_no_destination = 'single';

		$srm_multi_desti = array();

		$srm_review_auto_redirect = intval(get_option('srm_yes_redirect_seconds'));

		// retrieve the _srm_button_text current value
		$srm_button_text = esc_html(get_post_meta( $latest_funnel_id, '_srm_button_text', true ));
		if($srm_button_text == ''){
			$srm_button_text = esc_html(get_option('srm_default_submit_button'));
		}
	}

	if($srm_no_destination == 'multiple'){
		$multi_desti_row_style = 'display: table-row;';
		$single_desti_row_style = 'display: none;';
	}else{
		$multi_desti_row_style = 'display: none;';
		$single_desti_row_style = 'display: table-row;';
	}

	?>
	<div class='inside'>
		<table class="form-table">
		<tr>
		<th scope="row"><label for="srm_yes_review_prompt"><?php echo esc_html__( 'Review Prompt', 'starfish' ); ?></label></th>
		<td><textarea name="srm_yes_review_prompt" id="srm_yes_review_prompt" rows="5" cols="60"  placeholder="<?php echo esc_html__( 'Excellent! Please rate us 5-stars.', 'starfish' ); ?>"><?php echo $srm_yes_review_prompt; ?></textarea>
		<p class="description" id="srm_yes_review_prompt-description"><?php echo esc_html__( 'Accepts HTML', 'starfish' ); ?></p></td></td>
		</tr>
		<tr>
		<th scope="row"><label for="srm_no_destination"><?php echo esc_html__( 'Number of Destinations', 'starfish' ); ?></label></th>
		<td>
			<select name="srm_no_destination" id="srm_no_destination">
					<option value="single" <?php selected( $srm_no_destination, 'single' ); ?> ><?php echo esc_html__( 'Single', 'starfish' ); ?></option>
					<option value="multiple" <?php selected( $srm_no_destination, 'multiple' ); ?> ><?php echo esc_html__( 'Multiple', 'starfish' ); ?></option>
			</select>
		</td>
		</tr>
		<tr class="multiple-destination-edit" style="<?php echo $multi_desti_row_style; ?>">
			<td class="nopadding multi_destination" colspan="2">
				<strong><?php echo esc_html__( 'Set Multiple Destinations', 'starfish' ); ?></strong>
				<table class="mulit_desti_row_section">
				<tr>
						<th></th>
						<th><?php echo esc_html__( 'Icon', 'starfish' ); ?></th>
						<th><?php echo esc_html__( 'Preview', 'starfish' ); ?></th>
						<th><?php echo esc_html__( 'Icon-BG Color', 'starfish' ); ?></th>
						<th><?php echo esc_html__( 'Name', 'starfish' ); ?><sup>*</sup></th>
						<th><?php echo esc_html__( 'Destination', 'starfish' ); ?><sup>*</sup></th>
						<th></th>
				</tr>
				<?php wp_nonce_field('image_upload', 'image_upload_nonce');?>
				<?php if(isset($srm_multi_desti) && is_array($srm_multi_desti) && (count($srm_multi_desti) > 0)){ ?>
					<?php foreach ($srm_multi_desti as $key => $multi_desti) { ?>
							<?php
								$srm_destination_icon = esc_html( $multi_desti['desti_icon'] );
								$icon_photo_id = intval( $multi_desti['icon_photo_id'] );
								$srm_desti_color = esc_html( $multi_desti['srm_desti_color'] );
								$srm_desti_bg_color = esc_html( $multi_desti['srm_desti_bg_color'] );
								$srm_desti_name = esc_html( $multi_desti['srm_desti_name'] );
								$srm_desti_url = esc_url( $multi_desti['srm_desti_url'] );
							?>
							<tr class="mulit_desti_row">
								<td class="reorder-icon"><span class="faicon-reorder fas fa-arrows-alt"></span></td>
								<td>
									<select name="desti_icon[]" class="desti_icon">
											<option value=""><?php echo esc_html__( 'Select Icon', 'starfish' ); ?></option>
											<option value="Google" <?php selected( $srm_destination_icon, 'Google' ); ?> ><?php echo esc_html__( 'Google', 'starfish' ); ?></option>
											<option value="Facebook" <?php selected( $srm_destination_icon, 'Facebook' ); ?> ><?php echo esc_html__( 'Facebook', 'starfish' ); ?></option>
											<option value="Yelp" <?php selected( $srm_destination_icon, 'Yelp' ); ?> ><?php echo esc_html__( 'Yelp', 'starfish' ); ?></option>
											<option value="Tripadvisor" <?php selected( $srm_destination_icon, 'Tripadvisor' ); ?> ><?php echo esc_html__( 'Tripadvisor', 'starfish' ); ?></option>
											<option value="Amazon" <?php selected( $srm_destination_icon, 'Amazon' ); ?> ><?php echo esc_html__( 'Amazon', 'starfish' ); ?></option>
											<option value="Audible" <?php selected( $srm_destination_icon, 'Audible' ); ?> ><?php echo esc_html__( 'Audible', 'starfish' ); ?></option>
											<option value="iTunes" <?php selected( $srm_destination_icon, 'iTunes' ); ?> ><?php echo esc_html__( 'iTunes', 'starfish' ); ?></option>
											<option value="AppleAppStore" <?php selected( $srm_destination_icon, 'AppleAppStore' ); ?> ><?php echo esc_html__( 'Apple App Store', 'starfish' ); ?></option>
											<option value="GooglePlay" <?php selected( $srm_destination_icon, 'GooglePlay' ); ?> ><?php echo esc_html__( 'Google Play', 'starfish' ); ?></option>
											<option value="Foursquare" <?php selected( $srm_destination_icon, 'Foursquare' ); ?> ><?php echo esc_html__( 'Foursquare', 'starfish' ); ?></option>
											<option value="WordPress" <?php selected( $srm_destination_icon, 'WordPress' ); ?> ><?php echo esc_html__( 'WordPress', 'starfish' ); ?></option>
											<option value="Etsy" <?php selected( $srm_destination_icon, 'Etsy' ); ?> ><?php echo esc_html__( 'Etsy', 'starfish' ); ?></option>
											<option value="YouTube" <?php selected( $srm_destination_icon, 'YouTube' ); ?> ><?php echo esc_html__( 'YouTube', 'starfish' ); ?></option>
											<option value="Uploadimage" <?php selected( $srm_destination_icon, 'Uploadimage' ); ?> ><?php echo esc_html__( 'Upload image', 'starfish' ); ?></option>
									</select>
									<div class="photo_upload_area" style="display:none;">
										<label class="btn_icon_upload"> <?php echo esc_html__( 'Browse...', 'starfish' ); ?>
											<input type="file" name="photoupload[]" class="photoupload"  accept=".png, .jpg, .jpeg, .gif">
										</label>
										<input type="hidden" class="icon_photo" name="icon_photo[]" value="">
										<input type="hidden" class="icon_photo_id" name="icon_photo_id[]" value="<?php echo $icon_photo_id; ?>">
										<div class="existing_photo"></div>
									</div>
								</td>
								<?php
									$color_field_disabled = '';
									if($srm_destination_icon == 'Uploadimage'){
										$color_field_disabled = 'disabled="disabled"';
									}
								?>
								<td class="mtd_icon_preview"><?php echo starfish_get_destination_icon($srm_destination_icon, $icon_photo_id); ?></td>
								<td class="td-color-field">
									 <input <?php echo $color_field_disabled; ?> type="text" class="medium-text color-field" name="srm_desti_color[]" value="<?php echo $srm_desti_color; ?>" placeholder="<?php echo esc_html__( '#ffffff', 'starfish' ); ?>">
									 <input <?php echo $color_field_disabled; ?> type="text" class="medium-text color-field" name="srm_desti_bg_color[]" value="<?php echo $srm_desti_bg_color; ?>" placeholder="<?php echo esc_html__( '#000000', 'starfish' ); ?>">
								</td>
								<td><input type="text" class="medium-text" name="srm_desti_name[]" value="<?php echo $srm_desti_name; ?>" placeholder="<?php echo esc_html__( 'Name', 'starfish' ); ?>"></td>
								<td><input type="text" class="medium-text" name="srm_desti_url[]" value="<?php echo $srm_desti_url; ?>" placeholder="<?php echo esc_html__( 'Review URL', 'starfish' ); ?>"></td>
								<td class="mr_add_remove btn-multi-desti-remove btn-danger"><span class="fas fa-minus"></span></td>
							</tr>
					<?php } ?>
				<?php } ?>
				<?php
					$srm_destination_icon = '';
					$icon_photo_id = '';
					$srm_desti_color = '';
					$srm_desti_bg_color = '';
					$srm_desti_name = '';
					$srm_desti_url = '';
				?>
				<tr class="mulit_desti_row">
					<td class="reorder-icon"><span class="faicon-reorder fas fa-arrows-alt"></span></td>
					<td>
						<select name="desti_icon[]" class="desti_icon">
								<option value=""><?php echo esc_html__( 'Select Icon', 'starfish' ); ?></option>
								<option value="Google" <?php selected( $srm_destination_icon, 'Google' ); ?> ><?php echo esc_html__( 'Google', 'starfish' ); ?></option>
								<option value="Facebook" <?php selected( $srm_destination_icon, 'Facebook' ); ?> ><?php echo esc_html__( 'Facebook', 'starfish' ); ?></option>
								<option value="Yelp" <?php selected( $srm_destination_icon, 'Yelp' ); ?> ><?php echo esc_html__( 'Yelp', 'starfish' ); ?></option>
								<option value="Tripadvisor" <?php selected( $srm_destination_icon, 'Tripadvisor' ); ?> ><?php echo esc_html__( 'Tripadvisor', 'starfish' ); ?></option>
								<option value="Amazon" <?php selected( $srm_destination_icon, 'Amazon' ); ?> ><?php echo esc_html__( 'Amazon', 'starfish' ); ?></option>
								<option value="Audible" <?php selected( $srm_destination_icon, 'Audible' ); ?> ><?php echo esc_html__( 'Audible', 'starfish' ); ?></option>
								<option value="iTunes" <?php selected( $srm_destination_icon, 'iTunes' ); ?> ><?php echo esc_html__( 'iTunes', 'starfish' ); ?></option>
								<option value="AppleAppStore" <?php selected( $srm_destination_icon, 'AppleAppStore' ); ?> ><?php echo esc_html__( 'Apple App Store', 'starfish' ); ?></option>
								<option value="GooglePlay" <?php selected( $srm_destination_icon, 'GooglePlay' ); ?> ><?php echo esc_html__( 'Google Play', 'starfish' ); ?></option>
								<option value="Foursquare" <?php selected( $srm_destination_icon, 'Foursquare' ); ?> ><?php echo esc_html__( 'Foursquare', 'starfish' ); ?></option>
								<option value="WordPress" <?php selected( $srm_destination_icon, 'WordPress' ); ?> ><?php echo esc_html__( 'WordPress', 'starfish' ); ?></option>
								<option value="Etsy" <?php selected( $srm_destination_icon, 'Etsy' ); ?> ><?php echo esc_html__( 'Etsy', 'starfish' ); ?></option>
								<option value="YouTube" <?php selected( $srm_destination_icon, 'YouTube' ); ?> ><?php echo esc_html__( 'YouTube', 'starfish' ); ?></option>
								<option value="Uploadimage" <?php selected( $srm_destination_icon, 'Uploadimage' ); ?> ><?php echo esc_html__( 'Upload image', 'starfish' ); ?></option>
						</select>
						<div class="photo_upload_area">
							<label class="btn_icon_upload"> <?php echo esc_html__( 'Browse...', 'starfish' ); ?>
								<input type="file" name="photoupload[]" class="photoupload"  accept=".png, .jpg, .jpeg, .gif">
							</label>
							<input type="hidden" class="icon_photo" name="icon_photo[]" value="">
							<input type="hidden" class="icon_photo_id" name="icon_photo_id[]" value="">
							<div class="existing_photo"></div>
						</div>
					</td>
					<td class="mtd_icon_preview"></td>
					<td class="td-color-field">
						 <input type="text" class="medium-text color-field" name="srm_desti_color[]" value="<?php echo $srm_desti_color; ?>" placeholder="<?php echo esc_html__( '#000000', 'starfish' ); ?>">
						 <input type="text" class="medium-text color-field" name="srm_desti_bg_color[]" value="<?php echo $srm_desti_bg_color; ?>" placeholder="<?php echo esc_html__( '#ffffff', 'starfish' ); ?>">
					</td>
					<td><input type="text" class="medium-text" name="srm_desti_name[]" value="<?php echo $srm_desti_name; ?>" placeholder="<?php echo esc_html__( 'Name', 'starfish' ); ?>"></td>
					<td><input type="text" class="medium-text" name="srm_desti_url[]" value="<?php echo $srm_desti_url; ?>" placeholder="<?php echo esc_html__( 'Review URL', 'starfish' ); ?>"></td>
					<td class="mr_add_remove btn-multi-desti-add"><span class="fas fa-plus"></span></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr class="single-destination-edit" style="<?php echo $single_desti_row_style; ?>">
		<th scope="row"><label for="srm_review_destination"><?php echo __( 'Review Destination<sup>*</sup>', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_review_destination" id="srm_review_destination" value="<?php echo $srm_review_destination; ?>" placeholder="<?php echo esc_html__( 'Review URL', 'starfish' ); ?>">
		<p class="description" id="srm_yn_question-description"><?php echo __( 'Destination is required. Use for single and default destination.', 'starfish' ); ?></p></td>
		</tr>
		<tr class="single-destination-edit" style="<?php echo $single_desti_row_style; ?>">
		<th scope="row"><label for="srm_review_auto_redirect"><?php echo esc_html__( 'Auto Redirect (seconds)', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_review_auto_redirect" id="srm_review_auto_redirect" value="<?php echo $srm_review_auto_redirect; ?>" placeholder="10">
		<p class="description" id="srm_review_auto_redirect-description"><?php echo esc_html__( 'Auto-forward to destination URL after X number of seconds. 0 for disable.', 'starfish' ); ?></p></td>
		</tr>
		<tr class="single-destination-edit" style="<?php echo $single_desti_row_style; ?>">
		<th scope="row"><label for="srm_button_text"><?php echo esc_html__( 'Submit Button (Positive)', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_button_text" id="srm_button_text" value="<?php echo $srm_button_text; ?>" placeholder="<?php echo esc_html__( 'Submit Review', 'starfish' ); ?>">
		</td>
		</tr>
		</table>
	</div>
	<?php
}

function srm_no_result_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'funnel_no_result_meta_box_nonce' );
	if(isset($_GET['post']) && ($_GET['action'] == 'edit')){
		// retrieve the _srm_no_review_prompt current value
		$srm_no_review_prompt = esc_html(get_post_meta( $post->ID, '_srm_no_review_prompt', true ));
		// retrieve the _srm_email_feedback current value
	  $srm_email_feedback = esc_html(get_post_meta( $post->ID, '_srm_email_feedback', true ));
		$srm_no_thank_you_msg = esc_html(get_post_meta( $post->ID, '_srm_no_thank_you_msg', true ));

		$srm_ask_name = esc_html(get_post_meta( $post->ID, '_srm_ask_name', true ));
		$srm_ask_name_required = esc_html(get_post_meta( $post->ID, '_srm_ask_name_required', true ));

		$srm_ask_email = esc_html(get_post_meta( $post->ID, '_srm_ask_email', true ));
		$srm_ask_email_required = esc_html(get_post_meta( $post->ID, '_srm_ask_email_required', true ));

		$srm_ask_phone = esc_html(get_post_meta( $post->ID, '_srm_ask_phone', true ));
		$srm_ask_phone_required = esc_html(get_post_meta( $post->ID, '_srm_ask_phone_required', true ));

		$srm_button_text_no = esc_html(get_post_meta( $post->ID, '_srm_button_text_no', true ));

		$disable_review_gating = esc_html(get_post_meta( $post->ID, '_srm_disable_review_gating', true ));
		$srm_public_review_text = esc_html(get_post_meta( $post->ID, '_srm_public_review_text', true ));

	}else{
		$latest_funnel_id = srm_get_last_funnel_id();
		// retrieve the _srm_no_review_prompt current value
		$srm_no_review_prompt = esc_html(get_post_meta( $latest_funnel_id, '_srm_no_review_prompt', true ));
		if($srm_no_review_prompt == ''){
			$srm_no_review_prompt = esc_html(get_option('srm_default_no_review_prompt'));
		}
		// retrieve the _srm_email_feedback current value
		$srm_email_feedback = esc_html(get_post_meta( $latest_funnel_id, '_srm_email_feedback', true ));
		if($srm_email_feedback == ''){
			$srm_email_feedback = esc_html(get_option('srm_default_feedback_email'));
		}
		$srm_no_thank_you_msg = esc_html(get_post_meta( $latest_funnel_id, '_srm_no_thank_you_msg', true ));
		if($srm_no_thank_you_msg == ''){
			$srm_no_thank_you_msg = esc_html(get_option('srm_msg_no_thank_you'));
		}

		$srm_button_text_no = esc_html(get_post_meta( $latest_funnel_id, '_srm_button_text_no', true ));
		if($srm_button_text_no == ''){
			$srm_button_text_no = esc_html(get_option('srm_default_submit_button_no'));
		}

		$srm_ask_name = '';
		$srm_ask_name_required = '';

		$srm_ask_email = '';
		$srm_ask_email_required = '';

		$srm_ask_phone = '';
		$srm_ask_phone_required = '';

		$disable_review_gating = '';
		$srm_public_review_text = esc_html__( 'Leave a Public Review', 'starfish' );


	}
	?>
	<div class='inside'>
		<table class="form-table">
		<tr>
		<th scope="row"><label for="srm_no_review_prompt"><?php echo esc_html__( 'Review Prompt', 'starfish' ); ?></label></th>
		<td><textarea name="srm_no_review_prompt" id="srm_no_review_prompt" rows="5" cols="60"  placeholder="<?php echo esc_html__( 'We\'re sorry we didn\'t meet expectations. How can we do better in the future?', 'starfish' ); ?>"><?php echo $srm_no_review_prompt; ?></textarea></td>
		</tr>
		<tr>
			<th scope="row"><label><?php echo esc_html__( 'Reviewer Name', 'starfish' ); ?></label></th>
			<td>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_name" id="srm_ask_name" value="yes" <?php checked( $srm_ask_name, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_name"><?php echo esc_html__( 'Ask for name?', 'starfish' ); ?></label>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_name_required" id="srm_ask_name_required" value="yes" <?php checked( $srm_ask_name_required, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_name_required"><?php echo esc_html__( 'Required?', 'starfish' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php echo esc_html__( 'Reviewer Email', 'starfish' ); ?></label></th>
			<td>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_email" id="srm_ask_email" value="yes" <?php checked( $srm_ask_email, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_email"><?php echo esc_html__( 'Ask for email?', 'starfish' ); ?></label>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_email_required" id="srm_ask_email_required" value="yes" <?php checked( $srm_ask_email_required, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_email_required"><?php echo esc_html__( 'Required?', 'starfish' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php echo esc_html__( 'Reviewer Phone', 'starfish' ); ?></label></th>
			<td>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_phone" id="srm_ask_phone" value="yes" <?php checked( $srm_ask_phone, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_phone"><?php echo esc_html__( 'Ask for phone?', 'starfish' ); ?></label>
				<input class="ask_checkbox" type="checkbox" name="srm_ask_phone_required" id="srm_ask_phone_required" value="yes" <?php checked( $srm_ask_phone_required, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="srm_ask_phone_required"><?php echo esc_html__( 'Required?', 'starfish' ); ?></label>
			</td>
		</tr>
		<tr>
		<th scope="row"><label for="srm_email_feedback"><?php echo esc_html__( 'Email Feedback', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_email_feedback" id="srm_email_feedback" value="<?php echo $srm_email_feedback; ?>" placeholder="<?php echo esc_html__( 'email address(es)', 'starfish' ); ?>"><p class="description" id="srm_email_feedback-description"><?php echo esc_html__( 'email address(es) to send negative feedback to. You can add comma separated multiple email addresses. Shortcode {admin-email} defaults to the email set in WP Settings>General.', 'starfish' ); ?></p>
		</td>
		</tr>
		<tr>
		<th scope="row"><label for="srm_button_text_no"><?php echo esc_html__( 'Submit Button (Negative)', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_button_text_no" id="srm_button_text_no" value="<?php echo $srm_button_text_no; ?>" placeholder="<?php echo esc_html__( 'Send Feedback', 'starfish' ); ?>">
		</td>
		</tr>
		<tr>
		<th scope="row"><label for="srm_no_thank_you_msg"><?php echo esc_html__( 'Thank You Message', 'starfish' ); ?></label></th>
		<td><textarea name="srm_no_thank_you_msg" id="srm_no_thank_you_msg" rows="5" cols="60"  placeholder="<?php echo esc_html__( 'Thank you!', 'starfish' ); ?>"><?php echo $srm_no_thank_you_msg; ?></textarea></td>
		</tr>
		<tr>
			<th scope="row"><label><?php echo esc_html__( 'Disable Review Gating', 'starfish' ); ?></label></th>
			<td>
				<input class="ask_checkbox" type="checkbox" name="disable_review_gating" id="disable_review_gating" value="yes" <?php checked( $disable_review_gating, 'yes' ); ?> /><label class="ask_checkbox_lebel" for="disable_review_gating"><?php echo esc_html__( 'Allow negative responses to proceed to review destination.', 'starfish' ); ?></label>
				<p class="description" id="disable_review_gating-description">It's recommended you use this when asking for Google and Yelp reviews, to comply with their policy of not selectively asking for reviews only from people with positive feedback. Learn more here.</p>
			</td>
		</tr>
		<?php
		 	$public_review_text_style = 'display: none;';
			if($disable_review_gating == 'yes'){
				$public_review_text_style = 'display: table-row;';
			}
		 ?>
		<tr id="srm_public_review_text_field" style="<?php echo $public_review_text_style; ?>">
		<th scope="row"><label for="srm_public_review_text"><?php echo esc_html__( 'Public Review Text', 'starfish' ); ?></label></th>
		<td><input type="text" class="regular-text" name="srm_public_review_text" id="srm_public_review_text" value="<?php echo $srm_public_review_text; ?>" placeholder="<?php echo esc_html__( 'Leave a Public Review', 'starfish' ); ?>">
		</td>
		</tr>
		</table>
	</div>
	<?php
}

/**
 * Store custom field meta box data
 *
 * @param int $post_id The post ID.
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
 */
function srm_funnel_sttings_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['funnel_settings_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['funnel_settings_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
	}
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
  // Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_yn_question'] ) ) {
		update_post_meta( $post_id, '_srm_yn_question', sanitize_text_field( $_POST['srm_yn_question'] ) );
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_button_style'] ) ) {
		update_post_meta( $post_id, '_srm_button_style', sanitize_text_field( $_POST['srm_button_style'] ) );
	}

}
add_action( 'save_post_funnel', 'srm_funnel_sttings_save_meta_box_data' );

function srm_funnel_yes_result_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['funnel_yes_result_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['funnel_yes_result_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
	}
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
  // Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_yes_review_prompt'] ) ) {
		update_post_meta( $post_id, '_srm_yes_review_prompt', wp_kses_post( $_POST['srm_yes_review_prompt'] ) );
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_review_destination'] ) ) {
		update_post_meta( $post_id, '_srm_review_destination', esc_url( $_POST['srm_review_destination'] ) );
	}

	// store custom fields values
	if ( isset( $_REQUEST['srm_review_auto_redirect'] ) ) {
		update_post_meta( $post_id, '_srm_review_auto_redirect', intval( $_POST['srm_review_auto_redirect'] ) );
	}

	// store custom fields values
	if ( isset( $_REQUEST['srm_button_text'] ) ) {
		update_post_meta( $post_id, '_srm_button_text', sanitize_text_field( $_POST['srm_button_text'] ) );
	}

	if ( isset( $_REQUEST['srm_no_destination'] ) ) {
		update_post_meta( $post_id, '_srm_no_destination', sanitize_text_field( $_POST['srm_no_destination'] ) );
	}

	//desti_icon  icon_photo_id srm_desti_color srm_desti_name srm_desti_url
	if ( isset( $_REQUEST['desti_icon'] ) && is_array($_REQUEST['desti_icon'])) {
			$desti_icon_arr = $_REQUEST['desti_icon'];
			$icon_photo_id_arr = $_REQUEST['icon_photo_id'];
			$srm_desti_color_arr = $_REQUEST['srm_desti_color'];
			$srm_desti_bg_color_arr = $_REQUEST['srm_desti_bg_color'];
			$srm_desti_name_arr = $_REQUEST['srm_desti_name'];
			$srm_desti_url_arr = $_REQUEST['srm_desti_url'];
			$multi_desti_icon_set = array();
			if(count($desti_icon_arr) > 0){
				foreach ($desti_icon_arr as $key_icon => $value_icon) {
						if((isset($srm_desti_name_arr[$key_icon]) && ($srm_desti_name_arr[$key_icon] != '')) && (isset($srm_desti_url_arr[$key_icon]) && ($srm_desti_url_arr[$key_icon] != ''))){
							$multi_desti_icon_set[] = array(
								'desti_icon' => $value_icon,
								'icon_photo_id' => (isset($icon_photo_id_arr[$key_icon]) ? intval($icon_photo_id_arr[$key_icon]) : ''),
								'srm_desti_color' => (isset($srm_desti_color_arr[$key_icon]) ? esc_html($srm_desti_color_arr[$key_icon]) : ''),
								'srm_desti_bg_color' => (isset($srm_desti_bg_color_arr[$key_icon]) ? esc_html($srm_desti_bg_color_arr[$key_icon]) : ''),
								'srm_desti_name' => (isset($srm_desti_name_arr[$key_icon]) ? esc_html($srm_desti_name_arr[$key_icon]) : ''),
								'srm_desti_url' => (isset($srm_desti_url_arr[$key_icon]) ? esc_url($srm_desti_url_arr[$key_icon]) : ''),
							);
						}
				}
			}

			if(count($multi_desti_icon_set) > 0){
				update_post_meta( $post_id, '_srm_multi_desti', $multi_desti_icon_set );
				$multi_desti_required_value = true;
			}else{
				$multi_desti_required_value = false;
			}

	}


	if((isset( $_REQUEST['srm_no_destination'] ) && ($_REQUEST['srm_no_destination'] == 'single')) && (!isset($_REQUEST['srm_review_destination']) || ($_REQUEST['srm_review_destination'] == ''))){
		global $wpdb;
    $wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
		add_filter( 'redirect_post_location', 'srm_funnel_add_notice_query_var', 99 );
	}

	if((isset( $_REQUEST['srm_no_destination'] ) && ($_REQUEST['srm_no_destination'] == 'multiple')) && (! $multi_desti_required_value)){
		global $wpdb;
		$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
		add_filter( 'redirect_post_location', 'srm_funnel_add_notice_query_var', 99 );
	}

	$total_funnel = get_total_funnel();
	if ( starfish_fs()->can_use_premium_code() ){
		if(($total_funnel >= 7) && (starfish_fs()->is_plan('business', true))){
			global $wpdb;
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
			add_filter( 'redirect_post_location', 'srm_funnel_restriction_notice_query_var', 99 );
		}
	}elseif($total_funnel > 1){
		global $wpdb;
		$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
		add_filter( 'redirect_post_location', 'srm_funnel_inactive_restriction_notice_query_var', 99 );
	}

}
add_action( 'save_post_funnel', 'srm_funnel_yes_result_save_meta_box_data' );

function srm_funnel_restriction_notice_query_var( $location ) {
	return add_query_arg( array( 'funnel_limitation' => 'yes' ), $location );
}

function srm_funnel_inactive_restriction_notice_query_var( $location ) {
	return add_query_arg( array( 'inactive_api' => 'yes' ), $location );
}

function srm_funnel_add_notice_query_var( $location ) {
	return add_query_arg( array( 'funnel_destination_required' => 'yes' ), $location );
}

add_action( 'admin_notices', 'funnel_validation_admin_notice' );
function funnel_validation_admin_notice(){
	if(isset($_GET['funnel_destination_required']) && ($_GET['funnel_destination_required'] =='yes')){
		?>
		<div class="error">
		  <p><?php _e( 'Review Destination URL Is Required', 'starfish' ); ?></p>
		</div>
		<?php
	}

	if(isset($_GET['funnel_limitation']) && ($_GET['funnel_limitation'] =='yes')){
		?>
		<div class="error">
			<p><?php _e( 'You have the Business version of Starfish Reviews, which limits you to 6 funnels. Please <a href="https://starfishwp.com/shop/" target="_blank">upgrade to</a> the Webmaster license if you need more.', 'starfish' ); ?></p>
		</div>
		<?php
	}
}

function srm_no_result_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['funnel_no_result_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['funnel_no_result_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
	}
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
  // Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_no_review_prompt'] ) ) {
		update_post_meta( $post_id, '_srm_no_review_prompt', wp_kses_post( $_POST['srm_no_review_prompt'] ) );
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_email_feedback'] ) ) {
		update_post_meta( $post_id, '_srm_email_feedback', sanitize_text_field( $_POST['srm_email_feedback'] ) );
	}
	// store custom fields values
	if ( isset( $_REQUEST['srm_no_thank_you_msg'] ) ) {
		update_post_meta( $post_id, '_srm_no_thank_you_msg', wp_kses_post( $_POST['srm_no_thank_you_msg'] ) );
	}

	if ( isset( $_REQUEST['srm_button_text_no'] ) ) {
		update_post_meta( $post_id, '_srm_button_text_no', sanitize_text_field( $_POST['srm_button_text_no'] ) );
	}

	if ( isset( $_REQUEST['srm_ask_name'] ) ) {
		update_post_meta( $post_id, '_srm_ask_name', sanitize_text_field( $_POST['srm_ask_name'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_name', '' );
	}
	if ( isset( $_REQUEST['srm_ask_name_required'] ) ) {
		update_post_meta( $post_id, '_srm_ask_name_required', sanitize_text_field( $_POST['srm_ask_name_required'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_name_required', '' );
	}
	if ( isset( $_REQUEST['srm_ask_email'] ) ) {
		update_post_meta( $post_id, '_srm_ask_email', sanitize_text_field( $_POST['srm_ask_email'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_email', '' );
	}
	if ( isset( $_REQUEST['srm_ask_email_required'] ) ) {
		update_post_meta( $post_id, '_srm_ask_email_required', sanitize_text_field( $_POST['srm_ask_email_required'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_email_required', '' );
	}
	if ( isset( $_REQUEST['srm_ask_phone'] ) ) {
		update_post_meta( $post_id, '_srm_ask_phone', sanitize_text_field( $_POST['srm_ask_phone'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_phone', '' );
	}
	if ( isset( $_REQUEST['srm_ask_phone_required'] ) ) {
		update_post_meta( $post_id, '_srm_ask_phone_required', sanitize_text_field( $_POST['srm_ask_phone_required'] ) );
	}else{
		update_post_meta( $post_id, '_srm_ask_phone_required', '' );
	}

	if ( isset( $_REQUEST['disable_review_gating'] ) ) {
		update_post_meta( $post_id, '_srm_disable_review_gating', sanitize_text_field( $_POST['disable_review_gating'] ) );
	}else{
		update_post_meta( $post_id, '_srm_disable_review_gating', '' );
	}

	if ( isset( $_REQUEST['srm_public_review_text'] ) ) {
		update_post_meta( $post_id, '_srm_public_review_text', sanitize_text_field( $_POST['srm_public_review_text'] ) );
	}

}
add_action( 'save_post_funnel', 'srm_no_result_save_meta_box_data' );


function srm_get_last_funnel_id(){
	global $wpdb;
	$lastrowId = $wpdb->get_col( "SELECT ID FROM $wpdb->posts where post_type='funnel' and post_status='publish' ORDER BY post_date DESC " );
	if(isset($lastrowId[0])){
		$lastFunnelId = $lastrowId[0];
	}else{
		$lastFunnelId = '';
	}

	return $lastFunnelId;
}

function srm_save_all_funnels_to_options(){
	$result_arr = array();
	$srm_funnel_args = array( 'post_type' => 'funnel', 'posts_per_page' => '-1');
	$srm_funnel_query = new WP_Query( $srm_funnel_args );
	$total_funnel = 0;
	if ( $srm_funnel_query->have_posts() ) {
			while ( $srm_funnel_query->have_posts() ) {
				$srm_funnel_query->the_post();
				$funnel_id = get_the_ID();
				$funnel_name = get_the_title();
				$result_arr[$funnel_id] = $funnel_name;
				$total_funnel += 1;
			}
			wp_reset_postdata();
	}
	update_option('srm_all_funnels', $result_arr);
}
add_action( 'save_post_funnel', 'srm_save_all_funnels_to_options' );

function get_total_funnel(){
	$count_funnels = wp_count_posts('funnel');
	$published_funnels = $count_funnels->publish;

	return $published_funnels;
}


add_action( 'admin_init', 'srm_disable_all_premium_features' );
function srm_disable_all_premium_features(){
	if (! starfish_fs()->can_use_premium_code() ){
			srm_force_draft_all_generated_funnels();
	}
}

function srm_force_draft_all_generated_funnels(){
	global $wpdb;
	$args = array(
		'post_type'  => array('funnel'),
		'orderby' => 'post_date',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	$starfish_query = new WP_Query( $args );
	if ( $starfish_query->have_posts() ) {
		while ( $starfish_query->have_posts() ) {
			$starfish_query->the_post();
			$srm_post_id = get_the_ID();
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $srm_post_id ) );
		}
	}
	wp_reset_postdata();
}


if ( ! function_exists( 'starfish_upload_user_file' ) ) :
	function starfish_upload_user_file( $file = array(), $title = false ) {

		require_once ABSPATH.'wp-admin/includes/admin.php';

		$file_return = wp_handle_upload($file, array('test_form' => false));

		if(isset($file_return['error']) || isset($file_return['upload_error_handler'])){

			return false;

		}else{

			$filename = $file_return['file'];

			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_content' => '',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);

			if($title){
				$attachment['post_title'] = $title;
			}

			$attachment_id = wp_insert_attachment( $attachment, $filename );

			require_once(ABSPATH . 'wp-admin/includes/image.php');

			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );

			wp_update_attachment_metadata( $attachment_id, $attachment_data );

			if( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}

		return false;
	}
endif;

/**
 * Rearray $_FILES array for easy use
 *
 */

if ( ! function_exists( 'starFishreArrayFiles' ) ) :
	function starFishreArrayFiles(&$file_post) {

	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}
endif;

add_action( 'wp_ajax_starfish_upload_images', 'starfish_upload_images_callback' );
add_action( 'wp_ajax_nopriv_starfish_upload_images', 'starfish_upload_images_callback' );

if ( ! function_exists( 'starfish_upload_images_callback' ) ) :
	function starfish_upload_images_callback() {
		$data = array();
		$attachment_ids = array();

		if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'image_upload' ) ){
			$files = starFishreArrayFiles($_FILES['files']);
			if ( empty($_FILES['files']) ) {
				$data['status'] = false;
				$data['message'] = esc_html__('Please select an image to upload!','starfish');
			} elseif ( $files[0]['size'] > 5242880 ) { // Maximum image size is 5M
				$data['size'] = $files[0]['size'];
				$data['status'] = false;
				$data['message'] = esc_html__('Image is too large. It must be less than 2M!','starfish');
			} else {
				$i = 0;
				$data['message'] = '';
				foreach( $files as $file ){
					if( is_array($file) ){
						$attachment_id = starfish_upload_user_file( $file, false );

						if ( is_numeric($attachment_id) ) {
							$img_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
							$data['status'] = true;
							$data['photo_url'] = $img_thumb[0];
							$data['attachment_id'] = $attachment_id;
							$data['message'] .='<img width="20" src="'.$img_thumb[0].'" alt="" />';
							$attachment_ids[] = $attachment_id;
						}
					}
					$i++;
				}

				if( ! $attachment_ids ){
					$data['status'] = false;
					$data['message'] = esc_html__('An error has occured. Your image was not added.','starfish');
				}
			}
		} else {
			$data['status'] = false;
			$data['message'] = esc_html__('Nonce verify failed','starfish');
		}

		echo json_encode($data);
		die();
	}
endif;
