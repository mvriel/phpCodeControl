<?php

/**
 * Defines all actions concerning commits.
 *
 * Since commits are automatically created using the phpcc:update task this module does not provide means to
 * create, edit or delete.
 *
 * @package    phpCodeControl
 * @subpackage Commit
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commitActions extends sfActions
{
  /**
   * Shows a list of commits which have been made.
   *
   * The resultset is limited on SCM id to only show commits which actually have a relation to eachother.
   * The list is ordered descending by timestamp.
   *
   * @param sfWebRequest $request
   *
   * @return sfView
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->scm = $this->getUser()->getSelectedScm();
    $scm_id = $this->scm->getId();
    $this->forward404Unless($this->scm);

    // if we have received the parameters to show a report, redirect handling to the reports
    $this->type   = $request->getParameter('type');
    $this->param  = $request->getParameter('param');
    $this->period = $request->getParameter('period');
    if ($this->type && $this->param && $this->period)
    {
      $this->redirect('commit/report?scm='.$scm_id.'&type='.$this->type.'&period='.$this->period.'&param='.$this->param);
    }

    // set up the pager
    $this->pager = new sfDoctrinePager('Commit', 20);
    $this->pager->setQuery(Doctrine::getTable('Commit')->getListQuery($scm_id));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

  /**
   * Displays a specific view containing a report.
   *
   * Reports always specify a:
   * - Type, i.e. All, User, Message, File
   * - Param, an additional free form value which acts as a criteria
   * - Period, The period which is shown
   *
   * @param sfWebRequest $request
   *
   * @return sfView
   */
  public function executeReport(sfWebRequest $request)
  {
    $this->scm = $this->getUser()->getSelectedScm();
    $scm_id = $this->scm->getId();

    // collect all parameters
    $type      = $request->getParameter('type');
    $param     = $request->getParameter('param', '');
    $period    = $request->getParameter('period');
    $page = $request->getParameter('page', 1);

    // the scm, type and period are required
    $this->forward404Unless($this->scm && $type && $period);

    $table = Doctrine::getTable('Commit');

    // determine which period to retrieve
    switch ($period)
    {
      case 'week':
        $period_start = date('Y-m-d 00:00:00', strtotime('last monday'));
        $period_end = date('Y-m-d H:i:s');
        break;
      default:
        $period_start = NULL;
        $period_end = NULL;
        break;
    }

    // determine which query to load and which parameters to use
    switch ($type)
    {
      case 'user':
        $query = $table->getUserQuery($scm_id, $param, $period_start, $period_end);
        break;
      case 'message':
        $query = $table->getMessageQuery($scm_id, $param, $period_start, $period_end);
        break;
      case 'file':
        $query = $table->getFileSearchQuery($scm_id, $param, $period_start, $period_end);
        break;
      case 'all':
      default:
        $query = $table->getListQuery($scm_id, $period_start, $period_end);
        break;
    }

    // initialize pager
    $this->pager = new sfDoctrinePager('Commit', 40);
    $this->pager->setQuery($query);
    $this->pager->setPage($page);
    $this->pager->init();

    // use the index template as nothing new was introduced
    $this->setTemplate('index');
  }

  /**
   * Displays the detailed information about a commit and all associated changes.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->commit = $this->getRoute()->getObject();

    $file_change_id = $request->getParameter('file_change', null);
    $this->selected_change = $file_change_id ? Doctrine::getTable('FileChange')->find($file_change_id) : null;

    if (!$this->selected_change)
    {
      $this->selected_change = $this->commit->getFileChange()->getFirst();
    }
  }

  /**
   * Displays an animated loader.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeLoader(sfWebRequest $request)
  {
  }

  /**
   * Returns the data for a pie chart showing the amount of commits per author.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeChartAuthorPie(sfWebRequest $request)
  {
    $scm_id = $request->getParameter('scm_id');

    $query = Doctrine::getTable('Commit')->createQuery()->
            addWhere('scm_id = ?', $scm_id)->
            addGroupBy('author')->
            addSelect('author, count(Commit.author) as author_count');
    $result = $query->fetchArray();

    $authors = array();
    $values = array();
    foreach($result as $item)
    {
      $authors[] = $item['author'];
      $values[] = $item['author_count'];
    }

    //Creating a stGraph object
    $g = new stGraph();
    $g->title( 'Overall commits per author', '{font-size:18px; color: #18A6FF}' );
    $g->set_tool_tip( '#x_label#: #val# commits' );
    $g->bg_colour = '#eeeeee';
    $g->pie_values($values, $authors);

    //Set the transparency and line colour to separate each slice
    $g->pie(80,'#78B9EC','{font-size: 12px; color: #78B9EC;');
    $g->pie_slice_colours( array('#d01f3c','#356aa0','#c79810') );

    return $this->renderText($g->render());
  }

  /**
   * Returns the data for a pie chart showing the amount of commits per author since monday.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeChartAuthorWeekPie(sfWebRequest $request)
  {
    $scm_id = $request->getParameter('scm_id');

    $query = Doctrine::getTable('Commit')->createQuery()->
            addWhere('timestamp >= ?', date('Y-m-d 00:00:00', strtotime('last monday')))->
            addWhere('scm_id = ?', $scm_id)->
            addGroupBy('author')->
            addSelect('author, count(Commit.author) as author_count');
    $result = $query->fetchArray();

    $authors = array();
    $values = array();
    foreach($result as $item)
    {
      $authors[] = $item['author'];
      $values[] = $item['author_count'];
    }

    //Creating a stGraph object
    $g = new stGraph();

    //set background color
    $g->bg_colour = '#eeeeee';

    //Set the transparency, line colour to separate each slice etc.
    $g->pie(80,'#78B9EC','{font-size: 12px; color: #78B9EC;');

    //array two arrray one containing data while other contaning labels
    $g->pie_values($values, $authors);

    //Set the colour for each slice. Here we are defining three colours
    //while we need 7 colours. So, the same colours will be
    //repeated for the all remaining slices in the same order
    $g->pie_slice_colours( array('#d01f3c','#356aa0','#c79810') );

    //To display value as tool tip
    $g->set_tool_tip( '#x_label#: #val# commits' );

    $g->title( 'Commits since monday per author', '{font-size:18px; color: #18A6FF}' );
    return $this->renderText($g->render());
  }

  /**
   * Returns the data for a bar chart showing the amount of commits per author per day in the week.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeChartAuthorActivityDays(sfWebRequest $request)
  {
    $scm_id = $this->getUser()->getSelectedScmId();

    $username = $request->getParameter('param');
    $query = Doctrine::getTable('Commit')->createQuery()->
            addWhere('scm_id', $scm_id)->
            addWhere('author = ?', $username);
    $result = $query->fetchArray();

    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $values = array(0,0,0,0,0,0,0);
    foreach($result as $item)
    {
      $day = date('N' ,strtotime($item['timestamp']));
      $values[$day-1]++;
    }

    //To create a bar chart we need to create a stBarOutline Object
    $bar = new stBarOutline( 80, '#78B9EC', '#3495FE' );
    $bar->key( 'Commits per day', 10 );

    //Passing the random data to bar chart
    $bar->data = $values;

    //Creating a stGraph object
    $g = new stGraph();
    $g->set_inner_background( '#E3F0FD', '#CBD7E6', 90 );
    $g->x_axis_colour( '#8499A4', '#E4F5FC' );
    $g->y_axis_colour( '#8499A4', '#E4F5FC' );

    //set background color
    $g->bg_colour = '#eeeeee';

    //Set the transparency, line colour to separate each slice etc.
    $g->bar_filled(80,'#78B9EC','#78B9EC','{font-size: 12px; color: #78B9EC;');

    //Pass stBarOutline object i.e. $bar to graph
    $g->data_sets[] = $bar;

    //Setting labels for X-Axis
    $g->set_x_labels($days);

    // To tick the values on x-axis
    // 2 means tick every 2nd value
    $g->set_x_axis_steps( 2 );

    //set maximum value for y-axis
    //we can fix the value as 20, 10 etc.
    //but its better to use max of data
    $g->set_y_max( max($values) );
    $g->y_label_steps( 4 );
    $g->set_y_legend( '# commits', 12, '#18A6FF' );

    //To display value as tool tip
    $g->set_tool_tip( '#x_label#: #val# commits' );

    $g->title( 'Total commits per weekday for '.$username, '{font-size:18px; color: #18A6FF}' );
    return $this->renderText($g->render());
  }

  /**
   * Returns the data for a bar chart showing the amount of commits per author per hour of the day.
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeChartAuthorActivityHours(sfWebRequest $request)
  {
    $scm_id = $this->getUser()->getSelectedScmId();

    $username = $request->getParameter('param');
    $query = Doctrine::getTable('Commit')->createQuery()->
            addWhere('scm_id', $scm_id)->
            addWhere('author = ?', $username);
    $result = $query->fetchArray();

    $hours = range(0, 23);
    $values = array_fill(0, 24, 0);
    foreach($result as $item)
    {
      $hour = (int)date('H', strtotime($item['timestamp']));
      $values[$hour]++;
    }

    //To create a bar chart we need to create a stBarOutline Object
    $bar = new stBarOutline( 80, '#78B9EC', '#3495FE' );
    $bar->key( 'Commits per hour', 10 );

    //Passing the random data to bar chart
    $bar->data = $values;

    //Creating a stGraph object
    $g = new stGraph();
    $g->set_inner_background( '#E3F0FD', '#CBD7E6', 90 );
    $g->x_axis_colour( '#8499A4', '#E4F5FC' );
    $g->y_axis_colour( '#8499A4', '#E4F5FC' );

    //set background color
    $g->bg_colour = '#eeeeee';

    //Set the transparency, line colour to separate each slice etc.
    $g->bar_filled(80,'#78B9EC','#78B9EC','{font-size: 12px; color: #78B9EC;');

    //Pass stBarOutline object i.e. $bar to graph
    $g->data_sets[] = $bar;

    //Setting labels for X-Axis
    $g->set_x_labels($hours);

    // To tick the values on x-axis
    // 2 means tick every 2nd value
    $g->set_x_axis_steps( 2 );

    //set maximum value for y-axis
    //we can fix the value as 20, 10 etc.
    //but its better to use max of data
    $g->set_y_max( max($values) );
    $g->y_label_steps( 4 );
    $g->set_y_legend( '# commits', 12, '#18A6FF' );

    //To display value as tool tip
    $g->set_tool_tip( '#x_label#: #val# commits' );

    $g->title( 'Total commits per hour for '.$username, '{font-size:18px; color: #18A6FF}' );
    return $this->renderText($g->render());
  }

}