<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Provide controls for sorting
 *
 * the sort options are only available if the view is called with a 'sortPrefix' option that is not ''
 */
$methods = array(
    'author' => __('Author'),
    'title' => __('Title'),
    'type' => __('Type/Journal'),
    'year' => __('Year'),
    'recent' => __('Recently added'),
);

if (isset($sortPrefix) && ($sortPrefix!='')):
  if (strpos($sortPrefix, '%s') === False) {
    $sortPrefix = rtrim($sortPrefix, '/') . '/%s';
  }
  ?>
  <div class="sortcontrols">
    <p><?php _e('Sort by:') ?></p>
    <ul class="tabs">
      <?php foreach ($methods as $method => $label): ?>
        <li class="<?php if(strpos('/'.uri_string(), $sortPrefix.$method) === 0) { echo 'active'; }
            ?>"><?php _a(sprintf($sortPrefix, $method), $label)?></li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif; ?>
