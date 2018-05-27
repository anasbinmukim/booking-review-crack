<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
update_option('srm_review_slug', 'review');
update_option('srm_branding', 'show');
update_option('srm_yes_redirect_seconds', '0');
$srm_default_yn_question = esc_html__( 'Would you recommend {site-name}?', 'starfish' );
update_option('srm_default_yn_question', $srm_default_yn_question);
$srm_default_submit_button = esc_html__( 'Submit Review', 'starfish' );
update_option('srm_default_submit_button', $srm_default_submit_button);
$srm_default_submit_button_no = esc_html__( 'Send Feedback', 'starfish' );
update_option('srm_default_submit_button_no', $srm_default_submit_button_no);
$srm_yes_review_prompt = esc_html__( '<h3>Excellent! Please Rate us 5-stars</h3>...and leave a helpful review.', 'starfish' );
update_option('srm_default_review_prompt', $srm_yes_review_prompt);
$srm_no_review_prompt = esc_html__( 'We\'re sorry we didn\'t meet expectations. How can we do better in the future?', 'starfish' );
update_option('srm_default_no_review_prompt', $srm_no_review_prompt);
$srm_msg_no_thank_you = esc_html__( 'Thanks for your feedback. We\'ll review it and work to improve.', 'starfish' );
update_option('srm_msg_no_thank_you', $srm_msg_no_thank_you);
update_option('srm_default_feedback_email', '{admin-email}');
update_option('srm_email_from_name', '{site-name}');
update_option('srm_email_from_email', '{admin-email}');
$srm_email_subject = esc_html__( 'Feedback from {funnel-name}', 'starfish' );
update_option('srm_email_subject', $srm_email_subject);
update_option('srm_email_template', 'Submitter ID: {review-id}
Message: {review-message}

{reviewer-name}
{reviewer-email}
{reviewer-phone}
');
