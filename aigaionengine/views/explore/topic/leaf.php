<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (sizeof($topic->getChildren())>0) {
    _icon('list-remove', '', 'id="control_topic_'.$topic->topic_id.'"');
    echo '<script type="text/javascript">$("#control_topic_'.$topic->topic_id.'").click(function() {
            var $icon = $(this);
            if($icon.attr("src").search(/list-remove/) > -1) {
                $icon.attr("src", $icon.attr("src").replace(/list-remove/, "list-add"));
                $icon.nextAll("div").hide();
                $.get(config.base_url+"option/set/topic_collapsed_'.$topic->topic_id.'/1");
            } else {
                $icon.attr("src", $icon.attr("src").replace(/list-add/, "list-remove"));
                $icon.nextAll("div").show();
                $.get(config.base_url+"option/set/topic_collapsed_'.$topic->topic_id.'");
            }
          });</script>';
}
$publicationCount     = $this->topic_db->getPublicationCountForTopic($topic->topic_id);
$publicationReadCount = $this->topic_db->getReadPublicationCountForTopic($topic->topic_id);
_a('explore/topic/'.$topic->topic_id, h($topic->name));
echo ' <em title="',sprintf(__('read: %s of %s publications'), $publicationReadCount, $publicationCount),
     '">',$publicationReadCount,'/',$publicationCount,'</em>';
?>