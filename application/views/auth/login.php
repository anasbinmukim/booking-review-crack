<!-- BEGIN LOGIN -->
<div class="content" style="margin-top:0">
  <!-- BEGIN LOGIN FORM -->
  <form class="login-form" action="" method="post">
      <h3 class="form-title">Sign In</h3>

      <?php if($this->session->flashdata('success')){?>
          <div class="alert alert-success">
             <button class="close" data-close="alert"></button>
             <span><?php echo $this->session->flashdata('success');?></span>
          </div>
      <?php }?>

      <?php if($error != '') {?>
          <div class="alert alert-danger">
              <button class="close" data-close="alert"></button>
             <span><?php echo $error;?> </span>
          </div>
      <?php }?>

      <div class="alert alert-danger display-hide">
          <button class="close" data-close="alert"></button>
          <span> Enter any email and password. </span>
      </div>

      <div class="form-group">
          <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
          <label class="control-label visible-ie8 visible-ie9">Email</label>
          <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="username" value="<?php if (get_cookie('seat_username')){ echo get_cookie('seat_username');}?>" /> </div>
      <div class="form-group">
          <label class="control-label visible-ie8 visible-ie9">Password</label>
          <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
      <div class="form-actions">
          <button type="submit" class="btn green uppercase">Login</button>
          <label class="rememberme check mt-checkbox mt-checkbox-outline">
              <input type="checkbox" name="remember" value="1" <?php if (get_cookie('seat_username')) { ?>checked="checked"<?php } ?> />Remember
              <span></span>
            </label>
          <a href="<?php echo site_url('forgot-password');?>"  class="forget-password">Forgot Password?</a>
      </div>
      <div class="create-account">
          <p>
              <a href="<?php echo site_url('register');?>" class="uppercase">Register/Sign up</a>
          </p>
      </div>
  </form>
  <!-- END LOGIN FORM -->
</div>
