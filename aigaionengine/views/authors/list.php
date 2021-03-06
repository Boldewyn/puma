<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h2><?php _h($header) ?></h2>
<?php if (isset($searchbox) && $searchbox): ?>
  <form method="post" action="<?php _url('authors/searchlist')?>">
    <p>
      <input type="text" class="text" name="author_search" id="author_search" />
      <input type="submit" value="<?php _e('Show')?>" />
      <script type='text/javascript'>
        $('#author_search').keyup(function () {
          $('#authorlist').load('authors/searchlist/'+$(this).val());
        });
      </script>
    </p>
  </form>
<?php endif; ?>
<div id="authorlist" class="textcolumn double_textcolumn">
  <?php $this->load->view('authors/list_items', $authorlist); ?>
</div>
