<?php header('HTTP/1.1 501 Not Implemented')?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <title><?php _e('Error')?> - Puma.&Phi;</title>
    <link rel="stylesheet" href="<?php echo base_url()?>static/css/style.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>static/js/jquery.js"></script>
  </head>
  <body id="error_general" class="errorbody">
    <div id="container">
      <div id="header">
        <div id="header_widgets">
          <p id="header_backlink">
            <a href="http://www.uni-regensburg.de/">Uni Regensburg</a>
          </p>

          <div id="header_controls">
            <form action="<?php echo base_url()?>search/quicksearch" method="post" id="quicksearch" class="header_control">
              <p>
                <input type="hidden" name="formname" value="simplesearch" />
                <input type="text" class="text" name="q" value="" />
                <input type="submit" class="submit" name="submit_search" value="<?php _e('Search')?>" />
              </p>
            </form>
            <p class="language header_control">
              <?php _e('Language:')?> <a href="<?php echo base_url()?>language/set/de/search" title="Deutsch">de</a>,
              <a href="<?php echo base_url()?>language/set/en/search" title="English">en</a>
            </p>
            <p class="userdata header_control"></p>
          </div>
        </div>
        <h1>
          <a href="<?php echo base_url()?>"><span><?php _site_title()?></span></a>
        </h1>
        <h2>
          <a href="<?php echo base_url()?>"><span>Publication Management for the Faculty of Physics</span></a>
        </h2>

      </div>
      
      <?php $subnav = array(); $subnav_current = ""; $nav_current = ""; include APPPATH.'views/menu.php';?>

      <div id="content">
        <h2><?php echo $heading ?></h2>
        <?php echo $message ?>
        <p><?php _e('If this error continues to occur, please use the form at the end of this page to contact the site admin.')?></p>
        <hr/>
        <h3><?php _e('Search the site:')?></h3>
        <form action="<?php echo base_url()?>search/quicksearch" method="post">
          <p>
            <input type="hidden" name="formname" value="simplesearch" />
            <input type="text" class="text" name="q" value="" />
            <input type="submit" class="submit" name="submit_search" value="<?php _e('Search')?>" />
          </p>
        </form>

<?php include APPPATH.'views/footer.php';?>
