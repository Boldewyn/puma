<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (is_ajax()):
    $this->load->view('header_clean');
else:
$userlogin = getUserLogin();
header('Content-Type: text/html; charset=UTF-8');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->config->item('current_language'); ?>">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <title><?php echo $title; ?> - <?php _site_title(True) ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/screen.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/print.css" type="text/css" media="print" />
    <script type="text/javascript">
      //<![CDATA[
      var config = {
          base_url: '<?php echo base_url();?>',
          language: '<?php echo $this->config->item('current_language');?>',
          user: {
            <?php if (! is_user()): ?>
              id: ''
            <?php else: ?>
              id: '<?php _js($userlogin->loginName());?>',
              firstname: '<?php _js($userlogin->preferences['firstname']); ?>',
              lastname: '<?php _js($userlogin->preferences['surname']); ?>',
              email: '<?php _js($userlogin->preferences['email']); ?>'
            <?php endif; ?>
          }
      }
      //]]>
    </script>
    <script type="text/javascript" src="<?php echo base_url() ?>static/js/jquery.js"></script>
  </head>
  <body id="<?php if (isset($body_id)) { echo $body_id; } else { echo h($this->uri->segment(1).'_'.$this->uri->segment(2)); } ?>"
     class="<?php printf('c%s m%s', h($this->uri->segment(1)), h($this->uri->segment(2)))?>">
    <div id="container">
      <div id="header">
        <div id="header_widgets">
          <p id="header_backlink">
            <a href="http://www.uni-regensburg.de/">Uni Regensburg</a>
          </p>
          <div id="header_controls">
            <?php echo form_open('search/quicksearch', array("id"=>"quicksearch", "class"=>"header_control")); ?>
              <p>
                <input type="hidden" name="formname" value="simplesearch" />
                <input type="text" class="text" name="q" value="<?php _h($this->input->post('q')); ?>" />
                <input type="submit" class="submit" name="submit_search" value="<?php _e('Search'); ?>" />
              </p>
            </form>
            <p class="language header_control">
              <?php _e('Language:'); ?>
              <?php
                global $AIGAION_SHORTLIST_LANGUAGES; $larr = array();
                foreach ($AIGAION_SHORTLIST_LANGUAGES as $lang):
                  $larr[] = anchor('language/set/'.$lang.'/'.implode('/',$this->uri->segment_array()),
                                   $lang, array('title' => $this->userlanguage->getLanguageName($lang)));
                endforeach;
                echo implode(', ', $larr);
              ?>
            </p>
            <p class="userdata header_control">
              <?php if (! is_user()): ?>
                <?php _e('Guest User:'); ?> <?php _a('login/nds/', __('Login (NDS)')); ?> | <?php _a('login', __('Login (Guest account)')); ?>
                | <?php _a('help', __('Help')); ?>
              <?php else: ?>
                <?php printf(__('Hello, %s %s'), $userlogin->preferences['firstname'], $userlogin->preferences['surname']); ?> |
                <?php if ($userlogin->hasRights('user_edit_self')): ?>
                  <?php _a('user/'.$userlogin->loginName().'/edit', __('Profile')); ?> |
                <?php endif ?>
                <?php if ($userlogin->hasRights('bookmarklist')): ?>
                  <?php _a('bookmarklist', __('Bookmarks')); ?> |
                <?php endif ?>
                <?php _a('help', __('Help')); ?> |
                <?php _a('login/dologout', __('Logout')); ?>
              <?php endif; ?>
            </p>
          </div>
        </div>
        <h1>
          <?php _a('','<span>'.site_title().'</span>');?>
        </h1>
        <h2>
          <?php _a('','<span>Publication Management for the Faculty of Physics</span>');?>
        </h2>
      </div>

      <?php
          $err = getErrorMessage();
          if ($err != '') {
              echo '<p class="error global-error">',$err,'</p>';
              clearErrorMessage();
          }
          $msg = getMessage();
          if ($msg != '') {
              echo '<p class="info global-info">',$msg,'</p>';
              clearMessage();
          }
      ?>

      <?php
          if (! isset($subnav)) { $subnav = array(); }
          $this->load->view('menu', array('subnav'=>$subnav));
      ?>

      <div id="content">
<?php endif;
//__END__