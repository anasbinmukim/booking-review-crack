<?php require_once('srm-header.php'); ?>

<?php do_action('before-starfish-review-form'); ?>
<?php
  $srm_funnel_id = get_the_ID();
  echo do_shortcode('[starfish funnel="'.$srm_funnel_id.'"]');
?>
<?php do_action('after-starfish-review-form'); ?>

<?php require_once('srm-footer.php'); ?>
