<div class="info-box">
  <img src="<?php echo image_path('icons/change_type_'.$file_change->getFileChangeType()->getIcon().'.png'); ?>" align="top" alt="<?php echo $file_change->getFileChangeType(); ?>"/>
  &nbsp;<a href="<?php echo url_for('commit/report?type=file&period=all&param='.$file_change->getFilePath())?>" title="Show file history"><?php echo $file_change->getFilePath() ?></a>
  <?php if ($previous_commit): ?>
  (comparing with revision <?php echo $previous_commit->getId(); ?>)
  <?php endif; ?>
</div>

<?php if ($sf_user->hasFlash('inline-error')): ?>
<div class="error-box">
  <?php echo $sf_user->getFlash('inline-error'); ?>
</div>
<?php endif; ?>

<div class="tabs">
  <ul>
    <?php if ($previous_commit && ($type == 'text')): ?>
    <li><a href="#tabs-1">Summary</a></li>
    <?php endif; ?>
    <li><a href="#tabs-2">Full view</a></li>
    <?php if ($previous_image): ?>
    <li><a href="#tabs-3">Previous</a></li>
    <?php endif; ?>

  </ul>
  <?php if ($previous_commit && ($type == 'text')): ?>
  <div id="tabs-1"><pre style="overflow: auto; display: block"><?php echo !is_null($unified_diff) ? sfGeshi::parse_single($sf_data->getRaw('unified_diff'), 'diff') : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.')); ?></pre></div>
  <?php endif; ?>
  <div id="tabs-2">
    <?php if ($type == 'image'): ?>
      <img src="<?php echo url_for('@change_download?file_change_id='.$file_change->getId())?>" /><br />
    <?php endif; ?>
    <?php if ($type == 'text'): ?>
    <pre style="overflow: auto; display: block"><?php if ($previous_commit):
        echo !is_null($inline_diff) ? $sf_data->getRaw('inline_diff') : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.'));
      else:
        echo $code;
      endif; ?></pre>
    <?php else: ?>
      <a href="<?php echo url_for('@change_download?file_change_id='.$file_change->getId())?>">Download this file</a>
    <?php endif; ?>
  </div>
  <?php if ($previous_image): ?>
  <div id="tabs-3">
    <img src="<?php echo url_for('@change_download?file_change_id='.$previous_commit->getId())?>" /><br />
  </div>
  <?php endif; ?>
</div>
<script type="text/javascript">
  jQuery(".tabs").tabs();
</script>