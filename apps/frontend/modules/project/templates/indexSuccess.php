<h1>Projects List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Scm</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($projects as $project): ?>
    <tr>
      <td><a href="<?php echo url_for('project/show?id='.$project->getId()) ?>"><?php echo $project->getId() ?></a></td>
      <td><?php echo $project->getName() ?></td>
      <td><?php echo $project->getScmId() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('project/new') ?>">New</a>
