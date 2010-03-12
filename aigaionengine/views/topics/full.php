<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id='singletopic-content-holder'>
<!-- Topic: HEADER AND DESCRIPTION -->
<?php

    $userlogin  = getUserLogin();

    if ($topic->name=='') {
        $name = sprintf(__('Topic #%s'), $topic->topic_id);
    } else {
        $name = sprintf(__('Topic: %s'), h($topic->name));
    }
    if ($topic->description != null) {
        $description = $topic->description;
    } else {
        $description = __('&ndash; No description &ndash;');
    }

$parent = $topic->getParent();
?>
<p class="parent-topic"><?php _a('topics/single/'.$parent->topic_id,$parent->name)?></p>
<p class="optionbox">
  <?php 
  if (($userlogin->hasRights('topic_edit'))
       && $this->accesslevels_lib->canEditObject($topic)) {
        _a('topics/edit/'.$topic->topic_id, '['.__('edit').'] ');
        _a('topics/delete/'.$topic->topic_id, '['.__('delete').'] ');
  }
  if (($userlogin->hasRights('topic_edit')) && $this->accesslevels_lib->canEditObject($topic)) {
      _a('accesslevels/edit/topic/'.$topic->topic_id,
         'r:'.icon('rights_'.$topic->derived_read_access_level).' e:'.icon('rights_'.$topic->derived_edit_access_level),
         array('title'=>__('click to modify access levels')));
  }
  ?>
</p>

<h2><?php echo $name ?></h2>

<?php
    if ($topic->url != '') {
        $this->load->helper('utf8');
        $urlname = prep_url($topic->url);
        if (utf8_strlen($urlname)>21) {
            $urlname = utf8_substr($urlname,0,30)."...";
        }
        echo __('URL').": <a  title='".prep_url($topic->url)."' href='".prep_url($topic->url)."' class='open_extern'>".$urlname."</a><br/><br/>\n";
    }
    if ($description)
        echo "<p>".$description."</p>\n";
        
?>
<div id='topictree-holder'>
  <h3><?php echo __('Subtopics:')?></h3>
  <ul class='topictree-list'>
  <?php
  $this->load->vars(array('subviews'  => array('topics/simpletreerow'=>array())));
  echo $this->load->view('topics/tree',
                          array('topics'   => $topic,
                                'showroot'  => False,
                                'depth'     => 2),
                          true);
  ?></ul>
</div>
<?php
$keywords = $topic->getKeywords();
if (sizeof($keywords) > 0) {
  $keywordContent['keywordList'] = $keywords;
  $keywordContent['isCloud'] = true;
  ?><div id='tagcloud'>
    <h3><?php echo __('Keywords:')?></h3>
    <?php echo $this->load->view('keywords/list_items', $keywordContent, true) ?>
  </div><?php
}

$topicstatBlock = "";
//Get statistics for this topic
$authorCount          = $this->topic_db->getAuthorCountForTopic($topic->topic_id);
$topicCount           = count($topic->getChildren());
$publicationCount     = $this->topic_db->getPublicationCountForTopic($topic->topic_id);
$publicationReadCount = $this->topic_db->getReadPublicationCountForTopic($topic->topic_id);

if ($publicationCount == 1) 
	$topicstatBlock .= "
<ul>
<li class='nobr'>{$publicationCount} ".__('publication')." ({$publicationReadCount} read)</li>";
else 
	$topicstatBlock .= "
<ul>
<li class='nobr'>{$publicationCount} ".__('publications')." ({$publicationReadCount} read)</li>";
if ($authorCount ==1)
$topicstatBlock .="
<li class='nobr'>{$authorCount} ".__('author')." [".anchor('authors/fortopic/'.$topic->topic_id,__('view'), 'title="'.__('view author for topic').'"')."]</li>";
else 
$topicstatBlock .="<li class='nobr'>{$authorCount} ".__('authors')." [".anchor('authors/fortopic/'.$topic->topic_id,__('view'), 'title="'.__('view authors for topic').'"')."]</li>";
if ($topicCount>0)
{
  if ($topicCount==1)
  $topicstatBlock .=
  "
  <li class='nobr'>{$topicCount} ".__('Subtopic')." ";
  else
  $topicstatBlock .= "<li class='nobr'>{$topicCount} ".__('Subtopics')." ";
} else
{
  $topicstatBlock .= "<li class='nobr'>".__('No subtopics')." ";
}
if ($userlogin->hasRights('topic_edit')) {
  $topicstatBlock .= "[".anchor('topics/add/'.$topic->topic_id,__('create new subtopic'), 'title="'.__('create new subtopic').'"')."]";
}
$topicstatBlock .= "</li>\n";
  if ($userlogin->hasRights('bookmarklist')) {
    $topicstatBlock .= "<li class='nobr'>[".anchor('bookmarklist/addtopic/'.$topic->topic_id,__('BookmarkAll'))."]</li>\n";
    $topicstatBlock .= "<li class='nobr'>[".anchor('bookmarklist/removetopic/'.$topic->topic_id,__('UnBookmarkAll'))."]</li>\n";
  }
  $topicstatBlock .= "</ul>\n";
  
if ($topicstatBlock != '') 
{
	echo "
	<div class='topicstats'>
	".$topicstatBlock."
  </div>";
}
?>

</div> 
