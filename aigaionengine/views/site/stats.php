<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
views/site/stats

Shows a block of site stats

*/
$authorCount = $this->author_db->getAuthorCount();
$topicCount = $this->topic_db->getMainTopicCount();
$publicationCount = $this->topic_db->getPublicationCountForTopic(1);
$publicationReadCount = $this->topic_db->getReadPublicationCountForTopic(1);


?>
<div class="statistics <?php if(isset($embed) && $embed): ?>embed<?php endif; ?>">
  <h2><?php echo __('Statistics')?></h2>
  <ul>
    <li><?php printf(__('%s publications (%s read)'), $publicationCount, $publicationReadCount)?></li>
    <li><?php printf(__('%s authors'), $authorCount)?></li>
    <li><?php printf(__('%s main topics'), $topicCount)?></li>
  </ul>
</div>
