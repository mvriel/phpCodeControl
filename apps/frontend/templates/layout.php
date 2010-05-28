<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript">
      jQuery(document).ready(function()
      {
        jQuery(".tabs").tabs();
      });
    </script>
  </head>
  <body>

  <div id="page">

    <div id="top">
      <img src="/images/banner-left.png" class="left" align="top"/>

      <form method="get" action="<?php echo url_for('scm/select'); ?>">
        <select name="scm_id">
          <?php foreach(Doctrine::getTable('Scm')->findAll() as $scm): ?>
          <option value="<?php echo $scm->getId(); ?>" <?php if ($scm->getId() == $sf_user->getSelectedScmId()):?>selected="selected"<?php endif; ?>><?php echo $scm->getName(); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="Select" />
      </form>
    </div>

    <div id="menu">
      <a href="<?php echo url_for('commit/index');?>" class="menuitem">Commits</a>
      <a href="<?php echo url_for('scm/index');?>" class="menuitem">SCMs</a>
    </div>

    <a href="<?php echo url_for('@homepage');?>"><div id="banner"></div></a>

    <?php if ($sf_user->hasFlash('error')): ?>
    <div class="error-box">
      <?php echo $sf_user->getFlash('error'); ?>
    </div>
    <?php endif; ?>

    <div id="sidebar">
      <?php echo get_slot('sidebar');?>
    </div>

    <div id="content">
      <?php echo $sf_content ?>
    </div>

    <div id="footer">
      phpCodeControl version <?php echo sfConfig::get('app_version');?> |
      Icons are part of the
      <a href="http://www.everaldo.com">Crystal Project</a> and copyright of
      <a href="mailto:everaldo@everaldo.com">Everaldo Coelho</a>
    </div>

  </body>
</html>
