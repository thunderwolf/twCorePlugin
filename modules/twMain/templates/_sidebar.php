<?php use_helper('I18N') ?>
<?php if (!$sf_user->isAuthenticated()): ?>
    <div class="panel-menu" id="menu-twadmin">
      <h5><?php echo __('Administration') ?></h5>
      <div>
        <?php echo link_to('Login', '@sf_guard_signin') ?><br />
      </div>
    </div>
<?php else: ?>
 
    <?php if (!empty($sidebar)): ?>
    <?php foreach ($sidebar AS $key => $plugins): ?>
    <div class="panel-menu" id="menu-twadmin">
      <h5><?php echo $plugins_array[$key] ?></h5>
      <div>
      <?php foreach ($plugins AS $plugin): ?>
        <?php echo link_to($plugin->getName(), $plugin->getRoute()) ?><br />
      <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="panel-menu" id="menu-twadmin">
      <h5>Error - no plugins!</h5>
    </div>
    <?php endif; ?>
    
<?php endif; ?>
