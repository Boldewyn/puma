<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
$userlogin = getUserLogin();
?>
<div class='author'>
  <?php if ($userlogin->hasRights('publication_edit')): ?>
    <p class='optionbox'>
      <?php _a('authors/delete/'.$author->author_id, '['.__('delete').']',
        array('title' => __('Delete this author'))) ?>
      <?php _a('authors/edit/'.$author->author_id, '['.__('edit').']',
        array('title' => __('Edit this author'))) ?>
      <?php  if ($userlogin->hasRights('bookmarklist')): ?>
        <?php _a('bookmarklist/addauthor/'.$author->author_id, '['.__('BookmarkAll').']',
          array('title' => __('Bookmark all publications of this author'))) ?>
        <?php _a('bookmarklist/removeauthor/'.$author->author_id, '['.__('UnBookmarkAll').']',
          array('title' => __('Unbookmark all publications of this author'))) ?>
      <?php endif; ?>
    </p>
  <?php endif; ?>
  
  <h2><?php _h($author->getName()) ?></h2>
  <div class='author_details'>
    <?php foreach (array('firstname'=>__('First name(s)'), 'von'=>__('von-part'), 
                  'surname'=>__('Last name(s)'), 'jr'=>__('jr-part'), 
                  'email'=>__('Email'), 'institute'=>__('Institute')) as $field=>$display):
        if (trim($author->$field) != ''): ?>
          <p>
            <label class="block"><?php _h($display) ?>:</label>
            <span><?php _h($author->$field) ?></span>
          </p>
        <?php endif;
    endforeach;
      if ($author->url != '') {
        $this->load->helper('utf8');
        $urlname = $url = prep_url($author->url);
        if (utf8_strlen($urlname)>21) {
            $urlname = utf8_substr($urlname,0,30).'&hellip;';
        } ?>
        <p>
          <label class="block"><?php _e('URL:')?></label>
          <a title="<?php echo $url ?>" href="<?php echo $url ?>"><?php echo $urlname ?></a>
        </p>
      <?php }
    ?>
  </div>
  <?php if ($userlogin->hasRights('publication_edit')):
      $similar = $author->getSimilarAuthors();
      if (count($similar) > 0): ?>
          <p class="info"><?php _e('Found authors with very similar names. You '.
            'can choose to merge the following authors with this author by clicking '.
            'on the merge link.')?></p>
          <ul>
            <?php foreach ($similar as $simauth): ?>
              <li>
                <?php _a('authors/show/'.$simauth->author_id, $simauth->getName(), array('title' => __('Click to show details'))) ?>
                (<?php _a('authors/merge/'.$author->author_id.'/'.$simauth->author_id, __('merge'), array('title' => __('Click to merge'))) ?>)
              </li>
            <?php endforeach; ?>
          </ul>
      <?php endif;
  endif; ?>
</div>
<?php $keywords = $author->getKeywords();
if (sizeof($keywords) > 0): ?>
  <div class="tagcloud">
    <h3><?php _e('Keywords:')?></h3>
    <?php $this->load->view('keywords/list_items', array(
      'keywordList' => $keywords,
      'isCloud' => true)); ?>
  </div>
<?php endif; ?>
