<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('scm/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

<?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

  <table>
    <tfoot>
      <tr><td colspan="2" align="right"><input type="submit" value="Save"/></td></tr>
      <tr><td colspan="2"><?php echo $form->renderHiddenFields(false) ?></td></tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?>*</th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['scm_type_id']->renderLabel() ?>*</th>
        <td>
          <?php echo $form['scm_type_id']->renderError() ?>
          <?php echo $form['scm_type_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host']->renderLabel() ?>*</th>
        <td>
          <?php echo $form['host']->renderError() ?>
          <?php echo $form['host'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['port']->renderLabel() ?></th>
        <td>
          <?php echo $form['port']->renderError() ?>
          <?php echo $form['port'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['username']->renderLabel() ?></th>
        <td>
          <?php echo $form['username']->renderError() ?>
          <?php echo $form['username'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['password']->renderLabel() ?></th>
        <td>
          <?php echo $form['password']->renderError() ?>
          <?php echo $form['password'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['path']->renderLabel() ?></th>
        <td>
          <?php echo $form['path']->renderError() ?>
          <?php echo $form['path'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>

<?php slot('sidebar'); ?>
<div class="section">
  <h1>Actions</h1>
  <a href="<?php echo url_for('scm/index') ?>"><img src="<?php echo image_path('icons/previous.png');?>" border="0" height="12" style="margin-right: 4px;"/>&nbsp;Back to list</a><br />
  <?php if (!$form->getObject()->isNew()): ?>
  <?php echo link_to('<img src="'.image_path("icons/change_type_delete.png").'" border="0" /> Delete', 'scm/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?><br />
  <?php endif; ?>
</div>
<?php end_slot(); ?>

