<?php
if(!isset($_SESSION))
  session_start();

require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "Task";
  public $status;
  public $projid;
  public $taskid;
  public $post;
  public $trAttr;
  public $taskDetails = "New Task";
  public $formAction = "Insert";
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }
$g = new gFormVar();  
$g->post['Tracked'] = "Y";

$g->yesNo = array("Y"=>"Yes","N"=>"No", "A"=>"Archived");

DBConnect();

if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);
  if (isset($task))
    {
    $g->taskDetails = "$task";
    
    if ($sqlSess)
      {
      $sql = "select t.ProjectID, t.TaskName, t.developerid, DATE_FORMAT(tc.completiondate,'%Y-%m-%d') CompDate,
              tc.statusid, tc.Comment, DATE_FORMAT(t.createddate,'%Y-%m-%d') CreatedDate,
              DATE_FORMAT(tc.CommenceDate,'%Y-%m-%d') StartDate, t.Tracked, t.minutes
              from task t left join taskcompletion tc on t.taskid = tc.taskid 
              where t.taskid=$task
              order by tc.createddate desc, tc.completiondate desc limit 1";
      $taskRes = mysql_query($sql,$sqlSess);
      
      if ($taskRes)
        {
        $taskRow=mysql_fetch_array($taskRes);
        
        if ($taskRow)
          {
          $g->formAction = "Update";
          $g->dev       = $taskRow['developerid'];
          $g->status    = $taskRow['statusid'];
          $g->taskid    = $task;
          $g->compDate  = $taskRow['CompDate'];
          $g->startDate = $taskRow['StartDate'];
          $g->post      = $taskRow;
          $g->proj      = $taskRow['ProjectID'];
          $g->comment   = $taskRow['Comment'];
          $g->minutes   = $taskRow['minutes'];
          $g->taskDetails = $g->taskDetails . " - " . $taskRow['TaskName'];
          }
        }
      }
    }
  }
else
  {
  if (isset($_COOKIE['userno']))
    { $g->dev = $_COOKIE['userno'];}
  }
    
if ($_POST['TaskAction'])
  {
  // check completion date
  $cdate = strtotime($_POST['CompDate']);
  $tdate = strtotime(date("Y-m-d"));
  
  $_POST['minutes'] = strToMin($_POST['minutes']);
  $g->post = $_POST;
  
  // Do not allow completion estimates to be in the past. However, allow actual commpletion date to be in the past
  if ($cdate < $tdate && $_POST['Status'] !=5)
    {
    $g->statusMessage = "Completion estimate is in the past";
    $g->formAction = $_POST['TaskAction'];
    $g->compDate = $_POST['CompDate'];
    $g->startDate = $_POST['StartDate'];
    $g->minutes = $_POST['minutes'];
    }
  else
    {  
    if ($_POST['TaskAction'] == "Insert")
      {
      $g->taskid = CreateTask($_POST);
      $g->post['CreatedDate'] = date("Y-m-d");
      if ($g->taskid >=1 )
        {
        $g->statusMessage = "Task Inserted";
        $g->formAction = "Update";
        }
      else
        {
        if ($g->taskid == -1) { $g->statusMessage = "Completion date does not appear to be a date"; }
        else { $g->statusMessage = "Insert Failed"; }
        }
      }
    elseif ($_POST['TaskAction'] == "Update") 
      {
      if (UpdateTask($_POST, $_SESSION)) { $g->statusMessage = "Task Updated"; }
      else { $g->statusMessage = "Update Failed - check completion date is a valid date";}
      }
  //  else $g->name = "Edit Task";
    }    
  if ($_POST['ProjectID'])
    {
    $g->proj = $_POST['ProjectID'];
    }
  if ($_POST['DevID']) {$g->dev=$_POST['DevID']; }
  if ($_POST['Status']) {$g->status=$_POST['Status']; }
  if ($_POST['TaskID'])
    {
    $g->taskid = $_POST['TaskID'];
    $g->taskDetails = $_POST['TaskID'] . " - " . $_POST['TaskName'];
    }
  }

$_SESSION['Status']     = $g->status;
$_SESSION['CompDate']   = $g->compDate;
$_SESSION['StartDate']  = $g->startDate;
$_SESSION['Comment']    = $g->comment;

$where ="";

if ($g->status> 1)
  {
  $where = "statusid > 1";
  }

//$g->projList=GetProjects();
$g->projList = MakeDropDown("projectid","projectname","project","live='L'","projectname");
$g->devList  = MakeDropDown("userid","name","users",null,"name");
$g->statList = MakeDropDown("statusid","status","status",$where,"statusid");
if (!isset($g->compDate)) { $g->compDate = date("Y-m-d"); }
if (!isset($g->startDate)) { $g->startDate = date("Y-m-d"); }

$g->trAttr = array("class='odd'","class='even'");

//select date_format(createddate,"%d-%m-%Y"), comment, date_format(createddate,"%d-%m-%Y"), status
//from taskcompletion, status where taskcompletion.statusid = status.statusid and taskid = 11

if ($g->taskid>=1)
  {
  $g->compCols = "Date Entered, Comment, Estimated Start, Estimated Completion, Status";
  $g->compDetails = MakeTable("date_format(createddate,\"%d-%m-%Y\"), comment, date_format(commencedate,\"%d-%m-%Y\"),
   date_format(completiondate,\"%d-%m-%Y\"), status", 
  "taskcompletion, status", "taskcompletion.statusid = status.statusid and taskid =" . $g->taskid,
  "createddate desc");
  }

$tmpl->register_function("minsToHours","minsToHours");
$tmpl->assign('g',$g);
$tmpl->display('task.tpl');
?>