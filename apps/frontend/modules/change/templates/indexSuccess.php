<h1>File changes List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Commit</th>
      <th>File path</th>
      <th>Change type</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($file_changes as $file_change): ?>
    <tr>
      <td><a href="<?php echo url_for('change/show?id='.$file_change->getId()) ?>"><?php echo $file_change->getId() ?></a></td>
      <td><?php echo $file_change->getCommitId() ?></td>
      <td><?php echo $file_change->getFilePath() ?></td>
      <td><?php echo $file_change->getChangeType() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('change/new') ?>">New</a>
