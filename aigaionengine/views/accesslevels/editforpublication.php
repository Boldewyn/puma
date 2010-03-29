<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin=getUserLogin();
$user_id = $userlogin->userId();
$this->load->helper('user');
?>
<p><?php _a('publications/show/'.$publication->pub_id,__('Back to publication')) ?></p>
<h2><?php echo __('Edit access levels')?></h2>
<table>
  <tr>
    <th colspan='2'>
      <?php _e('Effective') ?>
      <?php _icon('info', Null, array('title' => __('Effective access levels (after combining all relevant access levels)'))) ?>
    </th>
    <th>
      <?php _e('Object') ?>
      <?php _icon('info', Null, array('title' => __('Object'))) ?>
    </th>
    <th>
      <?php _e('Owner') ?>
      <?php _icon('info', Null, array('title' => __('Owner of object (only owner can change objects with private edit levels&hellip;)'))) ?>
    </th>
    <th>
      <?php _e('Individual per-object access levels') ?>
      <?php _icon('info', Null, array('title' => __('Per-object access levels'))) ?>
    </th>
  </tr>
  <tr>
    <td colspan="2"></td>
    <th colspan="3"><?php _e('Publication:') ?></th>
  </tr>
  <tr <?php
    if ($type=='publication')echo 'style="background:#dfdfff;" ';
    ?>>
    <td>
      r:<?php _icon('al_'.$publication->derived_read_access_level.'_grey') ?>
    </td>
    <td>
      e:<?php _icon('al_'.$publication->derived_edit_access_level.'_grey') ?>
    </td>
    <td>
      <?php _h($publication->title) ?>
    </td>
    <td>
      <?php 
      if ($publication->user_id==$user_id) {
          echo '<span>'.getAbbrevForUser($publication->user_id).'</span>';
      } else {
          _a(getUrlForUser($publication->user_id), getAbbrevForUser($publication->user_id));
      }
      ?>
    </td>
    <td>
      <?php $this->load->view('accesslevels/editpanel', array('object'=>$publication,'type'=>'publication','object_id'=>$publication->pub_id)); ?>
    </td>
  </tr>

  <tr>
    <td colspan="2"></td>
    <th colspan="3"><?php _e('Attachments:') ?></th>
  </tr>
  <?php foreach ($publication->getAttachments() as $attachment): ?>
    <tr <?php
      if (($type=='attachment')&&($object_id==$attachment->att_id))echo 'style="background:#dfdfff;" ';
      ?>>
      <td>
        r:<?php _icon('al_'.$attachment->derived_read_access_level.'_grey') ?>
      </td>
      <td>
        e:<?php _icon('al_'.$attachment->derived_edit_access_level.'_grey') ?>
      </td>
      <td>
        <?php _h($attachment->name) ?>
      </td>
      <td>
        <?php 
        if ($attachment->user_id==$user_id) {
            echo '<span>'.getAbbrevForUser($attachment->user_id).'</span>';
        } else {
            _a(getUrlForUser($attachment->user_id), getAbbrevForUser($attachment->user_id));
        }
        ?>
      </td>
      <td>
        <?php $this->load->view('accesslevels/editpanel', array('object'=>$attachment,'type'=>'attachment','object_id'=>$attachment->att_id)); ?>
      </td>
    </tr>
  <?php endforeach ?>

  <tr>
    <td colspan="2"></td>
    <th colspan="3"><?php _e('Notes:') ?></th>
  </tr>
  <?php foreach ($publication->getNotes() as $note): ?>
    <tr <?php
      if (($type=='note')&&($object_id==$note->note_id))echo 'style="background:#dfdfff;" ';
      ?>>
      <td>
        r:<?php _icon('al_'.$note->derived_read_access_level.'_grey') ?>
      </td>
      <td>
        e:<?php _icon('al_'.$note->derived_edit_access_level.'_grey') ?>
      </td>
      <td>
        <?php echo $note->text ?>
      </td>
      <td>
        <?php 
        if ($note->user_id==$user_id) {
            echo '<span>'.getAbbrevForUser($note->user_id).'</span>';
        } else {
            _a(getUrlForUser($note->user_id), getAbbrevForUser($note->user_id));
        }
        ?>
      </td>
      <td>
        <?php $this->load->view('accesslevels/editpanel', array('object'=>$note,'type'=>'note','object_id'=>$note->note_id)); ?>
      </td>
    </tr>
  <?php endforeach ?>

</table>
<?php $this->load->view('accesslevels/legenda') ?>
<div class="regular-text">
  <p><?php _e('When you modify access levels of individual objects, this may have consequences for the final &ldquo;effective&rdquo; access level of other objects. For example, when you set a publication to private, the effective access level of all objects belonging to that publication will be set to private as well.') ?></p>
  <p><?php _e('On the other hand, when you edit the read access level of for example an attachment, and the new level is higher than that of the publication it belongs to, the <strong>actual</strong> read level of the publication is updated, too.') ?></p>
  <p><?php _e('The effective access levels are shown on the left; the access levels defined per individual object are shown on the right. Editing of access levels is done in the right column.') ?></p>
  <p><strong><?php _e('Unsure how the access levels turn out?')?></strong> <?php _e('The column on the left shows which objects are effectively accessible with what levels.') ?></p>
  <h4><?php _e('Examples:') ?></h4>
  <ul>
    <li><?php _e('Publication is &ldquo;intern&rdquo;; attachment is &ldquo;intern&rdquo;. <em>Set</em> attachment to &ldquo;public&rdquo; &rarr; publication will become &ldquo;public&rdquo; as well.') ?></li>
    <li><?php _e('Publication is &ldquo;intern&rdquo;; attachment is &ldquo;intern&rdquo;. <em>Set</em> publication to &ldquo;private&rdquo; &rarr; attachment stays &ldquo;intern&rdquo;, but <em>effective</em> access level of attachment becomes &ldquo;private&rdquo;. When you set the publication to &ldquo;intern&rdquo; again, the effective access level of the attachment reverts to &ldquo;intern&rdquo;.') ?></li>
    <li><?php _e('Attachment read is &ldquo;public&rdquo;, attachment edit is &ldquo;intern&rdquo;. Set attachment read to &ldquo;private&rdquo; &rarr; attachment edit will also change to &ldquo;private&rdquo;.') ?></li>
    <li><?php _e('A publication has edit level &ldquo;intern&rdquo;. You are not the owner. You change the edit level to &ldquo;private&rdquo;. &rarr; Subsequently, you can no longer edit that publication :o)') ?></li>
  </ul>
</div>
