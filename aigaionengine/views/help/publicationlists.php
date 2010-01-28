<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-content">
  <h2><?php _e('The Publication Lists')?></h2>
  <p><?php printf(__('%s offers multiple ways to explore your publications, the standard list sorts all publications in the database by year. You can also browse the publications sorted by title, by publicationtype or by topic.'), puma())?></p>
  <div class='message'>
  	<table>
  	  <tr>
    		<td><a>Rienks, Rutger</a>,&nbsp;<a>Poppe, Ronald</a>&nbsp;and&nbsp;<a>Poel, Mannes</a>&nbsp;<a>Speaker Prediction based on Head Orientations</a>,&nbsp;in <i>Proceedings of the Fourteenth Annual Machine Learning Conference of Belgium and the Netherlands (Benelearn 2005)</i>, <a>Otterlo van, Martijn</a>,&nbsp;<a>Poel, Mannes</a>&nbsp;and&nbsp;<a>Nijholt, Anton</a> (eds), CTIT Workshop Proceedings Series WP05-03, pp.&nbsp;73-79, 2005</td>
    		<td valign=top align=right><?php _icon('publication_edit_small') ?>&nbsp;<?php _icon('attachment_pdf') ?></td>
  	  </tr>
  	</table>
  </div>
  <p class="caption"><?php _e('An example publication entry')?></p>

  <h3><?php _e('Icons in the publication list and their functions.')?></h3>
  <ul>
    <li><?php _icon('publication_edit_small') ?> <?php _e('Edit the information of this publication.')?></li>
    <li><?php _icon('attachment_pdf') ?> <?php _e('View the pdf file of this article (only if available).')?></li>
  </ul>
</div>
