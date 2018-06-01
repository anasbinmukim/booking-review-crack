<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
**Process Submitted Reviews
**/
function srm_send_starfish_review_data() {
	$funnel_id = intval($_POST['funnel_id']);
	$tracking_id = sanitize_text_field($_POST['tracking_id']);
	$yes_no_flag = sanitize_text_field($_POST['yes_no_flag']);
	$review_text = sanitize_textarea_field($_POST['review_text']);
	$reveiw_destination_url = sanitize_text_field($_POST['reveiw_destination_url']);

	$srm_reviewer_name = sanitize_text_field($_POST['reviewer_name']);
	$srm_reviewer_email = sanitize_text_field($_POST['reviewer_email']);
	$srm_reviewer_phone = sanitize_text_field($_POST['reviewer_phone']);

	$desti_type = sanitize_text_field($_POST['desti_type']);
	$srm_desti_name = sanitize_text_field($_POST['desti_name']);



	$current_local_time = date( 'j M Y g:i a', current_time( 'timestamp', 0 ) );

	if (!check_ajax_referer( 'srm_reveiw_nonce', 'security' )) {
		echo json_encode(array("msg" => "Error"));
		exit;
	}else{
		$defaults_review_arg = array(
					  'post_type'      => 'starfish_review',
					  'post_title'     => $current_local_time,
					  'post_content'  =>   $review_text,
					  'post_status'    => 'publish'
					);
					if($review_id = wp_insert_post( $defaults_review_arg )) {
			  		// add user to profile cpt
			  		add_post_meta($review_id, '_srm_feedback', $yes_no_flag);
						add_post_meta($review_id, '_srm_funnel_id', $funnel_id);
						add_post_meta($review_id, '_srm_tracking_id', $tracking_id);
						add_post_meta($review_id, '_srm_destination_url', $reveiw_destination_url);
						add_post_meta($review_id, '_srm_desti_type', $desti_type);

						//Single destination negative feedback set destination "Yes"
						if(($yes_no_flag == 'Yes') && ($desti_type == 'single')){
							add_post_meta($review_id, '_srm_desti_name', 'Yes');
						}

						if(($yes_no_flag != 'Yes') && (($desti_type == 'single') || ($desti_type == 'multiple'))){
							add_post_meta($review_id, '_srm_desti_name', 'No');
						}

						if(($srm_desti_name != '') && ($desti_type == 'multiple')){
							add_post_meta($review_id, '_srm_desti_name', $srm_desti_name);
						}

						if($srm_reviewer_name != ''){
							add_post_meta($review_id, '_srm_reviewer_name', $srm_reviewer_name);
						}
						if($srm_reviewer_email != ''){
							add_post_meta($review_id, '_srm_reviewer_email', $srm_reviewer_email);
						}
						if($srm_reviewer_phone != ''){
							add_post_meta($review_id, '_srm_reviewer_phone', $srm_reviewer_phone);
						}
			  	}
		//Send email if negative feedback
		if($yes_no_flag != 'Yes'){
			srm_send_feedback_email($funnel_id, $review_text, $tracking_id, $review_id);
		}
		echo json_encode(array("msg" => "Complete", "review_id" => $review_id));
		exit;
	}

}
add_action('wp_ajax_send-starfish-review-data', 'srm_send_starfish_review_data');
add_action('wp_ajax_nopriv_send-starfish-review-data', 'srm_send_starfish_review_data');


/**
**Update Submitted Reviews
**/
function srm_update_starfish_review_data() {
	$review_id = intval($_POST['review_id']);
	$desti_url = sanitize_text_field($_POST['desti_url']);
	$desti_name = sanitize_text_field($_POST['desti_name']);
	$desti_type = sanitize_text_field($_POST['desti_type']);

	if (!check_ajax_referer( 'srm_reveiw_nonce', 'security' )) {
		echo json_encode(array("msg" => "Error"));
		exit;
	}else{

		if(($desti_type == 'single')){
			update_post_meta($review_id, '_srm_desti_name', 'Yes');
		}

		if(($desti_name != '') && ($desti_type == 'multiple')){
			update_post_meta($review_id, '_srm_desti_name', $desti_name);
		}

		echo json_encode(array("msg" => "Complete"));
		exit;
	}

}
add_action('wp_ajax_update-starfish-review-data', 'srm_update_starfish_review_data');
add_action('wp_ajax_nopriv_update-starfish-review-data', 'srm_update_starfish_review_data');


function srm_send_feedback_email($funnel_id, $reveiw_message, $tracking_id, $review_id){

	$admin_email = get_option('admin_email');
	$blogname = get_option('blogname');

	$funnel_name = get_the_title($funnel_id);
	$email_subject = stripslashes(get_option('srm_email_subject'));
	$search_subject = array();
	$replace_subject = array();
	$search_subject[] = '{review-id}';
	$replace_subject[] = $tracking_id;
	$search_subject[] = '{funnel-name}';
	$replace_subject[] = $funnel_name;
	$email_subject = str_replace($search_subject, $replace_subject, $email_subject);
	$email_subject = wp_specialchars_decode( esc_html( $email_subject ), ENT_QUOTES );

	$reviewer_name = esc_html(get_post_meta($review_id, '_srm_reviewer_name', true));
	$reviewer_email = esc_html(get_post_meta($review_id, '_srm_reviewer_email', true));
	$reviewer_phone = esc_html(get_post_meta($review_id, '_srm_reviewer_phone', true));



	//process email message
	$email_message = get_option('srm_email_template');
	$search = array();
	$replace = array();
	$search[] = '{admin-email}';
	$replace[] = $admin_email;
	$search[] = '{site-name}';
	$replace[] = $blogname;
	$search[] = '{funnel-name}';
	$replace[] = $funnel_name;
	$search[] = '{review-id}';
	$replace[] = $tracking_id;
	$search[] = '{reviewer-name}';
	$replace[] = $reviewer_name;
	$search[] = '{reviewer-email}';
	$replace[] = $reviewer_email;
	$search[] = '{reviewer-phone}';
	$replace[] = $reviewer_phone;
	$search[] = '{review-message}';
	$replace[] = $reveiw_message;
	$get_message = str_replace($search, $replace, $email_message);
	$get_message = 	stripslashes($get_message);
	$message_send = apply_filters('the_content', $get_message);

	//process email addresses
	$srm_email_feedback = esc_html(get_post_meta( $funnel_id, '_srm_email_feedback', true ));
	$search = array();
	$replace = array();
	$search[] = '{admin-email}';
	$replace[] = $admin_email;
	$get_email_addresses = str_replace($search, $replace, $srm_email_feedback);
	$get_email_address_arr = explode(",", $get_email_addresses);

	$srm_email_from_name = esc_html(stripslashes(get_option('srm_email_from_name')));
	$search = array();
	$replace = array();
	$search[] = '{site-name}';
	$replace[] = $blogname;
	$srm_email_from_name = str_replace($search, $replace, $srm_email_from_name);

	$srm_email_from_email = esc_html(get_option('srm_email_from_email'));
	$search = array();
	$replace = array();
	$search[] = '{admin-email}';
	$replace[] = $admin_email;
	$srm_email_from_email = str_replace($search, $replace, $srm_email_from_email);

	//if tracking id is valid email address then use it for replay to email.
	$srm_email_replay_to_email = '';
	if(is_email( $tracking_id ) && (get_option('srm_replay_to_email') == 'yes')){
		$srm_email_replay_to_email = $tracking_id;
	}

	if(is_email( $reviewer_email )){
		$srm_email_replay_to_email = $reviewer_email;
	}

	$headers[]  = 'MIME-Version: 1.0' . "\r\n";
	$headers[] = 'Content-Type: text/html; charset=UTF-8' . "\r\n";

	if(($srm_email_from_name != '') && ($srm_email_from_email != '')){
		$headers[] = "From: " . wp_specialchars_decode( esc_html( $srm_email_from_name ), ENT_QUOTES ) . " <" . apply_filters( 'starfish_notification_email_header_from_email', $srm_email_from_email ) . ">\r\n";
	}
	if(($srm_email_replay_to_email != '')){
		$headers[] = 'Reply-To: <'.$srm_email_replay_to_email.'>';
	}

	if(isset($get_email_address_arr) && is_array($get_email_address_arr) && (count($get_email_address_arr) > 0)){
		foreach ($get_email_address_arr as $key => $email_address) {
				$email_address = trim($email_address);
				add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
				@wp_mail( $email_address, $email_subject, $message_send, $headers );
		}
	}

}
