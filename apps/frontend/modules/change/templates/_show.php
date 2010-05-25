<div class="info-box">
  <img src="<?php echo image_path('icons/change_type_'.$file_change->getFileChangeType()->getIcon().'.png'); ?>" align="top" alt="<?php echo $file_change->getFileChangeType(); ?>"/>
  &nbsp;<a href="<?php echo url_for('commit/report?type=file&period=all&param='.$file_change->getFilePath())?>" title="Show file history"><?php echo $file_change->getFilePath() ?></a>
  <?php if ($previous_commit): ?>
  (comparing with revision <?php echo $previous_commit->getId(); ?>)
  <?php endif; ?>
</div>

<div class="tabs">
  <ul>
    <?php if ($previous_commit): ?>
    <li><a href="#tabs-1">Summary</a></li>
    <?php endif; ?>
    <li><a href="#tabs-2">Full view</a></li>
  </ul>
  <?php if ($previous_commit): ?>
  <div id="tabs-1"><pre><?php echo !is_null($unified_diff) ? sfGeshi::parse_single($sf_data->getRaw('unified_diff'), 'diff') : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.')); ?></pre></div>
  <?php endif; ?>
  <div id="tabs-2">
    <pre><?php if ($previous_commit):
        echo !is_null($inline_diff) ? $sf_data->getRaw('inline_diff') : include_partial('global/error_large', array('message' => 'There was an error while obtaining the difference.'));
      else:
        echo $code;
      endif; ?></pre>
  </div>
</div>
<script type="text/javascript">
  jQuery(".tabs").tabs();
</script>