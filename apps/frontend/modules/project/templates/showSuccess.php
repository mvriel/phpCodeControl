<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $project->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $project->getName() ?></td>
    </tr>
    <tr>
      <th>Scm:</th>
      <td><?php echo $project->getScmId() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('project/edit?id='.$project->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('project/index') ?>">List</a>
