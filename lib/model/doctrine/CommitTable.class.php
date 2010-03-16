<?php

class CommitTable extends Doctrine_Table
{
  public function getListQuery($scm_id)
  {
    $q = Doctrine_Query::create()
      ->orderBy('timestamp DESC')
      ->from('Commit c');
//      ->where('scm_id = ?', $scm_id);

    if (isset($this->period_start))
    {
      $q->where('timestamp >= ?', $this->period_start);
    }
    
    if (isset($this->period_start) && isset($this->period_end))
    {
      $q->andWhere('timestamp <= ?', $this->period_end);
    }
    
    return $q;
  }

  public function getUserQuery($scm_id, $user)
  {
    $q = $this->getListQuery($scm_id);
    $q->andWhere('author=?', $user);

    return $q;
  }

  public function getMessageQuery($scm_id, $message)
  {
    $q = $this->getListQuery($scm_id);
    $q->andWhere('message LIKE ?', '%'.$message.'%');

    return $q;
  }

  public function getFileSearchQuery($scm_id, $file)
  {
    $q = $this->getListQuery($scm_id);
    $q->innerJoin('c.FileChange fc');
    $q->andWhere('fc.file_path LIKE ?', '%'.$file.'%');

    return $q;
  }

  public function countInPeriod($start, $end = null)
  {
    if (!$end)
    {
      $end = $start;
    }
    
    $q = Doctrine_Query::create()
      ->select('count(id) AS count')
      ->from('Commit c')
      ->where('timestamp>=?', $start)
      ->andWhere('timestamp<=?', $end);
    $record = $q->fetchOne();
    return $record->getCount();
  }
}
