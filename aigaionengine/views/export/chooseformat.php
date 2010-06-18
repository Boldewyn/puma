<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
this view shows a form that asks you the format in which you want to export the data
It needs several view parameters:

header              Default: "Export all publications"
exportCommand       Default: "export/all/"; will be suffixed with type. May also be, e.g., "export/topic/12/"
*/
$userlogin  = getUserLogin();

if (!isset($header))$header=__('Export all publications');
if (!isset($exportCommand))$exportCommand="export/all/";
?>
<h2><?php _h($header) ?></h2>
<p>
  <?php _e('Please select the format in which you want to export the publications:')?>
</p>
<?php echo form_open($exportCommand.'bibtex') ?>
  <p><input type="submit" name="BibTeX" title="<?php _e('Export to BibTeX')?>" value="<?php _e('BibTeX')?>" /></p>
</form>
<?php echo form_open($exportCommand.'ris') ?>
  <p><input type="submit" name="RIS" title="<?php _e('Export to RIS')?>" value="<?php _e('RIS')?>" /></p>
</form>
<?php if ($userlogin->hasRights('export_email')):
  echo form_open($exportCommand.'email') ?>
  <p><input type="submit" name="E-mail" title="<?php _e('Export by e-Mail')?>" value="<?php _e('E-mail')?>" /></p>
</form>
<?php endif; ?>
<hr/>

<?php $this->load->helper('osbib');
echo form_open($exportCommand.'formatted'); ?>
  <p><?php _e('Format:');
    echo form_dropdown('format',array('html'=>'HTML','rtf'=>'RTF','plain'=>'TXT'),'html');?>
    <?php _e('Style:'); 
    $style_options = array();
    $styles = LOADSTYLE::loadDir(APPPATH."include/OSBib/styles/bibliography");
    foreach ($styles as $style=>$longname) {
        $style_options[$style] = $style;
    }
    echo form_dropdown('style',$style_options);
    ?>
    <input type="hidden" name="sort" value="nothing" />
    <input type="submit" name="Formatted" title="<?php _e('Export formatted entries')?>" value="<?php _e('Export')?>" />
  </p>
</form>

