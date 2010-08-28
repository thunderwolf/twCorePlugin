<?php if (!$sf_user->isAuthenticated()): ?>
  <ul id="twadmin-personaltools">
    <li class="portalNotLoggedIn"><?php echo link_to(__('login'), '@sf_guard_signin') ?></li>
  </ul>
<?php else: ?>
  <ul id="twadmin-personaltools">
    <li class="portalUser"><a href="#"><?php echo $sf_user->getUsername() ?></a></li>
    <li class="portalLoggedOut"><?php echo link_to(__('logout'), '@sf_guard_signout') ?></li>
  </ul>
<?php endif; ?>