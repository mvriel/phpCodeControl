    <?php if ($pager->haveToPaginate()): ?>
      <div class="pagination">
        <a href="<?php echo url_for('commit/index') ?>?page=1">
          <img src="<?php echo image_path('icons/first.png');?>" alt="First page" />
        </a>

        <a href="<?php echo url_for('commit/index') ?>?page=<?php echo $pager->getPreviousPage() ?>">
          <img src="<?php echo image_path('icons/previous.png');?>" alt="Previous page" title="Previous page"/>
        </a>

        <?php foreach ($pager->getLinks() as $page): ?>
          <?php if ($page == $pager->getPage()): ?>
            <span class="page current-page"><?php echo $page ?></span>
          <?php else: ?>
            <span class="page"><a href="<?php echo url_for('commit/index') ?>?page=<?php echo $page ?>"><?php echo $page ?></a></span>
          <?php endif; ?>
        <?php endforeach; ?>

        <a href="<?php echo url_for('commit/index') ?>?page=<?php echo $pager->getNextPage() ?>">
          <img src="<?php echo image_path('icons/next.png');?>" alt="Next page" title="Next page" />
        </a>

        <a href="<?php echo url_for('commit/index') ?>?page=<?php echo $pager->getLastPage() ?>">
          <img src="<?php echo image_path('icons/last.png');?>" alt="Last page" title="Last page" />
        </a>
      </div>
    <?php endif; ?>

    <div class="pagination_desc">
      <strong><?php echo count($pager) ?></strong> commits

      <?php if ($pager->haveToPaginate()): ?>
        on <strong><?php echo $pager->getLastPage() ?></strong> pages
      <?php endif; ?>
    </div>

    <table cellspacing="0" cellpadding="5">
        <tbody>
        <?php $prev_date = null; ?>
        <?php foreach ($pager->getResults() as $commit): ?>
        <?php if (date('Y-m-d', strtotime($commit->getTimestamp())) !== $prev_date): ?>
        <?php $prev_date = date('Y-m-d', strtotime($commit->getTimestamp())); ?>
        </tbody>
        <thead style="background-color: #f4f4ee;">
        <tr><td colspan="5" style="border-top: 1px solid silver;"><h1><?php echo format_date($commit->getTimestamp(), 'P'); ?> (<?php echo Doctrine::getTable('Commit')->countInPeriod($prev_date.' 00:00', $prev_date.' 23:59'); ?>)</h1></td></tr>
        <tr>
          <th>Revision</th>
          <th>Author</th>
          <th>Time</th>
          <th>Chg</th>
          <th>Message</th>
        </tr>
        </thead>
        <tbody>
        <?php endif; ?>
        <tr>
          <td><a href="<?php echo url_for('commit/show?id='.$commit->getId()) ?>"><?php echo $commit->getId() ?></a></td>
          <td><a href="<?php echo url_for('commit/report?type=user&period=all&param='.$commit->getAuthor()) ?>"><?php echo $commit->getAuthor() ?></a></td>
          <td style="white-space: nowrap;"><?php echo format_date($commit->getTimestamp(), 't') ?></td>
          <td style="border-right: 1px solid silver; text-align: right"><?php echo $commit->getFileChange()->count() ?></td>
          <td><a href="<?php echo url_for('commit/show?id='.$commit->getId()) ?>"><?php echo $commit->getMessage() ?></a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
