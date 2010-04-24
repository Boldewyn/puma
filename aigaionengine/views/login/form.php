<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
  <?php
    $userlogin=getUserLogin();
    $notice = $userlogin->notice();
    if ($notice != '') {
      echo '<p class="error">',$notice,'</p>';
    }
    $formtitle = sprintf(__('Welcome to %s, please login'), site_title());
    if ($this->latesession->get('FORMREPOST')==True) {
        echo '<p class="error">',
             sprintf(__('You just submitted a form named %s, but it seems that '.
             'you have been logged out. To proceed with submitting the information, '.
             'please log in again, then confirm that you want to re-submit the data.'),
             $this->latesession->get('FORMREPOST_formname')),'</p>';
        $formtitle = __('Login to proceed with form submission');
    }

    echo form_open_multipart('login/dologin/'.implode('/', $segments), array('id' => 'loginForm')); ?>
      <h2><?php echo $formtitle; ?></h2>
      <p><?php printf(__('Guest accounts only, please. If you have an NDS account, %s.'),
                        '<a href="'.base_url().'">'.__('use this form').'</a>')?></p>
      <p>
        <label for="name" class="block"><?php echo __('Name:');?></label>
        <input type="text" class="text" name="loginName" id="name" value="<?php _h($this->input->post('loginName')); ?>" />
        <script type="text/javascript">document.getElementById('name').focus()</script>
      </p>
      <p>
        <label for="password" class="block"><?php echo __('Password:');?></label>
        <input type="password" class="text" name="loginPass" id="password" value="<?php _h($this->input->post('loginPass')); ?>" />
      </p>
      <p class="empty_block">
        <input type="checkbox" name="remember" id="remember" value="1" checked="checked" />
        <label for="remember"><?php echo __('Remember me');?></label>
      </p>
      <p class="empty_block">
        <input type="submit" class="submit" name="submitlogin" value="<?php _e('Login');?>" />
      </p>
      <p class="smallprint">
        <?php echo sprintf(__('If you have no NDS account and want a guest access, please '.
          'write an e-mail to %s.'),
          '<a href="mailto:&quot;'.h(getConfigurationSetting('CFG_ADMIN')).'&quot;%20&lt;'.
          getConfigurationSetting('CFG_ADMINMAIL').'&gt;?subject=Registration%20request%20for%20Puma.Phi">'
          .h(getConfigurationSetting('CFG_ADMIN')).'</a>');?>
      </p>
      <p>
        <a href="<?php echo base_url();?>"><?php _e("Back to the front page.");?></a>
      </p>
    </form>
