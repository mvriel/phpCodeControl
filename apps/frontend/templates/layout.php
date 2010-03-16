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
    <div style="height: 79px; background: transparent url('/sfJqueryReloadedPlugin/css/ui-lightness/images/ui-bg_gloss-wave_35_f6a828_500x100.png') repeat-x scroll left top;"></div>
    <ul class="menu">
      <li><a href="<?php echo url_for('@homepage');?>">Home</a></li>
    </ul>
    <?php echo $sf_content ?>
  </body>
</html>
