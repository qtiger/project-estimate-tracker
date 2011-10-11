<?php
require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "Timesheet";
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }
$g = new gFormVar();  

DBConnect();

GetPrefs();

parse_str($_SERVER['QUERY_STRING'], $query);

if (isset($_COOKIE['userno']))
  {
  $g->devID = $_COOKIE['userno'];
  
  if (array_key_exists('date',$query)) $g->date = $query['date'];
  else $g->date = date("Y-m-d");
  
  $tl1 = buildList("select t.taskid, taskname, p.projectid, p.projectname, 0 type, t.developerid from task t
  left join taskcompletion tc on (t.taskid = tc.taskid
  and tc.completionid = (select max(completionid) from taskcompletion where taskid = t.taskid))
  left join project p on t.projectid = p.projectid
  where t.developerid = " . $g->devID . " and tc.statusid < 5
  order by p.projectname, t.taskname");

  $tl2 = buildList("select t.taskid, t.taskname, p.projectid, p.projectname, 1 type, t.developerid
  from task t, project p where t.projectid = p.projectid and (developerid=0 or developerid = " . $g->devID . ")
  and t.tracked='N' order by t.developerid desc, p.projectname, t.taskname");
  
  if ($tl1[0] && $tl2[0]) $g->taskList = array_merge($tl1, $tl2);
  else if ($tl1[0]) $g->taskList = $tl1;
  else if ($tl2[0]) $g->taskList = $tl2;
  
  include "timesheetsub.php";
  
  $g->name = $_COOKIE['userfullname'];

  $g->startHour = $startHour;
  $g->startMin = $startMin;

  $tmpl->assign('g',$g);
  $tmpl->display('timesheet.tpl');
  }