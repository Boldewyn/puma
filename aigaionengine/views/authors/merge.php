<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class='author'>
  <h2><?php _e('Merge authors') ?></h2>
  <p><?php _e('Merges the source author with the target author. The source '.
    'author will be deleted, all publications will be transferred to the target '.
    'author.') ?></p>
  <form method="post" action="<?php echo site_url('authors/mergecommit') ?>">
    <table class='author_edit_form' width='100%'>
      <tr>
        <th colspan="2"><h4><?php _e('Target author') ?></h4></th>
        <td></td>
        <th colspan="2"><h4><?php _e('Source author') ?></h4></th>
      </tr>
      <?php foreach (array('firstname'=>__('First name(s)'), 'von'=>__('von-part'),
                     'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'),
                     'email'=>__('Email'), 'institute'=>__('Institute')) as $field=>$display): ?>
        <tr>
          <th><?php echo $display; ?>:</th>
          <td><input type="text" class="text" name="<?php echo $field ?>"
                     id="<?php echo $field ?>" value="<?php _h($author->$field) ?>" /></td>
          <td><button type="button" onclick="$('#<?php echo $field
                     ?>').val($('#sim<?php echo $field ?>').val());">&lArr;</button></td>
          <th><?php echo $display; ?>:</th>
          <td><input type="text" class="text" name="sim<?php echo $field ?>" 
                     id="sim<?php echo $field ?>" value="<?php _h($simauthor->$field) ?>" /></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <p>
      <input type="hidden" name="author_id" value="<?php echo $author->author_id?>" />
      <input type="hidden" name="simauthor_id" value="<?php echo $simauthor->author_id?>" />
      <input type="hidden" name="formname" value="author" />
      <input type="submit" class="submit" value="<?php _e('Merge')?>" />
      <?php _a('authors/show/'.$author->author_id, __('Cancel'), 'class="pseudobutton"'); ?>
    </p>
  </form>
</div>