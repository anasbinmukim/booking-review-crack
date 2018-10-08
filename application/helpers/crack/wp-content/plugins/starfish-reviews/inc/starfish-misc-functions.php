<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function starfish_get_destination_icon($icon_type, $photo_id = '', $display = 'admin'){
  $icon_html = '';

  if($icon_type == 'Google'){
    $icon_html = '<span class="faicon_preview icon_google fab fa-google"></span>';
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
    $icon_html = '<span class="faicon_preview icon_itunes fab fa-itunes-note"></span>';
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
      }else{
        $img_thumb = wp_get_attachment_image_src( $photo_id, 'thumbnail' );
        if(isset($img_thumb[0])){
          $icon_html ='<img width="100" src="'.esc_url($img_thumb[0]).'" alt="" />';
        }
      }
  }

  return $icon_html;

}


function starfish_get_name_from_destination_url($url){
  $destination_domain = '';
  $destination_domain_name = '';

  if($url_data = parse_url($url)){
    if(isset($url_data['host'])){
        $destination_domain = str_replace('www.', '', $url_data['host']);

        $domain_parts = explode('.', $destination_domain);

        if(isset($domain_parts[0])){
          $destination_domain_name = ucwords($domain_parts[0]);
        }
    }
  }

  return $destination_domain_name;

  switch ($destination_domain) {
    case "google.com":
        $desti_name = 'Google';
        break;
    case "facebook.com":
        $desti_name = 'Facebook';
        break;
    case "yelp.com":
        $desti_name = 'Yelp';
        break;
    case "tripadvisor.com":
        $desti_name = 'Tripadvisor';
        break;
    case "amazon.com":
        $desti_name = 'Amazon';
        break;
    case "audible.com":
        $desti_name = 'Audible';
        break;
    case "itunes.com":
        $desti_name = 'iTunes';
        break;
    case "apple.com":
        $desti_name = 'AppleAppStore';
        break;
    case "support.google.com":
        $desti_name = 'GooglePlay';
        break;
    case "foursquare.com":
        $desti_name = 'Foursquare';
        break;
    case "wordpress.org":
        $desti_name = 'WordPress';
        break;
    case "etsy.com":
        $desti_name = 'Etsy';
        break;
    case "youtube.com":
        $desti_name = 'YouTube';
        break;
    default:
        $desti_name = 'Others';
  }

  return $desti_name;

}

function starfish_get_icon_color($icon_type){
  $icon_color = '';

  switch ($icon_type) {
    case "Google":
        $icon_color = '#dd4b39';
        break;
    case "Facebook":
        $icon_color = '#3b5998';
        break;
    case "Yelp":
        $icon_color = '#af0606';
        break;
    case "Tripadvisor":
        $icon_color = '#00af87';
        break;
    case "Amazon":
        $icon_color = '#ff9900';
        break;
    case "Audible":
        $icon_color = '#f7991c';
        break;
    case "iTunes":
        $icon_color = '#333333';
        break;
    case "AppleAppStore":
        $icon_color = '#34C5F9';
        break;
    case "GooglePlay":
        $icon_color = '#fbbc05';
        break;
    case "Foursquare":
        $icon_color = '#f94877';
        break;
    case "WordPress":
        $icon_color = '#21759b';
        break;
    case "Etsy":
        $icon_color = '#d5641c';
        break;
    case "YouTube":
        $icon_color = '#ff0000';
        break;
    default:
        $icon_color = '#000';
  }

  return $icon_color;

}


function starfish_add_beacon_help_scout_button(){
  ?>
  <script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});</script>
  <script>window.Beacon('init', '392c4337-1f73-4e52-84e9-d226d7d3a21d')</script>
  <?php
  if ( starfish_fs()->can_use_premium_code() ){
  global $starfish_fs;
  $fs_user = $starfish_fs->get_user();
  $fs_name = $fs_user->get_name();
  $fs_email = $fs_user->email;
  ?>
  <script>
  Beacon("identify", {
    name: "<?php echo esc_attr($fs_name); ?>",
    email: "<?php echo esc_attr($fs_email); ?>"
  });
  Beacon('prefill', {
    subject: 'Starfish Contact From Plugin Admin Page'
  })
  </script>
  <?php }
}

add_action('admin_footer', 'starfish_add_admin_footer_script');
function starfish_add_admin_footer_script() {
  global $pagenow;
  if ( (isset($_GET['post_type']) && (('starfish_review' === $_GET['post_type']) || ('funnel' === $_GET['post_type'])))  || ('post.php' === $pagenow && isset($_GET['post']) && ('funnel' === get_post_type( $_GET['post']) )) ){
      starfish_add_beacon_help_scout_button();
  }

}
