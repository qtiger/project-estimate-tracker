<?php
#if(!isset($_SESSION))
#  session_start();

require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name;
  public $status;
  public $projid;
  public $holidayid;
  public $post;
  public $trAttr;
  public $holidayDetails = "Out of Office";
  public $formAction = "Insert";
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Estimate Tracking Database';
  }
$g = new gFormVar();  

DBConnect();

debug("Holiday");
$g->name = $_COOKIE['userfullname'];


if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);
  if (isset($holiday))
    {
    $g->holidayDetails = "$holiday";
    
    if ($sqlSess)
      {
      $sql = "select t.developerid, DATE_FORMAT(EndDate,'%Y-%m-%d') EndDate,
              DATE_FORMAT(createddate,'%Y-%m-%d') CreatedDate,
              DATE_FORMAT(startdate,'%Y-%m-%d') StartDate
              from holiday
              where holidayid=$holiday
              order by tc.createddate desc limit 1";
      $holidayRes = mysql_query($sql,$sqlSess);
      
      if ($holidayRes)
        {
        $holidayRow=mysql_fetch_array($holidayRes);
        
        if ($holidayRow)
          {
          $g->formAction = "Update";
          $g->dev       = $holidayRow['developerid'];
          $g->holidayid = $holiday;
          $g->EndDate   = $holidayRow['EndDate'];
          $g->StartDate = $holidayRow['StartDate'];
          $g->post      = $holidayRow;
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
if ($_POST['HolidayAction'])
  {
  debug("Action");

  // check completion date
  $cdate = strtotime($_POST['EndDate']);
  $tdate = strtotime(date("Y-m-d"));
  
  $g->post = $_POST;
  
  if ($cdate < $tdate)
    {
    debug("End date in past");

    $g->statusMessage = "End date is in the past";
    $g->formAction = $_POST['holidayAction'];
    $g->EndDate = $_POST['EndDate'];
    $g->startDate = $_POST['StartDate'];
    }
  else
    {
    debug("PreAction");
  
    if ($_POST['HolidayAction'] == "Insert")
      {
      debug("Insert");

      $g->holidayid = CreateHoliday($_POST);
      $g->post['CreatedDate'] = date("Y-m-d");
      if ($g->holidayid >=1 )
        {
        debug("Insert worked");

        $g->statusMessage = "Holiday Inserted";
        $g->formAction = "Update";
        }
      else
        {
        debug("Insert failed");

        if ($g->holidayid == -1) { $g->statusMessage = "End date does not appear to be a date"; }
        else { $g->statusMessage = "Insert Failed"; }
        }
      }
    elseif ($_POST['HolidayAction'] == "Update") 
      {
      debug("Update");

      if (UpdateHoliday($_POST)) { $g->statusMessage = "holiday Updated"; }
      else { $g->statusMessage = "Update Failed - check end date is a valid date";}
      }
  //  else $g->name = "Edit holiday";
    }    
  if ($_POST['ProjectID'])
    {
    $g->proj = $_POST['ProjectID'];
    }
  if ($_POST['DevID']) {$g->dev=$_POST['DevID']; }
  if ($_POST['Status']) {$g->status=$_POST['Status']; }
  if ($_POST['HolidayID'])
    {
    $g->holidayid = $_POST['HolidayID'];
    $g->holidayDetails = $_POST['HolidayID'];
    }
  }

$where ="";

if ($g->status> 1)
  {
  $where = "statusid > 1";
  }

//$g->projList=GetProjects();
$g->devList  = MakeDropDown("userid","name","users",null,"name");
if (!isset($g->EndDate)) { $g->EndDate = date("Y-m-d"); }
if (!isset($g->StartDate)) { $g->StartDate = date("Y-m-d"); }

$g->trAttr = array("class='odd'","class='even'");

//select date_format(createddate,"%d-%m-%Y"), comment, date_format(createddate,"%d-%m-%Y"), status
//from holidaycompletion, status where holidaycompletion.statusid = status.statusid and holidayid = 11

//if ($g->holidayid>=1)
//  {
$today = date('Y-m-d');
$g->holCols = "Developer, Start Date, EndDate";
$g->holDetails = MakeTable("name, date_format(startdate,\"%d-%m-%Y\"), date_format(enddate,\"%d-%m-%Y\")", 
 "holiday, users", "holiday.developerid = users.userid and enddate > '$today' and developerid = $g->dev", "startdate desc");
//  }
  
$tmpl->assign('g',$g);
$tmpl->display('holiday.tpl');
?>