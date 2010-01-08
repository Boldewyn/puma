<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin = getUserLogin();
header("Content-Type: text/html; charset=UTF-8");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->config->item('current_language'); ?>">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <title><?php echo $title; ?> - Puma.&Phi;</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/screen.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/print.css" type="text/css" media="print" />
    <script type="text/javascript">
      //<![CDATA[
      var config = {
          base_url: '<?php echo base_url();?>',
          language: '<?php echo $this->config->item('current_language');?>',
          user: {
            <?php if ($userlogin->isAnonymous()): ?>
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
  </head>
  <body>
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
                <input type="text" name="q" value="<?php _h($this->input->post('q')); ?>" />
                <input type="submit" name="submit_search" value="<?php _e('Search'); ?>" />
              </p>
            </form>
            <p class="header_control">
              <?php if ($userlogin->isAnonymous()): ?>
                <?php _e('Guest User:'); ?> <?php echo anchor('login/nds/', __('Login (NDS)')); ?> | <?php echo anchor('login', __('Login (Guest account)')); ?>
              <?php else: ?>
                <?php printf(__('Hello, %s %s'), $userlogin->preferences['firstname'], $userlogin->preferences['surname']); ?> |
                <?php if ($userlogin->hasRights('user_edit_self')): ?>
                  <?php echo anchor('users/edit/'.$userlogin->userId(), __('My Profile')); ?> |
                <?php endif;
                  if ($userlogin->hasRights('topic_subscription')): ?>
                  <?php echo anchor('users/topicreview/', __('Topic Subscribe')); ?> |
                <?php endif; ?>
                <?php echo anchor('login/dologout', __('Logout')); ?>
              <?php endif; ?>
            </p>
            <p class="language header_control">
              <?php _e('Language:'); ?>
              <?php
                global $AIGAION_SHORTLIST_LANGUAGES; $larr = array();
                foreach ($AIGAION_SHORTLIST_LANGUAGES as $lang):
                  $larr[] = anchor('language/set/'.$lang.'/'.implode('/',$this->uri->segment_array()),$lang);//$this->userlanguage->getLanguageName($lang));
                endforeach;
                echo implode(", ", $larr);
              ?>
            </p>
          </div>
        </div>
        <h1>
          <?php echo anchor('','<span>Puma.&Phi;</span>');?>
        </h1>
        <h2>
          <?php echo anchor('','<span>Publication Management for the Faculty of Physics</span>');?>
        </h2>
      </div>

      <?php
        //load menu
        //view parameter to be passed to menu: a prefix for the sort options. See views/menu.php for more info
        if (!isset($sortPrefix))
          $sortPrefix = '';
        //view parameter to be passed to menu: a command relevant for the menu export option. See views/menu.php for more info
        if (!isset($exportCommand))
          $exportCommand = '';
        if (!isset($exportName))
          $exportName = __('Aigaion export list');
        $this->load->view('menu', array('sortPrefix'=>$sortPrefix,'exportCommand'=>$exportCommand,'exportName'=>$exportName));
      ?>

      <div id="content">
        <p>Hier kommt <a href="#">ein Test</a>.</p>
        <?php
            $err = getErrorMessage();
            if ($err != "") {
                echo "<p class='error'>$err</p>";
                clearErrorMessage();
            }
            $msg = getMessage();
            if ($msg != "") {
                echo "<p class='info'>$msg</p>";
                clearMessage();
            }
        ?>
