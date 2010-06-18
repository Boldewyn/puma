<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="regular-text">
    <h2><?php _e('All available languages'); ?></h2>
    <p><?php _e('Choose your preferred display language:') ?></p>
    <ul>
      <?php 
      global $AIGAION_SUPPORTED_LANGUAGES;
      foreach ($AIGAION_SUPPORTED_LANGUAGES as $lang) :?>
        <li><?php _a('language/set/'.$lang, $this->userlanguage->getLanguageName($lang)) ?></li>
      <?php endforeach ?>
    </ul>
</div>

