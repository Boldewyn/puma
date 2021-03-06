<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (is_ajax()):
    $this->load->view('footer_clean');
else:
$userlogin = getUserLogin();
?>
      </div>
    </div>
    <div id="footer">
      <div id="footer_content">
        <p>
          <?php _site_title()?> — Publication Management for the Faculty of Physics
        </p>
      </div>
        <ul class="level1">
          <li class="level1 level1_first">
            <h3><?php _e('Useful Links'); ?></h3>
            <ul>
              <li><a href="http://www.uni-regensburg.de">Universität Regensburg</a></li>
              <li><a href="http://www.physik.uni-regensburg.de"><?php _e('Faculty of Physics');?></a></li>
              <li><a href="http://www.bibliothek.uni-regensburg.de"><?php _e('University library');?></a></li>
              <li><a href="http://www.uni-regensburg.de/e/r"><?php _e('Computer Centre');?></a></li>
              <li><a href="http://scholar.google.com">Google Scholar</a></li>
              <li><a href="http://arxiv.org/">arXiv.org</a></li>
              <li><a href="http://www.slac.stanford.edu/spires/hep/">Spires HEP</a></li>
              <li><a href="http://citeseer.ist.psu.edu/">Citeseer</a></li>
              <li><a href="http://www.dpg-physik.de">DPG</a>, <a href="http://de.physnet.net/PhysNet/">PhysNet</a></li>
            </ul>
          </li>
          <li class="level1">
            <h3><?php _e('Ask the Admin'); ?></h3>
            <p style="margin-bottom:1em">
              <?php printf(__('Do you have any question regarding %s? Have you spotted an error '.
              'or do you want to suggest a new feature? Don’t hesitate and drop us a line.'), site_title())?>
            </p>
            <?php echo form_open('user/admin/contact'); ?>
              <p>
                <input type="text" class="text" name="name" id="footer_ask_name" value="<?php
                  echo (! is_user())? __('Name') :
                        $userlogin->preferences['firstname'].' '.$userlogin->preferences['surname']; ?>" />
              </p>
              <p>
                <input type="text" class="text" name="email" id="footer_ask_email" value="<?php
                  echo !is_user()? __('E-Mail') :
                        $userlogin->preferences['email']; ?>" />
              </p>
              <p>
                <label for="footer_ask_question"><?php _e('Question:')?></label>
                <textarea name="message" id="footer_ask_question" rows="2" cols="20"></textarea>
              </p>
              <p style="text-align:center">
                <input type="submit" class="submit" value="<?php _e('ask the admin'); ?>" />
              </p>
            </form>
          </li>
          <li class="level1">
            <h3><?php _e('About'); ?></h3>
            <p>
              <?php printf(__('%s ist das Promotionsprojekt von Manuel Strehl und entsteht in Zusammenarbeit mit dem '.
              'Lehrstuhl für Medieninformatik und der Fakultät für Physik an der Universität Regensburg. Ziel ist der Aufbau '.
              'eines umfassenden Web 2.0-Angebots für die Regensburger Physiker. '.
              'Die Anwendung basiert auf der freien Publikationsdatenbank %s.'),
              site_title(), '<a href="http://www.aigaion.nl">Aigaion</a>'); ?>
            </p>
          </li>
          <li class="level1 level1_last">
            <h3><?php _e('Help'); ?></h3>
            <ul>
              <li><?php _a('help', __('General help')); ?></li>
              <li><?php _a('help/about', __('About this site')); ?></li>
              <li><?php _a('help/faq', __('FAQ')); ?></li>
              <li><?php _a('help/tutorial', __('Video tutorial')); ?></li>
              <li><a href="http://www.aigaion.nl/forum"><?php _e('Aigaion forum'); ?></a></li>
              <!--li><?php _a('imprint', __('Imprint')) ?></a></li-->
            </ul>
          </li>
        </ul>
    </div>
    <script type="text/javascript" src="<?php echo base_url(); ?>static/js/puma.js"></script>
  </body>
</html>
<?php endif;
//__END__
