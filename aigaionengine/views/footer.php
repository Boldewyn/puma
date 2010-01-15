<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$userlogin = getUserLogin();
?>
      </div>
    </div>
    <div id="footer">
      <div id="footer_content">
        <p>
          Puma.Φ — Publication Management for the Faculty of Physics
        </p>
      </div>
        <ul class="level1">
          <li class="level1 level1_first">
            <h3><?php _e('Useful links'); ?></h3>
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
            <h3><?php _e('Help'); ?></h3>
            <ul>
              <li><?php _a('help/about', __('About this site')); ?></li>
              <li><?php _a('help', __('General help')); ?></li>
              <li><?php _a('help/faq', __('FAQ')); ?></li>
              <li><?php _a('help/tutorial', __('Video tutorial')); ?></li>
              <li><a href="http://www.aigaion.nl/forum"><?php _e('Aigaion forum'); ?></a></li>
            </ul>
          </li>
          <li class="level1">
            <h3><?php _e('Ask the admin'); ?></h3>
            <p style="margin-bottom:1em">
              <?php _e('Do you have any question regarding Puma.&Phi;? Have you spotted an error or do you want to suggest a new feature? Don&rsquo;t hesitate and drop us a line.');?>
            </p>
            <?php echo form_open('contact/admin'); ?>
              <p>
                <input type="text" class="text" name="name" id="ask_name" value="<?php
                  echo $userlogin->isAnonymous()? __("Name") : $userlogin->preferences['firstname']." ".$userlogin->preferences['surname']; ?>" />
              </p>
              <p>
                <input type="text" class="text" name="email" id="ask_email" value="<?php
                  echo $userlogin->isAnonymous()? __("E-Mail") : $userlogin->preferences['email']; ?>" />
              </p>
              <p>
                <label for="ask_question"><?php _e('Question:')?></label>
                <textarea name="question" id="ask_question" rows="2" cols="20"></textarea>
              </p>
              <p style="text-align:center">
                <input type="submit" class="submit" name="name" value="<?php _e('ask the admin'); ?>" />
              </p>
            </form>
          </li>
          <li class="level1 level1_last">
            <h3><?php _e('About'); ?></h3>
            <p>
              <?php printf(__('Puma.Φ ist das Promotionsprojekt von Manuel Strehl und entsteht in Zusammenarbeit mit dem
              Lehrstuhl für Medieninformatik und der Fakultät für Physik an der Universität Regensburg. Ziel ist der Aufbau
              eines umfassenden Web 2.0-Angebots für die Regensburger Physiker.
              Die Anwendung basiert auf der freien Publikationsdatenbank %s.'), '<a href="http://www.aigaion.nl">Aigaion</a>'); ?>
            </p>
          </li>
        </ul>
    </div>
    <script type="text/javascript" src="<?php echo base_url(); ?>static/js/puma.js"></script>
  </body>
</html>
