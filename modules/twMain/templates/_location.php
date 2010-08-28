<?php echo __('You are in:'); ?>
<?php if (!$sf_user->isAuthenticated()): ?>
<?php echo link_to(__('Home'), '@homepage'); ?>
<span class="locationSeparator">»</span>
<?php echo link_to(__('Login'), '@sf_guard_signin') ?>
<?php else: ?>
<?php echo link_to(__('Home'), '@homepage') ?>
<?php endif; ?>

