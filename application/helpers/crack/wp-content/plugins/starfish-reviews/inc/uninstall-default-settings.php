<?php
if ( ! defined( 'ABSPATH' ) ) {
exit;
}
if(get_option('srm_clean_on_deactive') === 'yes'){
  update_option('srm_review_slug', '');
  update_option('srm_branding', '');
  update_option('srm_yes_redirect_seconds', '');
  update_option('srm_default_yn_question', '');
  update_option('srm_default_submit_button', '');
  update_option('srm_default_review_prompt', '');
  update_option('srm_default_no_review_prompt', '');
  update_option('srm_msg_no_thank_you', '');
  update_option('srm_default_feedback_email', '');
  update_option('srm_email_from_name', '');
  update_option('srm_email_from_email', '');
  update_option('srm_replay_to_email', '');
  update_option('srm_email_subject', '');
  update_option('srm_email_template', '');
  update_option('srm_destination_name', '');
  update_option('srm_affiliate_url', '');
  srm_clear_all_generated_post_data();
  update_option('srm_clean_on_deactive', '');
}
function srm_clear_all_generated_post_data(){
	$args = array(
		'post_type'  => array('funnel', 'starfish_review'),
		'post_status' => 'any',
		'orderby' => 'post_date',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	$starfish_query = new WP_Query( $args );
	if ( $starfish_query->have_posts() ) {
		while ( $starfish_query->have_posts() ) {
			$starfish_query->the_post();
			$srm_post_id = get_the_ID();
      wp_delete_post( $srm_post_id, true );
		}
	}
	wp_reset_postdata();
}
