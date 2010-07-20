<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
views/topics/maintreerow

Displays a row if information about one topic in the 'normal' main tree. 
Some configuration can be passed through the view parameters.

Parameters:
    $topic: the topic for which the row is to be displayed
    $useCollapseCallback: if True, collapse and expand actions will be passed to the topics/collapse callback
*/
  if (!isset($useCollapseCallback) || $useCollapseCallback == False) {
    $callhome = 'false';
  } else {
    $callhome = 'true';
  }
  $isSubscr = $topic->flags['userIsSubscribed'];
  $hide1 = '';
  $hide2 = 'display: none';
  if (array_key_exists('flagCollapsed',$topic->configuration) && ($topic->flags['userIsCollapsed']==True)) {
      $hide1 = $hide2;
      $hide2 = '';
  }
  $publicationCount     = $this->topic_db->getPublicationCountForTopic($topic->topic_id);
  $publicationReadCount = $this->topic_db->getReadPublicationCountForTopic($topic->topic_id);

  if (sizeof($topic->getChildren())>0) {
    _icon('tree_min', Null, array('alt'=>'[-]', 'id'=>'min_topic_'.$topic->topic_id,
          'class'=>'topic-toggler topic-toggler-min', 'style' => $hide1,
          'onclick' => 'Puma.topic.collapse('.$topic->topic_id.', '.$callhome.')'));
    _icon('tree_plus', Null, array('alt'=>'[+]', 'id'=>'plus_topic_'.$topic->topic_id,
          'class'=>'topic-toggler topic-toggler-plus', 'style' => $hide2,
          'onclick' => 'Puma.topic.expand('.$topic->topic_id.', '.$callhome.')'));
  } else {
    echo '<span class="tree-blank">&nbsp;</span>';
  }
  _a('topics/single/'.$topic->topic_id, h($topic->name)); ?>
  <span class="topic-leaf-info">
    <em title="<?php printf(__('read: %s of %s publications'), $publicationReadCount, $publicationCount)?>">
    <?php echo $publicationReadCount ?>/<?php echo $publicationCount ?></em>
    <?php if (! isset($subscribed) || $subscribed == False) {
      if ($isSubscr):
        _a('users/unsubscribe/'.$topic->topic_id, '<span>'.__('Unsubscribe').'</span>',
           array('class' => 'subscribe subscribed'));
      else:
        _a('users/subscribe/'.$topic->topic_id, '<span>'.__('Subscribe').'</span>',
           array('class' => 'subscribe'));
      endif;
    } ?>
  </span>
