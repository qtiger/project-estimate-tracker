<?php
require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "Subtask List";
  public $post;
  public $taskRows = 0;
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  public $showlist=false;
  }
$g = new gFormVar();
DBConnect();

if ($sqlSess)
  {
  $g->devID = $_COOKIE['userno'];
  $g->user = $_COOKIE['userfullname'];
  
  $g->months=1;
  if($_SERVER['QUERY_STRING'])
    {
    parse_str($_SERVER['QUERY_STRING']);
    if (isset($months)) $g->months = $months;
    }
  $res = mysql_query('select distinct tk.taskname, tm.description from task tk, time tm
where tk.taskid = tm.taskid and tm.developerid = ' . $g->devID .
' and tm.date > date_sub(curdate(),interval ' . $g->months .' month)
and tm.description != ""
order by tk.taskname, tm.description');

  if ($res)
    {
    $row = mysql_fetch_array($res);
    
    while ($row)
      {
      $g->subTaskList[] = $row;
      $row = mysql_fetch_array($res);
      $g->showlist=true;
      }
    }
  else $g->statusMessage= "SQL Error";
  
  $g->subTaskRows = count($g->subTaskList);
  }
$tmpl->assign('g',$g);
$tmpl->display('subtask.tpl');
?>
