<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul id='help-nav'>
  <li class="<?php if($topic == 'front'           ) { echo 'active'; }?> first"><?php _a('help', __('Introduction')); ?></li>
  <li class="<?php if($topic == 'about'           ) { echo 'active'; }?>"><?php _a('help/about', __('About')); ?></li>
  <li class="<?php if($topic == 'faq'             ) { echo 'active'; }?>"><?php _a('help/faq', __('FAQ')); ?></li>
  <li class="<?php if($topic == 'tutorial'        ) { echo 'active'; }?>"><?php _a('help/tutorial', __('Video tutorial')); ?></li>
  <li class="<?php if($topic == 'topictree'       ) { echo 'active'; }?>"><?php _a('help/topictree', __('Topic Tree')); ?></li>
  <li class="<?php if($topic == 'notes'           ) { echo 'active'; }?>"><?php _a('help/notes', __('Annotating Publications')); ?></li>
  <li class="<?php if($topic == 'publicationlists') { echo 'active'; }?>"><?php _a('help/publicationlists', __('Publication Lists')); ?></li>
  <li class="<?php if($topic == 'attachments'     ) { echo 'active'; }?>"><?php _a('help/attachments', __('Attachments')); ?></li>
  <li class="<?php if($topic == 'accounts'        ) { echo 'active'; }?>"><?php _a('help/accounts', __('Accounts')); ?></li>
  <li class="<?php if($topic == 'accessrights'    ) { echo 'active'; }?>"><?php _a('help/accessrights', __('Access Rights')); ?></li>
  <li class="<?php if($topic == 'crossref'        ) { echo 'active'; }?>"><?php _a('help/crossref', __('Crossreferencing')); ?></li>
<?php /*  <li class="<?php if($topic == 'themes'          ) { echo 'active'; }?>"><?php _a('help/themes', __('Themes')); ?></li> */?>
  <li class="<?php if($topic == 'goodpractices'   ) { echo 'active'; }?> last"><?php _a('help/goodpractices', __('Good Practices')); ?></li>
</ul>
