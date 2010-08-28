<?php if ($sf_user->hasFlash('notice')): ?>
<div class="portalMessage">
  <?php $notice = $sf_user->getFlash('notice'); ?>
  <?php if (is_array($notice)): ?>
    <?php foreach($notice AS $module => $item): ?>
      <?php echo __($item, array(), $module) ?><br />
    <?php endforeach; ?>
  <?php else: ?>
    <?php echo __($notice) ?>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($sf_user->hasFlash('warning')): ?>
<div class="portalWarning">
  <?php $warning = $sf_user->getFlash('warning'); ?>
  <?php if (is_array($warning)): ?>
    <?php foreach($warning AS $module => $item): ?>
      <?php echo __($item, array(), $module) ?><br />
    <?php endforeach; ?>
  <?php else: ?>
    <?php echo __($warning) ?>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($sf_user->hasFlash('error')): ?>
<div class="portalError">
  <?php $error = $sf_user->getFlash('error'); ?>
  <?php if (is_array($error)): ?>
    <?php foreach($error AS $module => $item): ?>
      <?php echo __($item, array(), $module) ?><br />
    <?php endforeach; ?>
  <?php else: ?>
    <?php echo __($error) ?>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($sf_user->hasFlash('critical')): ?>
<div class="portalCritical">
  <?php $critical = $sf_user->getFlash('critical'); ?>
  <?php if (is_array($error)): ?>
    <?php foreach($critical AS $module => $item): ?>
      <?php echo __($item, array(), $module) ?><br />
    <?php endforeach; ?>
  <?php else: ?>
    <?php echo __($critical) ?>
  <?php endif; ?>
</div>
<?php endif; ?>