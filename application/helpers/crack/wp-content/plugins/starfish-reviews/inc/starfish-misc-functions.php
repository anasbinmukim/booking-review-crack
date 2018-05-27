<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function starfish_get_destination_icon($icon_type, $photo_id = '', $display = 'admin'){
  $icon_html = '';

  if($icon_type == 'Google'){
    $icon_html = '<span class="faicon_preview icon_google fab fa-google-plus-g"></span>';
  }
  if($icon_type == 'Facebook'){
    $icon_html = '<span class="faicon_preview icon_facebook fab fa-facebook-f"></span>';
  }
  if($icon_type == 'Yelp'){
    $icon_html = '<span class="faicon_preview icon_yelp fab fa-yelp"></span>';
  }
  if($icon_type == 'Tripadvisor'){
    $icon_html = '<span class="faicon_preview icon_tripadvisor fab fa-tripadvisor"></span>';
  }
  if($icon_type == 'Amazon'){
    $icon_html = '<span class="faicon_preview icon_amazon fab fa-amazon"></span>';
  }
  if($icon_type == 'Audible'){
    $icon_html = '<span class="faicon_preview icon_audible fab fa-audible"></span>';
  }
  if($icon_type == 'iTunes'){
    $icon_html = '<span class="faicon_preview icon_itunes fab fa-itunes"></span>';
  }
  if($icon_type == 'AppleAppStore'){
    $icon_html = '<span class="faicon_preview icon_apple fab fa-apple"></span>';
  }
  if($icon_type == 'GooglePlay'){
    $icon_html = '<span class="faicon_preview icon_google fab fa-google-play"></span>';
  }
  if($icon_type == 'Foursquare'){
    $icon_html = '<span class="faicon_preview icon_foursquare fab fa-foursquare"></span>';
  }
  if($icon_type == 'WordPress'){
    $icon_html = '<span class="faicon_preview icon_wordpress fab fa-wordpress"></span>';
  }
  if($icon_type == 'Etsy'){
    $icon_html = '<span class="faicon_preview icon_etsy fab fa-etsy"></span>';
  }
  if($icon_type == 'YouTube'){
    $icon_html = '<span class="faicon_preview icon_youtube fab fa-youtube"></span>';
  }
  if(($icon_type == 'Uploadimage') && ($photo_id > 0)){
      if($display == 'admin'){
        $img_thumb = wp_get_attachment_image_src( $photo_id, 'thumbnail' );
        if(isset($img_thumb[0])){
          $icon_html ='<img width="20" src="'.esc_url($img_thumb[0]).'" alt="" />';
        }
      }
  }

  return $icon_html;

}
