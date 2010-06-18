<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (count($topic->getChildren())>0):
  $default = '';
  ?>
  <?php _icon('tree_min', Null, array('alt'=>'[-]', 'id'=>'min_'.$topic->topic_id, 'class'=>'topic-toggler topic-toggler-min')) ?>
  <script type="text/javascript"><?php echo $default ?></script>
<?php else: ?>
  <span class="topic-placeholder">&nbsp;</span>
<?php endif;
    
$class = 'topic-subscription ';
if ($topic->flags['userIsSubscribed']) {
    $class .= 'subscribedtopic';
} else {
    $class .= 'unsubscribedtopic';
}?>
  <a id="subscription_<?php echo $topic->topic_id ?>" class="<?php echo $class ?>" href="<?php _url('topics/toggle_subscription/'.$topic->topic_id) ?>">
     <?php _h($topic->name) ?>
  </a>

