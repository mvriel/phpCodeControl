<?php use_helper('Date'); ?>

<div class="tabs">
  <ul>
    <li><a href="#tabs-1">List</a></li>
    <li><a href="#tabs-2">Charts</a></li>
    <?php if($sf_request->getParameter('type') == 'user'): ?>
    <li><a href="#tabs-3">Activity</a></li>
    <?php endif; ?>
  </ul>

  <div id="tabs-1">
    <p><?php include_partial('list', array('pager' => $pager)); ?></p>
  </div>

  <div id="tabs-2">
    <p>
    <?php stOfc::createChart(350, 250, '@chart_author_pie?scm_id='.$sf_user->getSelectedScmId(), false); ?>
    <?php stOfc::createChart(350, 250, '@chart_author_week_pie?scm_id='.$sf_user->getSelectedScmId(), false); ?>
    </p>
  </div>

  <?php if($sf_request->getParameter('type') == 'user'): ?>
  <div id="tabs-3">
    <p>
      <?php stOfc::createChart(700, 250, '@chart_author_activity_days?scm_id='.$sf_user->getSelectedScmId().'&param='.$sf_request->getParameter('param'), false); ?>
      <?php stOfc::createChart(700, 250, '@chart_author_activity_hours?scm_id='.$sf_user->getSelectedScmId().'&param='.$sf_request->getParameter('param'), false); ?>
    </p>
  </div>
  <?php endif; ?>
</div>

<?php slot('sidebar'); ?>
<div class="section">
  <h1>Search</h1>
  <?php
    include_partial('sidebar', array(
      'type' => $sf_request->getParameter('type'),
      'param' => $sf_request->getParameter('param'),
      'period' => $sf_request->getParameter('period')
    ));
  ?>
</div>
<?php end_slot(); ?>
