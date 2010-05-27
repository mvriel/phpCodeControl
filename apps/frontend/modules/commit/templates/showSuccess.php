<?php slot('sidebar'); ?>
<div class="section">
  <h1>Details</h1>
  <table>
    <tbody>
      <tr>
        <th>Scm:</th>
        <td><?php echo $commit->getScm() ?></td>
      </tr>
      <tr>
        <th>Revision:</th>
        <td><?php echo $commit->getRevision() ?></td>
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
</div>

<div class="section">
  <h1>Actions</h1>
  <a href="<?php echo url_for('commit/index') ?>">Back to overview</a><br />
</div>

<div class="section">
  <h1>Changes</h1>
  <div style="width:190px; overflow: auto;">
  <?php foreach ($commit->getFileChange() as $change): ?>
    <div style="white-space: nowrap">
      <img src="<?php echo image_path('icons/change_type_'.$change->getFileChangeType()->getIcon().'.png'); ?>" alt="<?php echo $change->getFileChangeType(); ?>"/>
      <a href="#" onclick="jQuery('#change_content').load('<?php echo url_for('commit/loader');?>', function(){ jQuery('#change_content').load('<?php echo url_for('change/show?id='.$change->getId());?>'); });" title="<?php echo $change->getFilePath(); ?>"><?php echo basename($change->getFilePath()); ?></a><br />
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php end_slot(); ?>

<?php include_component('change', 'show', array('file_change' => $selected_change)); ?>
