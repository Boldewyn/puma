<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* method defines the type of tree view */
if (! isset($method)) {
    $method = 'simple';
}
if (! isset($callhome)) {
    $callhome = False;
}
$callhome = $callhome? 'true' : 'false';
$id = $topic->topic_id;

$is_subscribed = false;
$is_collapsed = false;
if (array_key_exists('flagCollapsed', $topic->configuration)
    && ($topic->flags['userIsCollapsed']==True)) {
    $is_collapsed = true;
}

$icon = '';
if ($method != 'simple') {

    if ($method == 'publication') {
        $is_subscribed = $topic->flags['publicationIsSubscribed'];
    } else {
        $is_subscribed = $topic->flags['userIsSubscribed'];
    }

    if (count($topic->getChildren())>0):
        if ($is_collapsed) {
            $icon = icon('tree_plus', Null, array('alt'=>'[+]', 'id'=>"toggler_$id",
                'class'=>'topic-toggler topic-toggler-max topic-toggler-'.$callhome));
        } else {
            $icon = icon('tree_min', Null, array('alt'=>'[-]', 'id'=>"toggler_$id",
                'class'=>'topic-toggler topic-toggler-min topic-toggler-'.$callhome));
        }
    else:
        $icon = '<span class="tree-blank">&nbsp;</span>';
    endif;
}
echo $icon;

$pre = '';
$base_class = "subscribed do-subscribe do-subscribe-".$method;
if ($is_subscribed) {
    $pre = 'un';
} else {
    $base_class = "un$base_class";
}
switch ($method) {
    case 'publication':
        _a("publications/${pre}subscribe/$id/".$topic->configuration['publicationId'],
            h($topic->name), array('class'=>$base_class));
        break;
    case 'user':
        _a("users/${pre}subscribe/$id/".$topic->configuration['user']->user_id,
            h($topic->name), array('class'=>$base_class));
        if ($topic->flags['userIsGroupSubscribed']) {
            printf(' (%s)', __('group subscribed'));
        }
        break;
    case 'group':
        _a("groups/${pre}subscribe/$id/".$topic->configuration['user']->user_id,
            h($topic->name), array('class'=>$base_class));
        break;
    case 'main':
        $publicationCount     = $this->topic_db->getPublicationCountForTopic($topic->topic_id);
        $publicationReadCount = $this->topic_db->getReadPublicationCountForTopic($topic->topic_id);
        _a("topics/single/$id", h($topic->name), array('class'=>$base_class)); ?>
        <abbr class="topic-leaf-info" title="<?php printf(__('read: %s of %s publications'),
              $publicationReadCount, $publicationCount)?>"><?php
              echo $publicationReadCount ?>/<?php echo $publicationCount
              ?></abbr> <?php
        if (isset($subscription_links) && $subscription_links) {
            _a("users/${pre}subscribe/$id/".$topic->configuration['user']->user_id,
                $pre? __("unsubscribe") : __("subscribe"), array('class'=>'topic-leaf-info do-subscribe do-subscribe-main-add'));
        }
        break;
    default:
        _a("topics/single/$id", h($topic->name), array('class'=>$base_class));
        break;
}






