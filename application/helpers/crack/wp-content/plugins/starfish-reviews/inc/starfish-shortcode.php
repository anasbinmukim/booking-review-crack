<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
wp_enqueue_style('srm-review-front');
$direct_feedback_type = '';
if(isset($_GET['feedback'])){ $direct_feedback_type = esc_html($_GET['feedback']); }
$funnel_id = $funnel;
if(!get_post_status( $funnel_id )){
	echo __( 'This funnel is not valid or published yet!', 'starfish' );
	return;
}
?>
<div id="srm_review_form" class="srm-review-form srm-review-funnel-<?php echo $funnel_id; ?>">
	<?php if (has_post_thumbnail( $funnel_id ) ){ ?>
	<?php
		$funnel_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($funnel_id), 'full');
		if(isset($funnel_thumb[0])){
			echo '<div class="funnel_logo"><img class="funnel_logo_img" src="'.$funnel_thumb[0].'" title="" alt="" /></div>';
		}
	?>
	<?php } ?>
<form class="srm_form" id="srm_review_form" action="" method="post">
<?php
$srm_yn_question = esc_html(get_post_meta( $funnel_id, '_srm_yn_question', true ));
$srm_no_destination = esc_html(get_post_meta( $funnel_id, '_srm_no_destination', true ));
$srm_review_destination_url = esc_url(get_post_meta( $funnel_id, '_srm_review_destination', true ));
$srm_review_auto_redirect_seconds = intval(get_post_meta( $funnel_id, '_srm_review_auto_redirect', true ));
$replace_site_name = get_bloginfo( 'name' );
$srm_yn_question = str_replace('{site-name}', $replace_site_name, $srm_yn_question);
$tracking_id = '';
if(isset($_GET['id'])){ $tracking_id = esc_html($_GET['id']); }
?>
<h2 class="question_heading"><?php echo $srm_yn_question; ?></h2>
<?php
	$lebel_yes = '';
	$lebel_no = '';
	$srm_button_style = esc_html(get_post_meta( $funnel_id, '_srm_button_style', true ));
	if($srm_button_style == 'thumbs_outline'){
			$lebel_yes = '<span class="faicon iconyes far fa-thumbs-up"></span>';
			$lebel_no = '<span class="faicon iconno far fa-thumbs-down faicon_flip"></span>';
	}elseif($srm_button_style == 'thumbs_solid'){
			$lebel_yes = '<span class="faicon iconyes fas fa-thumbs-up"></span>';
			$lebel_no = '<span class="faicon iconno fas fa-thumbs-down faicon_flip"></span>';
	}elseif($srm_button_style == 'faces'){
			$lebel_yes = '<span class="faicon iconyes far fa-smile"></span>';
			$lebel_no = '<span class="faicon iconno far fa-frown"></span>';
	}elseif($srm_button_style == 'scircle'){
			$lebel_yes = '<span class="faicon iconyes fas fa-check-circle"></span>';
			$lebel_no = '<span class="faicon iconno fas fa-times-circle"></span>';
	}else{
		$lebel_yes = '<span class="faicon iconyes far fa-thumbs-up"></span>';
		$lebel_no = '<span class="faicon iconno far fa-thumbs-down faicon_flip"></span>';
	}

	if($srm_no_destination == ''){
		$srm_no_destination = 'single';
	}

	if($srm_no_destination == 'multiple'){
		$funnel_desti_type = 'multiple';
	}else{
		$funnel_desti_type = 'single';
	}

	$srm_no_thank_you_msg = esc_html(get_post_meta( $funnel_id, '_srm_no_thank_you_msg', true ));

	$current_yes_no_value = '';
	if($direct_feedback_type != ''){
		$custom_css = '';
		$custom_css .= '<style>';
		$custom_css .= '.question_heading,.yes-no-checked{display:none;}';

		if($direct_feedback_type == 'p'){
			$custom_css .= '#review_no_section{display:none;}';
			$custom_css .= '#review_yes_section{display:block;}';
			$custom_css .= '#submit_review{display:inline-block;}';
			$current_yes_no_value = 'Yes';
		}
		if(($direct_feedback_type == 'p') && ($funnel_desti_type == 'single')){
			$custom_css .= '#submit_review{display:inline-block;}';
		}
		if(($direct_feedback_type == 'p') && ($funnel_desti_type == 'multiple')){
			$custom_css .= '#submit_review{display:none;}';
		}
		if($direct_feedback_type == 'n'){
			$custom_css .= '#review_yes_section{display:none;}';
			$custom_css .= '#review_no_section{display:block;}';
			$custom_css .= '#submit_review{display:inline-block;}';
			$current_yes_no_value = 'No';
		}

		$custom_css .= '</style>';

		echo $custom_css;
	}
?>
<div class="review_submit_form_field">
	<div class="yes-no-checked" id="yes-no-checked">
			<div class="radio_item radio_item_yes"><input <?php checked( $current_yes_no_value, 'Yes' ); ?> type="radio" name="yes_no_flag" class="srm-radio" id="srm_review_yes" value="Yes"> <label for="srm_review_yes"><?php echo $lebel_yes; ?></label></div>
			<div class="radio_item radio_item_no"><input <?php checked( $current_yes_no_value, 'No' ); ?> type="radio" name="yes_no_flag" class="srm-radio" id="srm_review_no" value="No"> <label for="srm_review_no"><?php echo $lebel_no; ?></label></div>
	</div>
	<div class="review_yes_section review_yes_no_section" id="review_yes_section">
		<div class="yes-prompt-text">
			<?php echo get_post_meta( $funnel_id, '_srm_yes_review_prompt', true ); ?>
		</div>
		<?php
		if($srm_no_destination == 'multiple'){
			$srm_multi_desti = get_post_meta( $funnel_id, '_srm_multi_desti', true );
			$output_icon_style = '';
			?>
			<div class="review-multiple-destination">
				<?php if(isset($srm_multi_desti) && is_array($srm_multi_desti) && (count($srm_multi_desti) > 0)){ ?>
					<ul class="multi-desti-buttons">
					<?php foreach ($srm_multi_desti as $key => $multi_desti) { ?>
							<?php
								$srm_destination_icon = esc_html( $multi_desti['desti_icon'] );
								$icon_photo_id = intval( $multi_desti['icon_photo_id'] );
								$srm_desti_color = esc_html( $multi_desti['srm_desti_color'] );
								$srm_desti_bg_color = esc_html( $multi_desti['srm_desti_bg_color'] );
								$srm_desti_name = esc_html( $multi_desti['srm_desti_name'] );
								$srm_desti_url = esc_url( $multi_desti['srm_desti_url'] );

								$icon_class = '';
								if($srm_destination_icon != ''){
									$icon_class = sanitize_title_with_dashes($srm_destination_icon);
								}
								if($srm_desti_color != ''){
									$output_icon_style .= '.multi-desti-buttons .'.$icon_class.' a{color: '.$srm_desti_color.'}';
								}
								if($srm_desti_bg_color != ''){
									$output_icon_style .= '.multi-desti-buttons .'.$icon_class.' a{background: '.$srm_desti_bg_color.'}';
								}
							?>
							<li class="<?php echo $icon_class; ?>"><a href="javascript:void(0)" class="multi-desti-submit" data-icon="<?php echo esc_attr($srm_destination_icon); ?>"  data-photo_id="<?php echo $icon_photo_id; ?>"  data-desti_name="<?php echo esc_attr($srm_desti_name); ?>" data-desti_url="<?php echo $srm_desti_url; ?>">
							<?php echo starfish_get_destination_icon($srm_destination_icon, $icon_photo_id, 'frontend'); ?></a><?php if($srm_desti_name != ''){ ?><span class="destination-name"><?php echo esc_html($srm_desti_name); ?></span><?php } ?></li>
						<?php } ?>
					</ul>
					<?php
					if($output_icon_style != ''){
						echo '<style>'.$output_icon_style.'</style>';
					}
					?>
					<?php } ?>
			</div>
		<?php } ?>
		<input type="hidden" name="reveiw_destination_url" id="reveiw_destination_url" value="<?php echo $srm_review_destination_url; ?>">
	</div>
	<div class="review_no_section review_yes_no_section" id="review_no_section">
		<?php echo get_post_meta( $funnel_id, '_srm_no_review_prompt', true ); ?>
		<p>
			<textarea name="review_text" id="review_text" placeholder="<?php echo __( 'Leave your review.', 'starfish' ); ?>"></textarea>
			<span class="alert_message review_text_alert"></span>
		</p>

		<?php if(get_post_meta( $funnel_id, '_srm_ask_name', true ) == 'yes'){ ?>
		<p>
			<?php $name_required = $required_class = ''; if(get_post_meta( $funnel_id, '_srm_ask_name_required', true ) == 'yes'){ $name_required = __( ' (Required)', 'starfish' ); $required_class = ' required '; } ?>
			<input type="text" class="<?php echo $required_class; ?>" name="srm_reviewer_name" id="srm_reviewer_name" placeholder="<?php echo __( 'Your Name', 'starfish' ); echo $name_required; ?>">
			<span class="alert_message name_alert"><?php echo esc_html__( 'Please enter your name.', 'starfish' ); ?></span>
		</p>
		<?php } ?>
		<?php if(get_post_meta( $funnel_id, '_srm_ask_email', true ) == 'yes'){ ?>
		<p>
			<?php $email_required = $required_class = ''; if(get_post_meta( $funnel_id, '_srm_ask_email_required', true ) == 'yes'){ $email_required = __( ' (Required)', 'starfish' ); $required_class = ' required '; } ?>
			<input type="text" class="<?php echo $required_class; ?>" name="srm_reviewer_email" id="srm_reviewer_email" placeholder="<?php echo __( 'Your Email', 'starfish' ); echo $email_required; ?>">
			<span class="alert_message email_alert"><?php echo esc_html__( 'Please enter your email address.', 'starfish' ); ?></span>
		</p>
		<?php } ?>
		<?php if(get_post_meta( $funnel_id, '_srm_ask_phone', true ) == 'yes'){ ?>
		<p>
			<?php $phone_required = $required_class = ''; if(get_post_meta( $funnel_id, '_srm_ask_phone_required', true ) == 'yes'){ $phone_required = __( ' (Required)', 'starfish' ); $required_class = ' required '; } ?>
			<input type="text" class="<?php echo $required_class; ?>" name="srm_reviewer_phone" id="srm_reviewer_phone" placeholder="<?php echo __( 'Your Phone', 'starfish' ); echo $phone_required; ?>">
			<span class="alert_message phone_alert"><?php echo esc_html__( 'Please enter your phone number.', 'starfish' ); ?></span>
		</p>
		<?php } ?>

		<input type="hidden" name="reveiw_no_thank_you" id="reveiw_no_thank_you" value="<?php echo $srm_no_thank_you_msg; ?>">
	</div>
	<input type="hidden" name="funnel_desti_type" id="funnel_desti_type" value="<?php echo $funnel_desti_type; ?>">
	<input type="hidden" name="funnel_id" id="funnel_id" value="<?php echo $funnel_id; ?>">
	<input type="hidden" name="tracking_id" id="tracking_id" value="<?php echo $tracking_id; ?>">
	<?php $srm_reveiw_nonce = wp_create_nonce( "srm_reveiw_nonce" ); ?>
	<input type="button" class="btn_review_submit" name="submit_review" id="submit_review" value="<?php echo esc_attr(get_post_meta( $funnel_id, '_srm_button_text', true )); ?>">
</div><!-- review_submit_form_field -->
</form>
<div class="review_under_processing">Sending...</div>
<?php if((get_post_meta( $funnel_id, '_srm_disable_review_gating', true ) == 'yes')){ ?>
		<?php
			$srm_public_review_text = esc_html(get_post_meta( $funnel_id, '_srm_public_review_text', true ));
			if($srm_public_review_text == ''){
				$srm_public_review_text = esc_html__('Leave a Public Review', 'starfish');
			}
		?>
		<div class="srm-public-review-button">
		<?php if($srm_no_destination == 'single'){ ?>
			<a href="javascript:void(0)" class="srm-leave-public-review public-review-button" data-desti_name="" data-desti_url="<?php echo $srm_review_destination_url; ?>">
			<?php echo $srm_public_review_text; ?>
			</a>
		<?php } ?>
		<?php
		if($srm_no_destination == 'multiple'){
			$srm_multi_desti = get_post_meta( $funnel_id, '_srm_multi_desti', true );
			$output_icon_style = '';
			?>
			<h3><?php echo $srm_public_review_text; ?></h3>
			<div class="review-multiple-destination">
				<?php if(isset($srm_multi_desti) && is_array($srm_multi_desti) && (count($srm_multi_desti) > 0)){ ?>
					<ul class="multi-desti-buttons">
					<?php foreach ($srm_multi_desti as $key => $multi_desti) { ?>
							<?php
								$srm_destination_icon = esc_html( $multi_desti['desti_icon'] );
								$icon_photo_id = intval( $multi_desti['icon_photo_id'] );
								$srm_desti_color = esc_html( $multi_desti['srm_desti_color'] );
								$srm_desti_bg_color = esc_html( $multi_desti['srm_desti_bg_color'] );
								$srm_desti_name = esc_html( $multi_desti['srm_desti_name'] );
								$srm_desti_url = esc_url( $multi_desti['srm_desti_url'] );

								$icon_class = '';
								if($srm_destination_icon != ''){
									$icon_class = sanitize_title_with_dashes($srm_destination_icon);
								}
							?>
							<li class="<?php echo $icon_class; ?>"><a href="javascript:void(0)" class="srm-leave-public-review" data-icon="<?php echo esc_attr($srm_destination_icon); ?>"  data-photo_id="<?php echo $icon_photo_id; ?>"  data-desti_name="<?php echo esc_attr($srm_desti_name); ?>" data-desti_url="<?php echo $srm_desti_url; ?>">
							<?php echo starfish_get_destination_icon($srm_destination_icon, $icon_photo_id, 'frontend'); ?>
							</a></li>
						<?php } ?>
					</ul>
					<?php } ?>
			</div>
		<?php } ?>
		<input type="hidden" id="srm-created-review-id" value="">
		</div><!-- srm-public-review-button -->
<?php } ?>

<?php $min_review_alert_text_1 = esc_html__( 'You need to enter at least', 'starfish' ); ?>
<?php $min_review_alert_text_2 = esc_html__( 'characters.', 'starfish' ); ?>

<?php
$srm_btn_no_text = 'Send Feedback';
if(get_post_meta( $funnel_id, '_srm_button_text_no', true ) != ''){
	$srm_btn_no_text = esc_attr(get_post_meta( $funnel_id, '_srm_button_text_no', true ));
}
?>



<script type="text/javascript">
	var srm_ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	var submit_no_text = "<?php echo $srm_btn_no_text; ?>";
	jQuery(document).ready(function(){
				jQuery('#srm_review_yes').change(function(e) {
					e.preventDefault();
					jQuery('#srm_review_no').attr('checked', false);
					jQuery('#srm_review_yes').attr('checked', true);
					jQuery( "#review_no_section" ).hide( "slow");
					jQuery( "#review_yes_section" ).show( "slow");
					jQuery( "#submit_review" ).show( "slow");
					jQuery( "#yes-no-checked" ).hide( "slow");
					jQuery( ".question_heading" ).hide( "slow");
					<?php
						if($srm_no_destination == 'multiple'){
							?>
							jQuery( "#submit_review" ).hide();
							<?php
						}
					?>
					<?php
						if(($srm_review_auto_redirect_seconds > 0) && ($srm_no_destination == 'single')){
							$auto_forward_seconds = $srm_review_auto_redirect_seconds * 1000;
							?>
							jQuery( "#submit_review" ).hide();
							setTimeout(function() {
									jQuery('.btn_review_submit').trigger('click');
							}, <?php echo $auto_forward_seconds; ?>);
							<?php
						}
					?>

				});
				jQuery('#srm_review_no').change(function (e) {
					e.preventDefault();
					jQuery( ".alert_message" ).hide();
					jQuery('#submit_review').val( submit_no_text );
					jQuery('#srm_review_yes').attr('checked', false);
					jQuery('#srm_review_no').attr('checked', true);
					jQuery( "#review_yes_section" ).hide( "slow");
					jQuery( "#review_no_section" ).show( "slow");
					jQuery( "#submit_review" ).show( "slow");
					jQuery( "#yes-no-checked" ).hide( "slow");
					jQuery( ".question_heading" ).hide( "slow");
				});
	});
  jQuery(document).ready(function($) {
      jQuery('.review_under_processing').hide();
      jQuery('.btn_review_submit').click(function() {
					var yes_no_flag = jQuery(".yes-no-checked input[type='radio']:checked").val();
					var minLength = 10;
					var check_required = false;
					if((jQuery('#review_text').val().length < minLength) && (yes_no_flag !== 'Yes')) {
						 var reveiw_txt_alert = '<?php echo $min_review_alert_text_1; ?> ' + minLength + ' <?php echo $min_review_alert_text_2; ?>';
						 jQuery('.review_text_alert').show();
						 jQuery('.review_text_alert').html(reveiw_txt_alert);
						 check_required = true;
					}
					<?php if(get_post_meta( $funnel_id, '_srm_ask_name', true ) == 'yes'){ ?>
					if((jQuery('#srm_reviewer_name').val().length < 2) && (jQuery('#srm_reviewer_name').hasClass( "required" ))) {
							jQuery('.name_alert').show();
							check_required = true;
					}
					<?php } ?>
					<?php if(get_post_meta( $funnel_id, '_srm_ask_email', true ) == 'yes'){ ?>
					if((jQuery('#srm_reviewer_email').val().length < 2) && (jQuery('#srm_reviewer_email').hasClass( "required" ))) {
							jQuery('.email_alert').show();
							check_required = true;
					}
					<?php } ?>
					<?php if(get_post_meta( $funnel_id, '_srm_ask_phone', true ) == 'yes'){ ?>
					if((jQuery('#srm_reviewer_phone').val().length < 2) && (jQuery('#srm_reviewer_phone').hasClass( "required" ))) {
							jQuery('.phone_alert').show();
							check_required = true;
					}
					<?php } ?>
					if(check_required && (yes_no_flag !== 'Yes')){
						return false;
					}
          jQuery('.review_under_processing').show();
					var reveiw_destination_url = jQuery('#reveiw_destination_url').val();
					var reveiw_yes_thank_you = jQuery('#reveiw_yes_thank_you').val();
					var reveiw_no_thank_you = jQuery('#reveiw_no_thank_you').val();
					var srm_reviewer_name = jQuery('#srm_reviewer_name').val();
					var srm_reviewer_email = jQuery('#srm_reviewer_email').val();
					var srm_reviewer_phone = jQuery('#srm_reviewer_phone').val();
					var funnel_desti_type = jQuery('#funnel_desti_type').val();
					var reveiw_msg_thank_you = '';
					if(yes_no_flag !== 'Yes'){
							reveiw_msg_thank_you = reveiw_no_thank_you;
					}
          var dataContainer = {
              security: '<?php echo $srm_reveiw_nonce; ?>',
              yes_no_flag: yes_no_flag,
              funnel_id: jQuery('#funnel_id').val(),
							tracking_id: jQuery('#tracking_id').val(),
							review_text: jQuery('#review_text').val(),
							desti_type: funnel_desti_type,
							reviewer_name: srm_reviewer_name,
							reviewer_email: srm_reviewer_email,
							reviewer_phone: srm_reviewer_phone,
							reveiw_destination_url: reveiw_destination_url,
			        action: 'send-starfish-review-data'
          };
          jQuery.ajax({
              action: "send-starfish-review-data",
              type: "POST",
              dataType: "json",
              url: srm_ajaxurl,
              data: dataContainer,
              success: function(data){
              	//alert(data.msg);
                if(data.msg == 'Complete'){
                  jQuery('.review_under_processing').html('<div class="success">'+reveiw_msg_thank_you+'</div>');
									jQuery('.srm-public-review-button').show();
                  //jQuery('.review_under_processing').delay(3000).fadeOut('slow');
									//jQuery('.review_submit_form_field').delay(1000).fadeOut('slow');
									jQuery('.review_submit_form_field').hide();
									//Set just now retruned review ID
									jQuery('#srm-created-review-id').val(data.review_id);
									if(yes_no_flag === 'Yes'){
										window.location = reveiw_destination_url;
									}
                }else{
                  jQuery('.review_under_processing').html('<span class="error">Sending error</span>');
                  //jQuery('.review_under_processing').delay(3000).fadeOut('slow');
									jQuery('.review_under_processing').hide();
                }
              }
          });
      });
  });

	//Process multiple destination
	jQuery(document).ready(function($) {
			jQuery('.multi-desti-submit').click(function() {
					var desti_url = jQuery(this).data('desti_url');
					var yes_no_flag = jQuery(".yes-no-checked input[type='radio']:checked").val();
					jQuery('.review_under_processing').show('slow');
					var reveiw_destination_url = jQuery(this).data('desti_url');
					var desti_name = jQuery(this).data('desti_name');
					var reveiw_yes_thank_you = jQuery('#reveiw_yes_thank_you').val();
					var reveiw_no_thank_you = jQuery('#reveiw_no_thank_you').val();
					var reveiw_msg_thank_you = '';
					if(yes_no_flag !== 'Yes'){
							reveiw_msg_thank_you = reveiw_no_thank_you;
					}
					var dataContainer = {
							security: '<?php echo $srm_reveiw_nonce; ?>',
							yes_no_flag: yes_no_flag,
							funnel_id: jQuery('#funnel_id').val(),
							tracking_id: jQuery('#tracking_id').val(),
							desti_type: 'multiple',
							review_text: '',
							reviewer_name: '',
							reviewer_email: '',
							reviewer_phone: '',
							reveiw_destination_url: reveiw_destination_url,
							desti_name: desti_name,
							action: 'send-starfish-review-data'
					};
					jQuery.ajax({
							action: "send-starfish-review-data",
							type: "POST",
							dataType: "json",
							url: srm_ajaxurl,
							data: dataContainer,
							success: function(data){
								//alert(data.msg);
								if(data.msg == 'Complete'){
									jQuery('.review_under_processing').html('<div class="success">'+reveiw_msg_thank_you+'</div>');
									jQuery('.srm-public-review-button').hide();
									jQuery('.review_submit_form_field').hide();
									//Set just now retruned review ID
									jQuery('#srm-created-review-id').val(data.review_id);
									if(yes_no_flag === 'Yes'){
										window.location = reveiw_destination_url;
									}
								}else{
									jQuery('.review_under_processing').html('<span class="error">Sending error</span>');
									//jQuery('.review_under_processing').delay(3000).fadeOut('slow');
									jQuery('.review_under_processing').hide();
								}
							}
					});
			});
	});

	//Process public reveiw
	jQuery(document).ready(function($) {
			jQuery('.srm-leave-public-review').click(function() {
					var desti_name = jQuery(this).data('desti_name');
					var desti_url = jQuery(this).data('desti_url');
					var funnel_desti_type = jQuery('#funnel_desti_type').val();
					//window.location = desti_url;
					var UpdateDataContainer = {
							security: '<?php echo $srm_reveiw_nonce; ?>',
							desti_name: desti_name,
							desti_url: desti_url,
							desti_type: funnel_desti_type,
							review_id: jQuery('#srm-created-review-id').val(),
							action: 'update-starfish-review-data'
					};
					jQuery.ajax({
							action: "update-starfish-review-data",
							type: "POST",
							dataType: "json",
							url: srm_ajaxurl,
							data: UpdateDataContainer,
							success: function(data){
								//alert(data.msg);
								if(data.msg == 'Complete'){
										window.location = desti_url;
								}else{
									jQuery('.review_under_processing').html('<span class="error">Sending error</span>');
									jQuery('.review_under_processing').hide();
								}
							}
					});
			});
	});
</script>
<?php if(get_option('srm_branding', true) != 'hide'){ ?>
<?php
	if(get_option('srm_affiliate_text') != ''){
		//$srm_affiliate_text = esc_html(get_option('srm_affiliate_text'));
		$srm_affiliate_text = esc_html__( 'Powered by Starfish', 'starfish' );
	}else{
		$srm_affiliate_text = esc_html__( 'Powered by Starfish', 'starfish' );
	}
	if(get_option('srm_affiliate_url') != ''){
		$srm_affiliate_url = esc_url(get_option('srm_affiliate_url'));
	}else{
		$srm_affiliate_url = 'https://starfish.reviews/';
	}
?>
<div id="srm_powred_by_txt" class="srm-powered-by"><a href="<?php echo $srm_affiliate_url; ?>" target="_blank"><?php echo $srm_affiliate_text; ?></a></div><!-- srm-powered-by -->
<?php } ?>
</div><!-- srm-review-form -->
