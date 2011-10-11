<?php
require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "Project";
  public $post;
  public $taskRows = 0;
  public $projDetails = "New project";
  public $formAction = "Insert";
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  public $showlist=false;
  }
$g = new gFormVar();  

DBConnect();

if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);
  if (isset($proj))
    {
    $g->projDetails = "$proj";
    
    if ($sqlSess)
      {
      $sql = "select ProjectID, ProjectName, live, DATE_FORMAT(CreatedDate,'%Y-%m-%d') CreatedDate from project where projectid = $proj" ;
      $projRes = mysql_query($sql,$sqlSess);
      
      if ($projRes)
        {
        $projRow=mysql_fetch_array($projRes);
        
        if ($projRow)
          {
          $g->formAction = "Update";
          $g->status    = $projRow['live'];
          $g->proj      = $proj;
          $g->post      = $projRow;
          
          $g->projDetails = $g->projDetails . " - " . $projRow['ProjectName'];
          }
        }
      }
    }
  }
else
  {
  if ($sqlSess)
    {
    $res = mysql_query("select projectid, projectname, live from project order by live,projectname",$sqlSess);
    
    if ($res)
      {
      $row = mysql_fetch_array($res);
      
      while ($row)
        {
        $g->projList[] = $row;
        $row = mysql_fetch_array($res);
        $g->showlist=true;
        }
      }
    }
  }

if ($_POST['ProjAction'])
  {
  $g->post = $_POST;

  debug($_POST['ProjAction']);

  if ($_POST['ProjAction'] == "Insert")
    {
    $g->projid = CreateProj($_POST);
    $g->post['CreatedDate'] = date("Y-m-d");
    $g->post['Status'] = $_POST['Status'];
    
    if ($g->projid >= 1 )
      {
      $g->statusMessage = "Project Inserted";
      $g->formAction = "Update";
      }
    else { $g->statusMessage = "Insert Failed"; }
    }
  elseif ($_POST['ProjAction'] == "Update") 
    {
    $g->post = $_POST;
    if (UpdateProj($_POST)) 
      {
      $g->statusMessage = "Project Updated";
      $g->formAction = "Update";
      }
    else { $g->statusMessage = "Update Failed";}
    }
  
  if ($_POST['ProjectID'])
    {
    $g->proj = $_POST['ProjectID'];
    $g->formAction = "Update";
    $g->projDetails = $_POST['ProjectID'] . " - " . $_POST['ProjectName'];
    }
  if ($_POST['Status']) {$g->status=$_POST['Status']; }
  }

$g->statList = array("L"=>"Current", "O"=>"Completed");

$g->compCols = "Task, Date Entered, Comment, Estimated Start, Estimated Completion, Status, Who";
$g->compDetails = MakeTable("taskname, date_format(tc.createddate,\"%d-%m-%Y\"), comment, date_format(commencedate,\"%d-%m-%Y\"),
   date_format(completiondate,\"%d-%m-%Y\"), status, u.name", 
  "task t, taskcompletion tc, status s, users u", 
  "t.taskid = tc.taskid and projectid = " . $g->proj ." and tc.statusid = s.statusid
  and t.developerid = u.userid 
  and completionid in (select max(completionid) from taskcompletion group by taskid)",
  "t.taskid");

$g->trAttr = array("class='odd'","class='even'");

$g->taskRows = count($g->compDetails);

$tmpl->assign('g',$g);
$tmpl->display('proj.tpl');
?>