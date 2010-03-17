<div class="info-box">
  <img src="<?php echo image_path('icons/change_type_'.$file_change->getFileChangeType()->getIcon().'.png'); ?>" align="top" alt="<?php echo $file_change->getFileChangeType(); ?>"/>
  &nbsp;<a href="<?php echo url_for('commit/report?type=file&period=all&param='.$file_change->getFilePath())?>" title="Show file history"><?php echo $file_change->getFilePath() ?></a>
  <?php if ($previous_commit): ?>
  (comparing with revision <?php echo $previous_commit->getId(); ?>)
  <?php endif; ?>
</div>

<?php if ($previous_commit): ?>
  <div class="tabs">
    <ul>
      <li><a href="#tabs-1">Summary</a></li>
      <li><a href="#tabs-2">Full view</a></li>
    </ul>
    <div id="tabs-1"><pre><?php echo $unified_diff ? $unified_diff : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.')); ?></pre></div>
    <div id="tabs-2"><pre><?php echo $inline_diff ? $inline_diff : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.')); ?></pre></div>
  </div>
  <script type="text/javascript">
    jQuery(".tabs").tabs();
  </script>
<?php else: ?>
  <pre><?php echo $code; ?></pre>
<?php endif; ?>
