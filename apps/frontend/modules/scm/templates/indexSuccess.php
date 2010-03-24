<table class="full-page" cellpadding="5" cellspacing="0">
<tr>
  <td class="left-sidebar">
    <a href="<?php echo url_for('commit/index') ?>"><img src="<?php echo image_path('icons/previous.png');?>" border="0" />&nbsp;Back to commits</a><br />
    <br />
    <a href="<?php echo url_for('scm/new') ?>"><img src="<?php echo image_path('icons/change_type_add.png');?>" border="0" />&nbsp;Add new SCM</a>
  </td>

  <td>
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
  </td>
</tr>
</table>