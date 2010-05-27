<?php slot('sidebar');?>
<div class="section">
  <h1>Actions</h1>
  <a href="<?php echo url_for('commit/index') ?>"><img src="<?php echo image_path('icons/previous.png');?>" border="0" height="12" style="margin-right: 4px;"/>&nbsp;Back to commits</a><br />
  <a href="<?php echo url_for('scm/new') ?>"><img src="<?php echo image_path('icons/change_type_add.png');?>" border="0" />&nbsp;Add new SCM</a>
</div>
<?php end_slot(); ?>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Scm type</th>
      <th>Host</th>
      <th>Path</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($scms as $scm): ?>
    <tr>
      <td><a href="<?php echo url_for('scm/edit?id='.$scm->getId()) ?>"><?php echo $scm->getName() ?></a></td>
      <td><?php echo $scm->getScmType() ?></td>
      <td><?php echo $scm->getHost() ?></td>
      <td><a href="<?php echo url_for('scm/edit?id='.$scm->getId()) ?>"><?php echo $scm->getPath() ?></a></td>
      <td><?php echo $scm->getUsername() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>