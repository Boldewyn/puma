<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); $userlogin = getUserLogin(); ?>

<div class="user_edit">

  <h2><?php printf(__('Edit my account: %s'), $user->login)?></h2>

  <?php echo validation_errors(); ?>

  <?php echo form_open("user/{$user->login}/edit", array("autocomplete"=>"off")); ?>
    <?php /* <fieldset class="extended_label">
      <legend><?php _e('Password')?></legend>
      <p>
        <label for="user_edit_password"><?php _e('Password (leave blank to keep)')?></label>
        <input type="password" class="text password" name="password" id="user_edit_password" />
      </p>
      <p>
        <label for="user_edit_password_check"><?php _e('Re-type new password')?></label>
        <input type="password" class="text password" name="password_check" id="user_edit_password_check" />
      </p>
    </fieldset> */ ?>

    <fieldset class="half">
      <legend><?php _e('Account settings')?></legend>
      <p>
        <label for="user_edit_initials"><?php _e('Initials')?></label>
        <input type="text" class="text" name="initials" id="user_edit_initials"
              size="5" value="<?php _h($user->initials)?>" />
      </p>
      <p>
        <label for="user_edit_firstname"><?php _e('First name')?></label>
        <input type="text" class="text" name="firstname" id="user_edit_firstname"
              size="5" value="<?php _h($user->firstname)?>" />
      </p>
      <p>
        <label for="user_edit_betweenname"><?php _e('Middle name')?></label>
        <input type="text" class="text" name="betweenname" id="user_edit_betweenname"
              size="5" value="<?php _h($user->betweenname)?>" />
      </p>
      <p>
        <label for="user_edit_surname"><?php _e('Surname')?></label>
        <input type="text" class="text" name="surname" id="user_edit_surname"
              size="5" value="<?php _h($user->surname)?>" />
      </p>
      <p>
        <label for="user_edit_abbreviation"><?php _e('Abbreviation')?></label>
        <input type="text" class="text" name="abbreviation" id="user_edit_abbreviation"
              size="5" value="<?php _h($user->abbreviation)?>" />
        <span class="note"><?php _e("This is not your login name, but the name others will see in the &lsquo;Who created this&rsquo; fields.")?></span>
      </p>
      <p class="extended_input">
        <label for="user_edit_email"><?php _e('Email address')?></label>
        <input type="text" class="text" name="email" id="user_edit_email"
              size="5" value="<?php _h($user->email)?>" />
      </p>
    </fieldset>

    <fieldset class="extended_label half">
      <legend><?php _e('Display preferences')?></legend>
      <p>
        <label for="user_edit_language"><?php _e('Language')?></label>
        <?php
          $lang_array = array('default' => sprintf(__('default (%s)'), getConfigurationSetting('DEFAULTPREF_LANGUAGE')));
          global $AIGAION_SUPPORTED_LANGUAGES;
          foreach ($AIGAION_SUPPORTED_LANGUAGES as $lang) {
            $lang_array[$lang] = $this->userlanguage->getLanguageName($lang);
          }
          echo form_dropdown('language', $lang_array,
                             $user->preferences["language"],
                             ' id="user_edit_language"')?>
      </p>
      <p>
        <label for="user_edit_summarystyle"><?php _e('Publication summary style')?></label>
        <?php echo form_dropdown('summarystyle',
                            array('default' => sprintf(__('default (%s)'), getConfigurationSetting('DEFAULTPREF_SUMMARYSTYLE')),
                                  'author' => __('author first'), 'title' => __('title first')),
                            $user->preferences["summarystyle"],
                            ' id="user_edit_summarystyle"')?>
      </p>
      <p>
        <label for="user_edit_authordisplaystyle"><?php _e('Author display style')?></label>
        <?php echo form_dropdown('authordisplaystyle',
                            array('default' => sprintf(__('default (%s)'), getConfigurationSetting('DEFAULTPREF_AUTHORDISPLAYSTYLE')),
                                  'fvl' => __('First [von] Last'), 'vlf' => __('[von] Last, First'), 'vl' => __('[von] Last')),
                            $user->preferences["authordisplaystyle"],
                            ' id="user_edit_authordisplaystyle"')?>
      </p>
      <p>
        <label for="user_edit_liststyle"><?php _e('Number of publications per page')?></label>
        <?php echo form_dropdown('liststyle',
                            array('0'=>__("All"), "10"=>"10", '15'=>"15", '20'=>"20", '25'=>"25", '50'=>"50", '100'=>"100"),
                            $user->preferences["liststyle"],
                            ' id="user_edit_liststyle"')?>
      </p>
      <p>
        <label for="user_edit_similar_author_test"><?php _e('&lsquo;Similar author&rsquo; check')?></label>
        <?php echo form_dropdown('similar_author_test',
                            array('default' => __('Site default'), 'il' => __("Last names, then initials"), "c" => __("Full name")),
                            $user->preferences["similar_author_test"],
                            ' id="user_edit_similar_author_test"')?>
        <span class="note"><?php _e('Select the method for checking whether two author names are counted as &lsquo;similar&rsquo;.');?></span>
      </p>
      <p>
        <label for="user_edit_newwindowforatt"><?php _e('Open attachments in new browser window')?></label>
        <input type="checkbox" class="checkbox" name="newwindowforatt" id="user_edit_newwindowforatt"
              value="TRUE" <?php if ($user->preferences['newwindowforatt']=="TRUE") { echo 'checked="checked"'; }?> />
      </p>
      <p>
        <label for="user_edit_exportinbrowser"><?php _e('Open export data in browser')?></label>
        <input type="checkbox" class="checkbox" name="exportinbrowser" id="user_edit_exportinbrowser"
              value="TRUE" <?php if ($user->preferences['exportinbrowser']=="TRUE") { echo 'checked="checked"'; }?> />
        <span class="note"><?php _e('Check this box to force the system to show export data such as BibTeX or RIS '.
                                    'directly in a browser window instead of downloading it as a file.')?></span>
      </p>
      <p>
        <label for="user_edit_utf8bibtex"><?php _e('Export BibTeX as UTF8')?></label>
        <input type="checkbox" class="checkbox" name="utf8bibtex" id="user_edit_utf8bibtex"
              value="TRUE" <?php if ($user->preferences['utf8bibtex']=="TRUE") { echo 'checked="checked"'; }?> />
        <span class="note"><?php sprintf(__('Check this box if you want all BibTeX output to be in UTF8, i.e. '.
                                            'when you do <strong>not</strong> want Aigaion to convert special '.
                                            'characters to BibTeX codes such as %s.'), "{\\'e}")?></span>
      </p>
    </fieldset>
    <p>
      <input type="submit" class="submit" value="<?php _e("Store new settings")?>" />
      <?php _a("user/{$user->login}", __("Back to the user&rsquo;s overview"), array('class'=>'pseudobutton'))?>
    </p>
  </form>

</div>