<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! isset($mode)) { $mode = 'simple'; }

$link = anchor('explore/topic/'.$topic->topic_id, h($topic->name));

switch ($mode) {
    case 'collapsable':
        if (sizeof($topic->getChildren())>0) {
            _icon('list-remove', '', 'id="control_topic_'.$topic->topic_id.'"');
            ?><script type="text/javascript">
              $("#control_topic_<?php echo $topic->topic_id ?>").click(function() {
                  var $icon = $(this);
                  if($icon.attr("src").search(/list-remove/) > -1) {
                      $icon.attr("src", $icon.attr("src").replace(/list-remove/, "list-add"));
                      $icon.nextAll("div").hide();
                      $.get(config.base_url+"option/set/topic_open_<?php echo $topic->topic_id ?>");
                  } else {
                      $icon.attr("src", $icon.attr("src").replace(/list-add/, "list-remove"));
                      $icon.nextAll("div").show();
                      $.get(config.base_url+"option/set/topic_open_<?php echo $topic->topic_id ?>/1");
                  }
              });<?php
            if (! array_key_exists('topic_open_'.$topic->topic_id, $open)) {
                ?>$(function(){$("#control_topic_<?php echo $topic->topic_id ?>").click();});<?php
            }
            ?></script><?php
        }

        $publicationCount     = $this->topic_db->getPublicationCountForTopic($topic->topic_id);
        $publicationReadCount = $this->topic_db->getReadPublicationCountForTopic($topic->topic_id);
        echo $link;
        echo ' <em title="',sprintf(__('%s of %s publications read'), $publicationReadCount, $publicationCount),
             '">',$publicationReadCount,'/',$publicationCount,'</em>';
        break;
    default:
        echo $link;
        break;
}
//__END__