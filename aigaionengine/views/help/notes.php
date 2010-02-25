<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-notes" class="help-content">
  <h2><?php _e('Annotating the Bibliography')?></h2>
  <p><?php _e('Where a publication abstract is a purely descriptive summary of a publication, a annotation can be both descriptive and critical. Annotations are commonly used to:')?></p>
  <ul>
    <li><?php _e('place a publication in a context')?></li>
    <li><?php _e('describe the relevance of a publication')?></li>
    <li><?php _e('summarize the strengths and weaknesses of a publication')?></li>
  </ul>
  <p><?php printf(__('%s offers the &lsquo;note&rsquo; facility to create annotations. An annotation might look like the following example:'), site_title())?></p>
  <div class="message">
  <span title="Example"><b>[EXA]</b></span> :&nbsp;Extensive evaluation of several featuresets and classifiers. The evaluation confirms the results that have been found in <i><a href='#'>aucouturier:04</a></i>.<br/>
  <ul>
    <li>There seems to be a glass ceiling in classification accuracy.</li>
    <li>The featureset found by Aucouturier indeed represents an optimal set.</li>
  </ul>
  </div>

  <h3><?php _e('Referencing other publications')?></h3>
  <p><?php _e('You can reference to other publications by simply using the publications BibTeX cite ID. On displaying the note, the cite ID will be replaced by a link to the corresponding publication.')?></p>

  <h3><?php _e('Formatting annotations')?></h3>
  <p><?php _e('To improve the readability of annotations it is recommended to keep annotations short and to the point. Standard HTML formatting tags can be used to format an annotation. Therefore the notes field is equipped with a simple HTML editor.')?></p>
</div>
