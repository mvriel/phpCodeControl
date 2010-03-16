<table class="full-page" cellpadding="5" cellspacing="0">
<tr><td class="left-sidebar">
  <div style="float: right"><small><a href="<?php echo url_for('commit/index') ?>">Back to overview</a></small></div>
  <strong>Commit</strong>
  <table>
    <tbody>
      <tr>
        <th>Revision:</th>
        <td><?php echo $commit->getId() ?></td>
      </tr>
      <tr>
        <th>Project:</th>
        <td><?php echo $commit->getProject() ?></td>
      </tr>
      <tr>
        <th>Author:</th>
        <td><?php echo $commit->getAuthor() ?></td>
      </tr>
      <tr>
        <th>Timestamp:</th>
        <td><?php echo $commit->getTimestamp() ?></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo $commit->getMessage() ?></td>
      </tr>
    </tbody>
  </table>

  <hr />
  <strong>Changes</strong>
  <div style="width:190px; overflow: auto;">
  <?php foreach ($commit->getFileChange() as $change): ?>
    <div style="white-space: nowrap">
      <img src="<?php echo image_path('icons/change_type_'.$change->getChangeType().'.png'); ?>" alt="<?php echo $change->getChangeType(); ?>"/>
      <a href="#" onclick="jQuery('#change_content').load('<?php echo url_for('commit/loader');?>', function(){ jQuery('#change_content').load('<?php echo url_for('change/show?id='.$change->getId());?>'); });" title="<?php echo $change->getFilePath(); ?>"><?php echo basename($change->getFilePath()); ?></a><br />
    </div>
  <?php endforeach; ?>
  </div>
</td>
<td id="change_content">
  <?php $changes = $sf_data->getRaw('commit')->getFileChange(); include_component('change', 'show', array('file_change' => $changes[0])); ?>
</td>
</tr>
</table>