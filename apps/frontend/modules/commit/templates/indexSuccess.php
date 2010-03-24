<?php use_helper('Date'); ?>
<table class="full-page" cellpadding="5" cellspacing="0">
<tr>
  <td class="left-sidebar">
    <?php include_partial('sidebar', array('type' => $sf_request->getParameter('type'),
      'param' => $sf_request->getParameter('param'), 'period' => $sf_request->getParameter('period'))); ?>
  </td>
  <td>

<div class="tabs">
  <ul>
    <li><a href="#tabs-1">List</a></li>
    <li><a href="#tabs-2">Charts</a></li>
    <?php if($sf_request->getParameter('type') == 'user'): ?>
    <li><a href="#tabs-3">Activity</a></li>
    <?php endif; ?>
  </ul>
  <div id="tabs-1">
    <?php include_partial('list', array('pager' => $pager)); ?>
  </div>
  <div id="tabs-2">
    <?php stOfc::createChart(350, 250, '@chart_author_pie?scm_id='.$sf_user->getSelectedScmId(), false); ?>
    <?php stOfc::createChart(350, 250, '@chart_author_week_pie?scm_id='.$sf_user->getSelectedScmId(), false); ?>
  </div>

  <?php if($sf_request->getParameter('type') == 'user'): ?>
  <div id="tabs-3">
    <?php stOfc::createChart(700, 250, '@chart_author_activity_days?scm_id='.$sf_user->getSelectedScmId().'&param='.$sf_request->getParameter('param'), false); ?>
    <?php stOfc::createChart(700, 250, '@chart_author_activity_hours?scm_id='.$sf_user->getSelectedScmId().'&param='.$sf_request->getParameter('param'), false); ?>
  </div>
  <?php endif; ?>
</div>

  </td>
</tr>
</table>