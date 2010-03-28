<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin=getUserLogin();
$user_id = $userlogin->userId();
$this->load->helper('form');
$this->load->helper('user');
?>
<h2><?php _e('Edit topic access levels') ?></h2>
<p><?php _e('You have to manually edit all access levels &ndash; there is no automatic propagation of changes up or down the tree.') ?></p>
<table>
  <tr>
    <th colspan='2'>
      <?php _e('Effective') ?>
      <?php _icon('info', Null, array('title' => __('Effective access levels (after combining all relevant access levels)'))) ?>
    </th>
    <th>
      <?php _e('Topic') ?>
      <?php _icon('info', Null, array('title' => __('Topic'))) ?>
    </th>
    <th>
      <?php _e('Owner') ?>
      <?php _icon('info', Null, array('title' => __('Owner of topic (only owner can change objects with private edit levels&hellip;)'))) ?>
    </th>
    <th colspan='2'>
      <?php _e('Individual per-object access levels') ?>
      <?php _icon('info', Null, array('title' => __('Per-object access levels'))) ?>
    </th>
  </tr>
<?php
$config = array('onlyIfUserSubscribed'=>True,
                 'user'=>$this->user_db->getByID($user_id),
                 'includeGroupSubscriptions'=>True);
$root = $this->topic_db->getByID(1,$config);

$todo = array($root);

$first = True;
$level = 0;
/* This is an experiment in left traversal of the tree that does not need nested views. (loading nested views seems to be extremely inefficient) */
while (sizeof($todo)>0){
    //get next topic to be displayed
    $next = $todo[0];
    //remove from todo list
    unset($todo[0]);
    if (!is_a($next,'Topic') && ($next=="end")) { 
        //if next is an end marker:
        $level--;
        $todo = array_values($todo); //reindex
    } else {
        //if next is a node: 
        $children = $next->getChildren();
        if (!$first) {
            //MAKE TABLE ROW
            $topic = $next;
            ?>
            <tr <?php
                if ($topic_id==$topic->topic_id)echo 'style="background:#dfdfff;" ';
                ?>>
                <td>
                  r:<?php _icon('al_'.$topic->derived_read_access_level.'_grey') ?>
                </td>
                <td>
                  e:<?php _icon('al_'.$topic->derived_edit_access_level.'_grey') ?>
                </td>
                <td class="level<?php echo $level ?>">
                  <?php _a('topics/single/'.$topic->topic_id, h($topic->name)) ?>
                </td>
                <td>
                  <?php 
                  if ($topic->user_id==$user_id) {
                      echo '<span>'.getAbbrevForUser($topic->user_id).'</span>';
                  } else {
                      _a(getUrlForUser($topic->user_id), getAbbrevForUser($topic->user_id));
                  }
                  ?>
                </td>
                <td>
                  <?php $this->load->view('accesslevels/editpanel', array('object'=>$topic,'type'=>'topic','object_id'=>$topic->topic_id)); ?>
                </td>
                <td>
                </td>
            </tr>
            <?php
            //END MAKE TABLE ROW
        }
        if (sizeof($children)>0) {
            //has children: open node and add all children + end marker in front of todo list; print this node
            $todo = array_merge($children,array('end'),$todo); //merge and reindex
            $level++;
        } else {
            $todo = array_values($todo); //reindex
        }
        $first = False;
    }
     //reindex
}    
?>
</table>    
<?php $this->load->view('accesslevels/legenda') ?>