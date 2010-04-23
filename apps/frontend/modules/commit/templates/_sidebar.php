<strong>Commits</strong>
<form method="get" action="<?php echo url_for('commit/index'); ?>">
<table width="100%" class="form">
  <tr><th>Search on</th><td>
    <select name="type">
      <option value="all">All</option>
      <option value="user" <?php if ($type === 'user'): ?>selected="selected"<?php endif;?>>User</option>
      <option value="message" <?php if ($type === 'message'): ?>selected="selected"<?php endif;?>>Message</option>
      <option value="file" <?php if ($type === 'file'): ?>selected="selected"<?php endif;?>>File</option>
    </select>
  </td></tr>
  <tr><th>with</th><td><input name="param" size="10" value="<?php echo $sf_data->getRaw('param');?>"/></td></tr>
  <tr><th>In period</th><td>
    <select name="period">
      <option value="all">All</option>
      <option value="week" <?php if ($period === 'week'): ?>selected="selected"<?php endif;?>>Week</option>
    </select>
  </td></tr>
  <tr><td colspan="2"><a href="<?php echo url_for('@homepage');?>">Reset</a> <input type="submit" value="Search"/></td></tr>
</table>
</form>
