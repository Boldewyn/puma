<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-about" class="help-content">
  <h2><?php printf(__('About %s'), puma())?></h2>
  <p>
<?php
        $Q = $this->db->get('aigaiongeneral');
        if ($Q->num_rows()>0) {
          $R = $Q->row();
            $version = $R->version;
        } else {
            $version = '0.0';
        }
?>
    <?php printf(__('Administrator of this installation: %s'), sprintf('<a href="mailto:&quot;%1$s&quot;%%20&lt;%2$s&gt;?subject=Question%%20re:%%20Puma">%1$s</a>', getConfigurationSetting("CFG_ADMIN"), getConfigurationSetting("CFG_ADMINMAIL")))?><br/>
    <?php printf(__('URL of this installation: %s'), AIGAION_ROOT_URL)?><br/>
    <?php printf(__('PHP version: %s'), phpversion())?><br/>
    <?php printf(__('Database Version: %s'), $version)?><br/>
    <?php printf(__('Based on the %s framework.'), '<a href="http://codeigniter.com/">CodeIgniter</a>')?>
  </p>
</div>
