<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<body onload="document.getElementById('name').focus()">
  <div id="login">
  <?php
    $userlogin=getUserLogin();
    $notice = $userlogin->notice();
    if ($notice!="") {
      echo "<p class='error'>$notice</p>";
    }
    $err = getErrorMessage();
    if ($err != "") {
        echo "<p class='error'>$err</p>";
        clearErrorMessage();
    }
    $formtitle = sprintf(__('Welcome to %s, please login'), puma());
    if ($this->latesession->get('FORMREPOST')==True) {
        echo "<p class='error'>".sprintf(__('You just submitted a form named %s, but it seems that you have been logged out. To proceed with submitting the information, please log in again, then confirm that you want to re-submit the data.'), $this->latesession->get('FORMREPOST_formname'))."</p>";
        $formtitle = __('Login to proceed with form submission');
    }

    echo form_open_multipart('login/dologin/'.implode('/',$segments), array('id' => 'loginForm')); ?>
      <h1><?php echo $formtitle; ?></h1>
      <p>
        <label for="name"><?php echo __('Name:');?></label>
        <input type="text" name="loginName" id="name" value="<?php _h($this->input->post('loginName')); ?>" />
      </p>
      <p>
        <label for="password"><?php echo __('Password:');?></label>
        <input type="text" name="loginPass" id="password" value="<?php _h($this->input->post('loginPass')); ?>" />
      </p>
      <p>
        <input type="checkbox" name="remember" id="remember" value="1" />
        <label for="remember"><?php echo __('Remember me');?></label>
      </p>
      <p>
        <input type="submit" name="submitlogin" value="<?php _e('Login');?>" />
      </p>
      <p class="smallprint">
        <?php echo sprintf(__('If you want a password, please mail to %s.'), "<a href='mailto:".h(getConfigurationSetting("CFG_ADMIN"))." &lt;".getConfigurationSetting("CFG_ADMINMAIL")."?gt;?subject=Registration request for Puma.Phi'>".h(getConfigurationSetting("CFG_ADMIN"))."</a>");?>
        <a href="<?php echo base_url();?>"><?php _e("Back to the front page.");?></a>
      </p>
    </form>
  </div>
</body>
