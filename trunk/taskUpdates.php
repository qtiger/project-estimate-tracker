<?php
if(!isset($_SESSION))
  session_start();

require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "Recent Task Updates";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }
$g = new gFormVar();  

DBConnect();

$g->compCols = "Project Name, Task Name, Who, Status, Comment, Modified Date";
$g->compDetails = MakeTable("p.projectname, taskname, u.name, s.status, tc.comment, date_format(tc.createddate,\"%d-%m-%Y\")",
   "project p, task t, taskcompletion tc, users u, status s", 
  "p.projectid = t.projectid and t.taskid = tc.taskid and t.developerid = u.userid and tc.statusid = s.statusid
and tc.createddate > (current_date() - interval 1 month)
and t.taskid not in (select taskid from taskcompletion where taskid = t.taskid and statusid = 5) and comment != ''",
  "tc.createddate desc");

if (is_array($g->compDetails)) $g->updates='yes';
else  $g->updates='no';

$tmpl->assign('g',$g);
$tmpl->display('taskupdates.tpl');
?>