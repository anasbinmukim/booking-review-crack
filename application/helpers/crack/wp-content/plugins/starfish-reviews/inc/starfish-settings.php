<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="wrap">
<?php
if ( isset($_POST['srm_settings_nonce']) && (! isset( $_POST['srm_settings_nonce'] ) || ! wp_verify_nonce( $_POST['srm_settings_nonce'], 'srm_settings_action' ) ) ) {
   //Verifiy not match..
   starfish_notice_data_nonce_verify_required();
} else {
   if(isset($_POST['srm_settings_submit'])){
       update_option('srm_review_slug', sanitize_title_with_dashes($_POST['srm_review_slug']));
       if(isset($_POST['srm_branding'])){
         update_option('srm_branding', sanitize_text_field($_POST['srm_branding']));
       }else{
         update_option('srm_branding', 'show');
       }
       if(isset($_POST['srm_replay_to_email'])){
         update_option('srm_replay_to_email', sanitize_text_field($_POST['srm_replay_to_email']));
       }else{
         update_option('srm_replay_to_email', 'no');
       }
       if(isset($_POST['srm_clean_on_deactive'])){
         update_option('srm_clean_on_deactive', sanitize_text_field($_POST['srm_clean_on_deactive']));
       }else{
         update_option('srm_clean_on_deactive', 'no');
       }

       update_option('srm_affiliate_url', sanitize_text_field($_POST['srm_affiliate_url']));
       update_option('srm_email_from_name', sanitize_text_field(stripslashes($_POST['srm_email_from_name'])));
       update_option('srm_email_from_email', sanitize_text_field($_POST['srm_email_from_email']));
       update_option('srm_email_subject', sanitize_text_field(stripslashes($_POST['srm_email_subject'])));
       update_option('srm_email_template', wp_kses_post($_POST['srm_email_template']));
       starfish_notice_data_successfully_saved();
       flush_rewrite_rules();
   }
}
?>
 <h2><?php echo __( 'Starfish Settings', 'starfish' ); ?></h2>
 <form method="post" action="edit.php?post_type=starfish_review&page=starfish-settings" novalidate="novalidate">
 	<input type="hidden" name="srm_settings_page" value="srm">
 	<?php wp_nonce_field( 'srm_settings_action', 'srm_settings_nonce' ); ?>
 	<?php wp_referer_field(); ?>
 	<table class="form-table">
    <tr>
      <th scope="row"><label for="srm_review_slug"><?php echo __( 'Review Page Slug', 'starfish' ); ?></label></th>
      <td><input name="srm_review_slug" type="text" id="srm_review_slug" value="<?php echo esc_html(get_option('srm_review_slug')); ?>" class="regular-text">
        <p class="description" id="srm_review_slug-description"><?php echo __( 'Re-save WP Settings>Permalink if your funnel goes to a 404 page.', 'starfish' ); ?></p></td>
    </tr>
		<tr>
		<?php
    $branding_value = '';
		if(get_option('srm_branding')){
			$branding_value = get_option('srm_branding');
		}
		?>
		<th scope="row"><?php echo __( 'Starfish Branding', 'starfish' ); ?></th>
		<td><fieldset><legend class="screen-reader-text"><span><?php echo __( 'Starfish Branding', 'starfish' ); ?></span></legend><label for="srm_branding">
		<input name="srm_branding" type="checkbox" id="srm_branding" <?php if($branding_value == 'hide'){ ?> checked="checked" <?php } ?> value="hide">
		<?php echo __( 'Turn off Starfish branding on Funnel pages.', 'starfish' ); ?></label>
		</fieldset></td>
		</tr>
    <tr>
      <th scope="row"><label for="srm_affiliate_url"><?php echo __( 'Affiliate URL', 'starfish' ); ?></label></th>
      <td><input name="srm_affiliate_url" type="text" id="srm_affiliate_url" value="<?php echo esc_url(get_option('srm_affiliate_url')); ?>" class="regular-text">
        <p class="description" id="srm_affiliate_url-description"><?php echo __( 'Override "Powered by Starfish" link URL with <a href="https://starfishwp.com/affiliate/?sub=creatives" target="_blank">your affiliate URL</a>.', 'starfish' ); ?></p></td>
    </tr>
    <tr>
    <?php
    $srm_clean_on_deactive = '';
    if(get_option('srm_clean_on_deactive')){
      $srm_clean_on_deactive = get_option('srm_clean_on_deactive');
    }
    ?>
    <th scope="row"><?php echo __( 'Deactivation', 'starfish' ); ?></th>
    <td><fieldset><legend class="screen-reader-text"><span><?php echo __( 'Deactivation', 'starfish' ); ?></span></legend><label for="srm_clean_on_deactive">
    <input name="srm_clean_on_deactive" type="checkbox" id="srm_clean_on_deactive"  <?php checked( 'yes', $srm_clean_on_deactive, true ); ?> value="yes">
    <?php echo __( 'Delete all settings on deactivation. DO NOT use this option, unless you want to remove ALL Starfish Reviews settings and data.', 'starfish' ); ?></label>
    </fieldset></td>
    </tr>
 	</table>

  <h2><?php echo __( 'Email Settings', 'starfish' ); ?></h2>

  <table class="form-table">
    <tr>
			<th scope="row"><label for="srm_email_from_name"><?php echo __( 'From Name', 'starfish' ); ?></label></th>
			<td><input name="srm_email_from_name" type="text" id="srm_email_from_name" value="<?php echo esc_html(stripslashes(get_option('srm_email_from_name'))); ?>" class="regular-text">
			<p class="description" id="srm_email_from_name-description"><?php echo __( 'The name the email will appear to be from. Supports shortcode {site-name} to use the site\'s name.', 'starfish' ); ?></p></td>
		</tr>
    <tr>
      <th scope="row"><label for="srm_email_from_email"><?php echo __( 'From Email', 'starfish' ); ?></label></th>
      <td><input name="srm_email_from_email" type="text" id="srm_email_from_email" value="<?php echo esc_html(get_option('srm_email_from_email')); ?>" class="regular-text">
      <p class="description" id="srm_email_from_email-description"><?php echo __( 'Email address the message will appear to be from. Supports shortcode {admin-email} to use the site\'s admin email.', 'starfish' ); ?></p></td>
    </tr>
    <tr>
		<?php
    $replay_to_email_value = '';
		if(get_option('srm_replay_to_email')){
			$replay_to_email_value = get_option('srm_replay_to_email');
		}
		?>
		<th scope="row"><?php echo __( 'Reply To Email', 'starfish' ); ?></th>
		<td><fieldset><legend class="screen-reader-text"><span><?php echo __( 'Reply To Email', 'starfish' ); ?></span></legend><label for="srm_replay_to_email">
		<input name="srm_replay_to_email" type="checkbox" id="srm_replay_to_email" <?php if($replay_to_email_value == 'yes'){ ?> checked="checked" <?php } ?> value="yes">
		<?php echo __( 'Use submitter ID as "reply-to" email address.', 'starfish' ); ?></label>
    </fieldset><p class="description" id="srm_replay_to_email-description"><?php echo __( 'Will only be used if ID is a properly formatted email address.', 'starfish' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="srm_email_subject"><?php echo __( 'Email Subject', 'starfish' ); ?></label></th>
			<td><input name="srm_email_subject" type="text" id="srm_email_subject" value="<?php echo esc_html(stripslashes(get_option('srm_email_subject'))); ?>" class="regular-text">
			<p class="description" id="srm_email_subject-description"><?php echo __( 'Subject of the email. Supports shortcode {funnel-name} and {review-id}.', 'starfish' ); ?></p></td>
		</tr>
    <tr>
			<th scope="row"><label for="srm_email_template"><?php echo __( 'Message Template', 'starfish' ); ?></label></th>
			<td><textarea name="srm_email_template" id="srm_email_template" rows="5" cols="60"  placeholder="<?php echo __( 'Review Message', 'starfish' ); ?>"><?php echo esc_html(get_option('srm_email_template')); ?></textarea>
			<p class="description" id="srm_email_template-description"><?php echo __( 'Contents of the message. Make sure to include shortcode {review-message}. Also supports {funnel-name}, {review-id}, {reviewer-name}, {reviewer-email} and {reviewer-phone}.', 'starfish' ); ?></p></td>
		</tr>
 	</table>
 	<p class="submit"><input type="submit" name="srm_settings_submit" id="srm_settings_submit" class="button button-primary" value="Save Changes"></p>
 </form>
</div>
