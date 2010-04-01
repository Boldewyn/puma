<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
this view shows a form that asks you the format in which you want to export the data
It needs several view parameters:

header              Default: "Export all publications"
exportCommand       Default: "export/all/"; will be suffixed with type. May also be, e.g., "export/topic/12/"
*/

if (!isset($header))$header=__('Export all publications');
if (!isset($exportCommand))$exportCommand='export/all/';
?>
<h2><?php _h($header) ?></h2>
<p>
  <?php _e('Please select the format(s) in which you want to export the publications and enter the email address(es) you want to send it to:') ?>
</p>
<p>
  <?php _e('Style:') ?>
</p>
<?php
$this->load->helper('osbib');
echo form_open($controller);
$style_options = array();
$styles = LOADSTYLE::loadDir(APPPATH."include/OSBib/styles/bibliography");
foreach ($styles as $style=>$longname) {
    $style_options[$style] = $style;
} ?>
  <p>
    <?php if(MAXIMUM_ATTACHMENT_SIZE > $attachmentsize): ?>
    <input type="radio" name="format" id="export_chooseformat_pdf" value="pdf" /> <label for="export_chooseformat_pdf">PDF</label>
      &nbsp;&nbsp;&nbsp; <?php printf(__('Attachment size: %s KB'), $attachmentsize);
    else: ?>
    <input type="radio" name="format" id="export_chooseformat_pdf" value="pdf" disabled="disabled" /> <label for="export_chooseformat_pdf">PDF</label>
      <?php printf(__('Maximum attachment size: %s KB'), MAXIMUM_ATTACHMENT_SIZE).' '.printf(__('Current attachment size: %s KB'), $attachmentsize);
    endif;?><br/>
    <input type="radio" name="format" id="export_chooseformat_bibtex" value="bibtex" /> <label for="export_chooseformat_bibtex">BibTeX</label><br/>
    <input type="radio" name="format" id="export_chooseformat_ris" value="ris" /> <label for="export_chooseformat_ris">RIS</label><br/>
    <input type="radio" name="format" id="export_chooseformat_formatted" value="html" /> <label for="export_chooseformat_formatted"><?php _e('Formatted')?></label>
     &nbsp;&nbsp;&nbsp; <?php echo form_dropdown('style',$style_options) ?>
  </p>
  <p>
    <input type="text" class="<?php if(! isset($recipientaddress) || $recipientaddress == -1) echo 'labeled'?> text"
        name="email_address" value="<?php (isset($recipientaddress) && $recipientaddress != -1)?
        _h($recipientaddress) : _e('Input email addresses here, separated by comma'); ?>" />
  </p>
  <p>
    <input type="hidden" name="sort" value="nothing" />
    <input type="submit" class="submit" name="Formatted" title="<?php _e('Export formatted entries')?>" value="<?php _e('Export')?>" />
  </p>
</form>
