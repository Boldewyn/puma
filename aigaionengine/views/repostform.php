<?php
/** See formrepost_helper, login filter and login controller... */

$userlogin = getUserLogin();
$this->load->helper('form');
echo form_open($this->latesession->get('FORMREPOST_uri')); ?>
  <p>
    <?php printf(__('The system detected that you were logged out while submitting '.
      'a form named &ldquo;%s&rdquo;. The data in that form has <strong>not</strong> '.
      'yet been submitted successfully to the database. Press the button below to '.
      're-submit the form data.'), $this->latesession->get('FORMREPOST_formname')) ?>
  </p>
  <?php if ($userlogin->isAnonymous()): ?>
  <p class="error">
    <strong><?php _e('NOTE:') ?></strong> <?php _e('You are now logged in as a guest '.
      'user. If you submitted the form from a registered account, you should first '.
      'log in with your registered account before resubmitting the form, because from '.
      'this guest account you might then not have enough rights to submit the form.') ?>
  </p>
  <?php endif; ?>
  <p>
    <input type="submit" name="repost_form" value="<?php _e('Repost')?>" />
    <?php foreach($this->latesession->get('FORMREPOST_post') as $field=>$val): ?>
      <input type="hidden" name="<?php _h($field)?>" value="<?php _h($val)?>" />
    <?php endforeach ?>
    <input type="hidden" name="form_reposted" value="form_reposted" />
    <?php /* Ui, ui. This might perhaps not work. In the original code, the cancel button was an empty request w/o hidden fields to base_url() */ ?>
    <a href="<?php echo base_url()?>" class="pseudobutton"><?php _e('Cancel')?></a>
  </p>
</form>

