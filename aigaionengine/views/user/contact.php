<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); if (!isset($embed)) { $embed = false; } ?>

<?php if ($embed): ?>
  <div class="embed user_contact_embed">
    <p><?php printf(__('Contact %s %s'), h($user->firstname), h($user->surname))?></p>
<?php else: ?>
  <h2><?php printf(__('Contact %s %s'), h($user->firstname), h($user->surname))?></h2>
<?php endif; ?>

<?php if ($success): ?>

  <?php if ($success_send): ?>
    <p class="info success"><?php _e('The e-Mail was successfully sent.')?></p>
  <?php else: ?>
    <p class="error"><?php _e('Ooops.')?></p>
    <p><?php _a('user/'.$user->login, __('&laquo; Back to the user’s overview'))?></p>
  <?php endif; ?>

<?php else: ?>

  <?php echo validation_errors(); ?>

  <?php echo form_open("user/{$user->login}/contact"); ?>
    <p>
      <label class="block" for="user_contact_subject"><?php _e('Subject:')?></label>
      <input type="text" class="text" name="subject" id="user_contact_subject"
             value="<?php echo set_value('subject')?>" />
    </p>
    <p>
      <label class="block" for="user_contact_message"><?php _e('Message:')?></label>
      <textarea name="message" id="user_contact_message"
                rows="5" cols="30" class="<?php if (form_error('message')) { echo 'error'; }?>"><?php echo set_value('message')?></textarea>
    </p>
    <p>
      <input type="submit" class="wide_button" value="<?php _e('Submit')?>" />
      <?php if (! $embed):?>
        <?php _a("user/{$user->login}", __('Back to the user’s overview'), array('class'=>'pseudobutton'))?>
      <?php endif ?>
    </p>
  </form>

<?php endif; ?>

<?php if ($embed): ?>
  </div>
<?php endif; ?>


