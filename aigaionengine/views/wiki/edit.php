<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (isset($discussion) && $discussion) { $d = '_Discussion'; } else { $discussion = False; $d = ''; }
?>

<h2><?php if ($discussion) {
              printf(__('Edit Discussion &ldquo;%s&rdquo;'), h($item));
          } else {
              printf(__('Edit &ldquo;%s&rdquo;'), h($item));
          }?></h2>

<?php echo validation_errors(); ?>

<?php if($preview): ?>
  <div class="wiki_preview">
    <h3><?php _e('Preview:')?></h3>
    <div class="wiki_page"><?php echo $preview?></div>
  </div>
<?php endif ?>

<form method="post" action="<?php _url('wiki/Edit'.$d.':'.h($item)) ?>" class="extralarge_input wiki_edit">
  <p>
    <textarea name="content" id="wiki_edit_content" class="richtext" rows="10" cols="30"><?php _h($original_content); ?></textarea>
    <script type="text/javascript" src="<?php echo base_url()?>static/js/tiny_mce/tiny_mce.js"></script>
  </p>
  <p style="text-align:right;">
    <button type="button" onclick="Puma.toggleEditor('wiki_edit_content')"><?php _e('Show/hide rich text editor')?></button>
  </p>
  <p>
    <label for="wiki_edit_description"><?php _e('A short description of this edit:')?></label>
    <input type="text" class="text" name="description" id="wiki_edit_description" value="<?php _h($description) ?>" />
  </p>
  <p>
    <input type="submit" class="wide_button" value="<?php _e('Save') ?>" />
    <input type="submit" class="wide_button" name="preview" value="<?php _e('Preview') ?>" />
  </p>
</form>
