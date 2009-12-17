<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
      </div>
      <div id="footer">
        <p><?php echo sprintf(__('processing time: %s seconds'), $this->benchmark->elapsed_time());?>.</p>
      </div>
    </div>
    <script type="text/javascript" src="<?php echo base_url(); ?>static/js/puma.js"></script>
  </body>
</html>
