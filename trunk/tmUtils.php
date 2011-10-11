<?php
//////////////////////////////////////////////////////////////////////////////////////////////
// ***IMPORTANT*** Make sure that database is changed if copying code from development to live
//////////////////////////////////////////////////////////////////////////////////////////////
require_once "environment.php";

$dbgFile=0;
$numWeeks = 7;
$manHours = 7;
$projTableAttr;
$repStartDate=0;
$startOfWeek=0;
$projTable;
$showAsWeeks=true;
$filePath="c:\projectmatrix.csv";
$showHoliday = true;
$byDeveloper=false;

class Holidays
{
private $HolidayArr = array();

public function __construct($StartDate)
  {
  global $sqlSess;
  global $numWeeks;
  
  $startWeek = date('W',$StartDate);
  
  if ($sqlSess)
    {
    $sql = "select userid from users order by userid";
    
    $devRes = mysql_query ($sql, $sqlSess);
    if ($devRes)
      {
      $devRow = mysql_fetch_array($devRes);
      
      while ($devRow)
        {
        $week = $startWeek;
        
        // Initialise the holiday array to show each developer in for the full time
        for ($i=1;$i<=($numWeeks*2);$i++)
          {
          $this->HolidayArr[$devRow['userid']][$week] = 'I';
          $week++;
          }
        $devRow = mysql_fetch_array($devRes);
        }
    
      $sql = "select developerid, startdate, enddate from holiday where enddate > '" . date("Y-m-d",$StartDate) . "' order by developerid, startdate";
      
      debug($sql);
      
      $holRes = mysql_query ($sql, $sqlSess);
          
      $curDev = 0;
      if ($holRes)
        {
        do
          {
          $holRow = mysql_fetch_array($holRes);
          
          if ($holRow)
            {
            $curWeek = date("W",strtotime($holRow['startdate']));
            $startDay = date("N",strtotime($holRow['startdate']));
            $endWeek = date("W",strtotime($holRow['enddate']));
            $endDay = date ('N', strtotime($holRow['enddate']));
            
            // Count as a full week if three or more days out
            if ($startDay > 3) { $curWeek++; }

            while ($curWeek < $endWeek)
              {
              $this->HolidayArr[$holRow['developerid']][$curWeek] = 'H';
              debug("hol: " . $holRow['developerid'] . " - " . $curWeek);
              $curWeek++;
              }
            
            if ($curWeek == $endWeek and $endDay >= 3)
              { $this->HolidayArr[$holRow['developerid']][$curWeek] = 'H'; }
            }
          }
        while ($holRow);
        }
      }
    }  
  }

public function IsHoliday($dev,$week)
  {
  if ($this->HolidayArr[$dev][$week] == "H") { return true; }
  else { return false; }
  }
}

function debug ($str)
{
global $dbgFile;
if ($dbgFile==0) { $dbgFile = fopen("c:\\tmdebug.txt","w"); }
if ($dbgFile) { fwrite ($dbgFile, $str . "\n"); }
} 

function MakeURL($page,$query,$str)
{
return "<a href='$page.php?$query'>$str</a>";
}

function AddWeek($date)
{
return $date + (60*60*24*7);
}

function StartDate()
{
global $numWeeks;
global $projTableAttr;
global $repStartDate;
global $startOfWeek;
global $projTable;
global $showAsWeeks;

$row1 = ",Year";
$row2 = ",Date";
$row3 = ",Week";

$tdAttr="class='de',class='de'";

$lastMon = 0;
$lastYear = 0;

// get the offset to get to Monday
$dayOffset   = date('N') - 1;
$day         = date('d') - $dayOffset; 
$curMon      = date ('m');
$startOfWeek = mktime(12,0,0,$curMon,$day);

$loopDate = $startOfWeek - ((60*60*24*7)*($numWeeks-1));

$repStartDate = $loopDate;
//date ("Y-m-d",$loopDate);
//$d = "Start of Week: " . $startDateStr . "<br>\n";

for ($i=1;$i<=($numWeeks*2);$i++)
  {
  $loopYear = date("Y",$loopDate);
  $loopMon  = date("m",$loopDate);
  
  if ($loopYear == $lastYear)
    { $row1 = $row1 . "," ; }
  elseif (($loopDate >= $startOfWeek and $loopDate < AddWeek($startOfWeek)))
    { $row1 = $row1 . "*," ; }
  else
    { $row1 = $row1 . ",$loopYear"; }
  $row2 = $row2 . "," . date("d",$loopDate) . "/$loopMon";
  $row3 = $row3 . "," . date("W",$loopDate);
  
  if ($loopDate >= $startOfWeek and $loopDate < AddWeek($startOfWeek)) 
    { $tdAttr = $tdAttr . ",class='today'"; }
  else { $tdAttr = $tdAttr . ","; }
  
  //$loopDate = $loopDate + (60*60*24*7);
  
  $loopDate = AddWeek($loopDate);
  $lastYear=$loopYear;
  }

$projTableAttr[] = explode(",",$tdAttr);
$projTableAttr[] = explode(",",$tdAttr);
$projTableAttr[] = explode(",",$tdAttr);

//debug($tdAttr);

$lastYear=$loopYear;
$lastMon=$loopMon;

$d = $row1 . "," . $row2 . "," . $row3;

$projTable[0]=explode(",",$row1);
$projTable[1]=explode(",",$row2);
$projTable[2]=explode(",",$row3);

return $d;
}

function DBConnect()
{
global $sqlSess;
global $env;

$sqlSess = @mysql_connect($env['host'],$env['dbUser'],$env['pw']);

if ($sqlSess)
  {
  mysql_select_db($env['database'],$sqlSess);
  }
}

function ProjectWalker($devID,$fileSave,$projID)
{
global $sqlSess;
global $repStartDate;
global $numWeeks;
global $startOfWeek;
global $projTableAttr;
global $projTable;
global $showAsWeeks;
global $showHoliday;
global $byDeveloper;

$h = new Holidays($repStartDate);

$projRow = 3;
$lastProj=0;

// The current week
$repWeek = date("y",$startOfWeek) . date("W",$startOfWeek);

$rowText="";

if ($sqlSess)
  {
  // Get all the relavant tasks
  // Note tcmax is the most recent task completion estimate - needed to ensure that jobs which are completed earlier
  // than estimate are not included in later reports
  $sql = "select t.taskid, tc.completionid, t.taskname, t.developerid, u.name, u.username, t.createddate t_createddate,
          tc.statusid, tc.completiondate,tc.createddate tc_createddate, p.projectname, p.projectid, tc.commencedate
          from taskcompletion tc, task t, users u, project p, taskcompletion tcmax
          where tc.taskid=t.taskid and t.developerid = u.userid and p.projectid = t.projectid
          and tc.taskid = tcmax.taskid and tcmax.completionid = (select max(completionid) from taskcompletion where taskid=tc.taskid)
          and tc.completiondate >= '" . date ("Y-m-d",$repStartDate) . "'
          and tcmax.completiondate >= '" . date ("Y-m-d",$repStartDate) . "'";
    
  if ($devID != "" ) $sql = $sql . " and username = '" . $devID . "'";
  if ($projID != "" ) $sql = $sql . " and p.projectid = $projID";
  
  if ($byDeveloper) $sql = $sql . " order by u.name, p.projectid, t.createddate, tc.completionid";
  else $sql = $sql . " order by p.projectid, t.createddate, tc.completionid";
  
  debug ($sql);
  
  $taskRes = mysql_query ($sql, $sqlSess);
  
  if ($taskRes)
    {
    $nextRow = mysql_fetch_array($taskRes);
    
    // Loop until all tasks are processed
    while ($nextRow)
      {
      // Create a header row for each project
      if ($nextRow['projectid'] != $lastProj)
        {
        // Project header spans the whole table
        $colSpan=($numWeeks*2)+2;
        $projTableAttr[] = array("class='pr' colspan='$colSpan'");
        
        if ($fileSave) {$projTable[] = array($nextRow['projectname']); }
        else
          {
          // The project name + the edit and timesheet hyperlinks
          $projTable[] = array(MakeURL("matrix", "proj=" .$nextRow['projectid'] , $nextRow['projectname']) . 
            " (" . MakeURL("proj", "proj=" .$nextRow['projectid'] , "Edit project") . ") " .
             "(" . MakeURL("projecttime", "proj=" .$nextRow['projectid'] , "Timesheet") . ")" );
          }
        $lastProj = $nextRow['projectid'];
        $projRow++;
        }
      
      // Alternate color stripes
      if ($projRow % 2 == 0)
        {
        $pclass="proj-e";
        $lclass="late-e";
        $bclass="block-e";
        $sclass="start-e";
        $cclass="comp-e";
        }
      else
        {
        $pclass="proj-o";
        $lclass="late-o";
        $bclass="block-o";
        $sclass="start-o";
        $cclass="comp-o";
        }
      
      // Initialise matrix row for current project. classes for first two cells
      $tdAttr = "class='ind',class='de'";
      if ($fileSave)
        { $rowText= $nextRow['taskname'] . "," . $nextRow['name']; }
      else
        {
        $rowText= "" . MakeURL("task","task=". $nextRow['taskid'],$nextRow['taskname']) . "," 
                . MakeURL("matrix","user=" . $nextRow['username'],$nextRow['name']);
        }
        
      // preload the next row
      $curRow = $nextRow;
      $nextRow = mysql_fetch_array($taskRes);
      
      // Set the week for the cell as being the week of the first week in report
      $cellWeek = date("y",$repStartDate) . date("W",$repStartDate);
        
      // loop for each week in the report
      for ($i=1;$i<=($numWeeks*2);$i++)
        {
        $chClass="";

        // Don't check the next row if at the end of the result set
        if ($nextRow) { $checkNextRow= True; }
        else {$checkNextRow=False;}
        
        // Check the next completion estimate to see if it is for the current project
        // and was created during the current week in the report, If so loop until find
        // the most up to date estimate in the current week.
        // and check again. If not carry on using the currnet estimate.
        while ($checkNextRow)
          {
          if ($nextRow['taskid']==$curRow['taskid'] and $cellWeek >= date("y",strtotime($nextRow['tc_createddate'])) . date("W",strtotime($nextRow['tc_createddate'])))
            {
            $chClass=" change";
        
            $curRow = $nextRow;
            $nextRow = mysql_fetch_array($taskRes);
            if(!$nextRow) { $checkNextRow=false; }
            }
          // drop out of the loop
          else { $checkNextRow=false; }
          }
        
        // Get the started week, completed week and created week of the current completion estimate  
        $compWeek = date("y",strtotime($curRow['completiondate'])) . date("W",strtotime($curRow['completiondate']));
        $createWeek = date("y",strtotime($curRow['tc_createddate'])) . date("W",strtotime($curRow['tc_createddate']));
        $st = strtotime($curRow['commencedate']);
        if ($st > 1) { $startWeek = date("y",$st) . date("W",$st); }
        else {$startWeek = $createWeek;}

        // Is the task active (in prgress or completed, not on hold or blocked)
        if (($curRow['statusid'] == 2) or ($curRow['statusid'] == 5))
          { $taskActive = true;}
        else { $taskActive = false; }  
        
        // Get the cell value (week number or date of estimate depending on display mode)
        // This will be used lower down to populate the cell if required
        if ($showAsWeeks) { $compStr = substr($compWeek,2,2); }
        else { $compStr = date("d",strtotime($curRow['completiondate'])) . "/" .
                          date("m",strtotime($curRow['completiondate'])); }


        // If options are to show out of office, and user is out of office mark as out
        if ($showHoliday and $h->IsHoliday($curRow['developerid'],substr($cellWeek,2,2))) 
          {
          $rowText=$rowText .",Out";
          if ($projRow % 2 == 0) { $tdAttr = $tdAttr . ",class='hol-e'"; }
          else { $tdAttr = $tdAttr . ",class='hol-o'"; }
          }
        else
          {
          // If the completion estimate was created after the current week or the current
          // week is in the future don't display a week number
          if ($cellWeek < $createWeek or $cellWeek > $repWeek)
            {
              {
              $rowText=$rowText .",";
            
              // If the current week is in the future, but inside the scope of the completion
              // estimate mark the cell as being in the project
              if (($cellWeek > $createWeek and $cellWeek <= $compWeek and $taskActive) or 
                 ($cellWeek >= $startWeek and $cellWeek <= $compWeek and !$taskActive)) 
                {
                if ($cellWeek > $createWeek) { $tdAttr = $tdAttr . ",class='$pclass'"; $rowText=$rowText ."*"; }
                else { $tdAttr = $tdAttr . ",class='$sclass'"; }
                }
              else
                {
                // If the current week is in the past, but inside the scope of the task
                // start date, mark the cell as being in the project
                if ($cellWeek < $startWeek or $cellWeek > $repWeek) { $tdAttr = $tdAttr . ","; }
                else $tdAttr = $tdAttr . ",class='$sclass'"; 
                }
              }
            }
            
          // If the current week is after the creation of the estimate but before the 
          // completion date display the week number
          elseif ($cellWeek <= $compWeek)
            {
            $rowText=$rowText ."," . $compStr;

            switch($curRow['statusid'])
              {
              case 1: // Not Started
                if ($cellWeek >= $startWeek)
                  {
                  $cellClass = $sclass;
                  $rowText=$rowText ."N";
                  }
                else
                  {
                  $cellClass = ""; 
                  $rowText=$rowText ."n";
                  }
                break;
              case 2: // In progress
                $cellClass = $pclass;
                break;
              case 5: // completed
                $cellClass = $cclass;
                if ($cellWeek == $compWeek) $rowText=$rowText ."c";
                break;
              case 4: // Blocked
                $cellClass = $bclass;
                $rowText=$rowText ."b";
                break;
              default:
                $cellClass = "";
              }

            if ($cellWeek == $repWeek) $tdAttr = $tdAttr . ",class='$cellClass today$chClass'";  
            else $tdAttr = $tdAttr . ",class='$cellClass$chClass'";  
            }
          // The task was marked as being complete this week - but the completion date is in the past
          elseif ($cellWeek > $compWeek && $cellWeek == $createWeek && $curRow['statusid'] == 5)
          {
            $tdAttr = $tdAttr . ",class='$cclass'";
            $rowText=$rowText ."," . $compStr . "c";
          }
          // The current week is after the completion estimate (and there is no current
          // estimate. mark the cell as late (unless the task is completed!)
          else
            {
            if ($curRow['statusid'] != 5) 
              {
              $rowText=$rowText ."," . $compStr;

              if ($cellWeek == $repWeek) $tdAttr = $tdAttr . ",class='$lclass today'";
              else $tdAttr = $tdAttr . ",class='$lclass'";
              }
            else
              {
              $rowText=$rowText .",";
              if ($cellWeek == $repWeek)
                {$tdAttr = $tdAttr . ",class='today'";}
              else
                {$tdAttr = $tdAttr . ",";}
              }
            }
          }
        //$cellWeek++;
        $nxtDate = strtotime("+$i weeks", $repStartDate);
        $cellWeek = date("y",$nxtDate) . date("W",$nxtDate);
        }
      $projTableAttr[] = explode(",", $tdAttr);
      $projTable[] = explode(",",$rowText);
      $projRow++;      
      }
    }
  }

return $rowText;
}

function MakeDropDown($id, $desc, $table, $where, $order)
{
global $sqlSess;

$dropdown = "";

if ($sqlSess)
  {
  $sql = "select " . $id . ", " . $desc . " from " . $table;
  if ($where) { $sql = $sql . " where " . $where; }
  if ($order) { $sql = $sql . " order by " . $order; }
  else { $sql = $sql . " order by " . $desc; }
  
  //debug ($sql);
  
  $res = mysql_query($sql, $sqlSess);
  
  if ($res)
    {
    do
      {
      $row = mysql_fetch_assoc ($res);
      if ($row)
        { $dropdown[$row[$id]]=$row[$desc]; }
      }
    while ($row);
    }
  }
return $dropdown;
}

function MakeTable($cols,$tables,$where,$order)
{
global $sqlSess;

if ($sqlSess)
  {
  $sql = "select " . $cols. " from " . $tables;
  if ($where) { $sql = $sql . " where " . $where; }
  if ($order) { $sql = $sql . " order by " . $order; }

  debug ($sql);
  $res = mysql_query($sql, $sqlSess);
  
  if ($res)
    {
    do
      {
      $row = mysql_fetch_row ($res);
      if ($row)
        {
        if (!isset($table))
          { $table = $row; }
        else
          { $table = array_merge($table,$row); }
        }
      }
    while ($row);
    }
  }
return $table;
}

function CreateTask ($p)
{
global $sqlSess;

$id = 0;

// convert date to unix time stamp and back to string to check user input is valid date
$compTimeStamp = strtotime($p['CompDate']);
if ($compTimeStamp )
  {
  $compDate = date("Y-m-d",$compTimeStamp);
  
  if ($sqlSess)
    {
    $sql = "insert into task (ProjectID, TaskName, DeveloperID, Tracked, minutes) values ('" . $p['ProjectID']
         . "', '" . $p['TaskName'] . "', '" . $p['DevID'] . "', '" . $p['Tracked'] . "', '" . $p['minutes'] . "')";

    //debug ($sql);
    
    $sts = mysql_query($sql, $sqlSess);
    
    if ($sts)
      {
      $today = date ("Y-m-d H:i:s");
      $id = mysql_insert_id($sqlSess);
      
      if ($p['DevID']>0 && $p['Tracked']=='Y')
        {
        $sql = "insert into taskcompletion (CompletionDate, StatusID, Comment, TaskID, CommenceDate, CreatedDate) values ('"
               . $compDate  . "', '" . $p['Status'] . "', '" . $p['Comment'] . "', " . $id . ",'" . $p['StartDate'] . "',
               '$today')";
        
        debug ($sql);
        
        $sts = mysql_query($sql, $sqlSess);
        }
      }
    }
  }
else
  {
  $id = -1;
  }
return $id;
}

function CreateHoliday ($p)
{
global $sqlSess;

$id = 0;

// convert date to unix time stamp and back to string to check user input is valid date
$compTimeStamp = strtotime($p['EndDate']);
if ($compTimeStamp )
  {
  $compDate = date("Y-m-d",$compTimeStamp);
  
  if ($sqlSess)
    {
    $sql = "insert into holiday (DeveloperID, StartDate, EndDate) values ('" . $p['DevID']
         . "', '" . $p['StartDate'] . "', '" . $p['EndDate'] . "')";

    debug ($sql);
    
    if (mysql_query($sql, $sqlSess)) { $id = mysql_insert_id($sqlSess); }
    }
  }
else
  {
  $id = -1;
  }
return $id;
}

function CreateProj($p)
{
global $sqlSess;

$sts=false;

if ($sqlSess)
  {
  $sql = "insert into project (ProjectName, Live) values ('" . $p['ProjectName'] . "','" 
  . $p['Status'] . "')";
  
  debug($sql);
  
  $sts = mysql_query($sql, $sqlSess);
  }
return $sts;
}

function UpdateProj($p)
{
global $sqlSess;

$sts = false;

if ($sqlSess)
  {
  $sql = "update project set projectname = '" . $p['ProjectName'] . "', live = '"
  . $p['Status'] . "' where projectid = " . $p['ProjectID'];
  
  debug($sql);

  $sts = mysql_query($sql, $sqlSess);
  }
return $sts;
}

function UpdateHoliday($p)
{
global $sqlSess;

$sts = false;

if ($sqlSess)
  {
  $sql = "update holiday set startdate = '" . $p['StartDate'] . "', enddate = '"
  . $p['EndDate'] . "' where holidayid = " . $p['HolidayID'];
  
  debug($sql);

  $sts = mysql_query($sql, $sqlSess);
  }
return $sts;
}

function UpdateTask ($p, $s)
{
global $sqlSess;

// convert date to unix time stamp and back to string to check user input is valid date
$compTimeStamp = strtotime($p['CompDate']);
if ($compTimeStamp )
  {
  $compDate = date("Y-m-d",$compTimeStamp);

  if ($sqlSess)
    {
    $sql = "update task set TaskName = '" . $p['TaskName'] . "', ProjectID = '" . $p['ProjectID'] . "', DeveloperID = '" . $p['DevID'] .
    "', tracked = '" . $p['Tracked'] . "', minutes ='" . $p['minutes'] . "' where taskid = " . $p['TaskID'];
    //debug ($sql);
    
    $sts = mysql_query($sql, $sqlSess);
    
    debug ( $s['Status'] . ":" . $p['Status'] . ":" . 
                  $s['CompDate']   . ":" .  $p['CompDate'] . ":" . 
                  $s['StartDate']  . ":" .  $p['StartDate'] . ":" . 
                  $s['Comment']    . ":" .  $p['Comment'] );
    
    if ($sts && ( $s['Status']     != $p['Status'] ||
                  $s['CompDate']   != $p['CompDate'] ||
                  $s['StartDate']  != $p['StartDate'] ||
                  $s['Comment']    != $p['Comment'] ) && $p['Tracked'] == 'Y')
      {
      $today = date ("Y-m-d H:i:s");
      $sql = "insert into taskcompletion (CompletionDate, StatusID, Comment, TaskID, CommenceDate, CreatedDate) values ('"
             . $compDate  . "', '" . $p['Status'] . "', '" . $p['Comment'] . "', " . $p['TaskID'] . ",'" . $p['StartDate'] . "',
             '$today')";
      
      //debug ($sql);
      
      $sts = mysql_query($sql, $sqlSess);
      }
    }
  }
return ($sts);
}

function arraywalk ($arr)
{
$i=1;

//debug("ArrayWalk");

foreach ($arr as $av)
  {
  $j=1;
  
  //debug("Row: $i");
  
  foreach ($arr[$i] as $iv)
    {
    debug($i . ": " . $j . ": ". $iv);
    $j++;
    }
  $i++;
  }
}

function GetUser($userName)
{
global $sqlSess;

$user[1] = "";

if ($sqlSess)
  {
  $res = mysql_query("select userid, name from users where username = \"$userName\"", $sqlSess);
  
  debug ($res);
  
  if ($res)
    {
    $row = mysql_fetch_array($res);
    
    if ($row)
      {
      $user[1] = $row['name'];
      $user[2] = $row['userid'];
      }
    }
  }
return $user;
}

function GetPrefs()
{
global $numWeeks;
global $manHours;
global $showAsWeeks;
global $filePath;
global $showHoliday;
global $byDeveloper;
global $startHour;
global $startMin;

if (isset($_COOKIE['NumWeeks']))
  { $numWeeks = $_COOKIE['NumWeeks']/2; }
if (isset($_COOKIE['ManHours']))
  { $manHours = $_COOKIE['ManHours']; }
if (isset($_COOKIE['ShowAsWeeks']))
  {
  if ($_COOKIE['ShowAsWeeks'] == "Y") { $showAsWeeks = TRUE; }
  else { $showAsWeeks = FALSE; }
  }
if (isset($_COOKIE['FilePath']))
  { $filePath = str_replace("\\\\", "\\", $_COOKIE['FilePath']); }
if (isset($_COOKIE['ShowHoliday']))
  {
  if ($_COOKIE['ShowHoliday']=="Y") { $showHoliday=TRUE; }
  else { $showHoliday=FALSE; }
  }
if (isset($_COOKIE['DevSort']))
  {
  if ($_COOKIE['DevSort']=="Y") { $byDeveloper=TRUE; }
  else { $byDeveloper=FALSE; }
  }

if (isset($_COOKIE['startHour']))
  { $startHour = $_COOKIE['startHour']; }
else $startHour = 9;
if (isset($_COOKIE['startMin']))
  { $startMin = $_COOKIE['startMin']; }
else $startMin = 0;
}

function SaveCSV($fname)
{
global $numWeeks;
global $projTable;
global $track_errors;
global $php_errormsg;

ini_set("track_errors", true);
$holdWeeks = $numWeeks;

$fname = str_replace("\\", "\\\\", $fname);
debug ($fname);
$csvFile = @fopen($fname,"w");
if ($csvFile)
  {
  $numWeeks = 16;

  $dates = StartDate();
  ProjectWalker("", true, "");
  
  foreach($projTable as $projRow)
    {
    $str = implode(",",$projRow);
    fwrite ($csvFile, $str . "\n");
    }
    
  fclose($csvFile);
  $status = "ok";
  }
else
  {
  if (strpos($php_errormsg, "Permission denied")===false) 
    {
    // errormessages always say "failed to open stream: "
    $statArr = explode("stream: ", $php_errormsg);
    $status = $statArr[1];
    }
  else
    { $status = "File may be in use (eg by excel)"; }
  }
$numWeeks = $holdWeeks;
ini_set("track_errors", false);

return $status;
}

function MakeCalendar()
{
$startMon = date('n'); // Month - no leading zero
$loopMon = $startMon;
$curMon = 0;
$curYear = date('Y'); // Four digit year

$endMon = $startMon+4;
$endYear = $curYear;

if ($endMon > 12) 
  {
  $endMon = $endMon -12;
  $endYear++;
  }

$today = mktime (0,0,0);

$loopDate = strtotime("$curYear-$startMon-01");
$endLoop = strtotime("$endYear-$endMon-01");
$startDay = date('w', $loopDate); // day 0 = Sun

$loopDay = 0;
$week = "";

echo "<table><tr valign='top'>";

while ($loopDate < $endLoop)
  {
  $curDay = date('w', $loopDate); // day 0 = Sun
  
  if ($loopMon != $curMon)
    {
    if ($curMon != 0)
      {
      while ($loopDay < 7)
        {
        echo "<td></td>\n";
        $loopDay++;
        }
      $loopDay = 0;
      echo "</tr></table></td>";
      }
    
    $curMon = $loopMon;

    echo "<td><table><tr><th colspan=7>" . date('F',$loopDate) . " " . date('Y',$loopDate) ."</th></tr>\n";

    echo "<tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr>\n";
    if ($curDay != $loopDay)
      {
      echo "<tr>";

      while ($curDay != $loopDay)
        {
        echo "<td></td>";
        $loopDay++;
        
        if ($loopDay > 6) $loopDay = 0;
        }
      }
    }
  
  if ($loopDay == 7)
    {
    echo "</tr>\n";
    }
    
  if ($loopDay < 7)
    {
    if ($loopDate == $today) echo "<td class='today'>" . date("d",$loopDate) . "</td>";
    else echo "<td>" . date("d",$loopDate) . "</td>";
    }
  else
    {
    if ($loopDate == $today) echo "<tr><td class='pr'>" . date("d",$loopDate) . "</td>";
    else echo "<tr><td>" . date("d",$loopDate) . "</td>";
    $loopDay = 0;
    }
  
  $loopDate = $loopDate + 60*60*24;
  $loopMon = date('n',$loopDate);
  $loopDay++; 
  }
while ($loopDay < 7)
  {
  echo "<td></td>\n";
  $loopDay++;
  }
echo "</tr></table></td>";
echo "</tr></table>\n";
}

function buildList($sql)
{
global $sqlSess;

$res = mysql_query($sql,$sqlSess);

if($res)
  {
  $row=mysql_fetch_array($res);
  
  if (mysql_num_rows($res)>0)
    {
    while ($row)
      {
      $ret[] = $row;
      $row=mysql_fetch_array($res);
      }
    }
  else
    {
    $ret[0]=false;
    $ret[1]="No rows returned";
    }
  }
else
  {
  $ret[0]=false;
  $ret[1] = mysql_error($sqlSess);
  }
  
return $ret;
}

function minsToDays($mins,$dayHours)
{
$days = (int)($mins/($dayHours*60));
$mins = (int)($mins %($dayHours*60));
$hours =(int)($mins/60);
$mins = (int)($mins%60);

return sprintf('%d:%02d:%02d', $days,$hours,$mins);
}

function minsToHours($params, &$smarty=null)
{
if ($params['mins']==0) return "";
else return sprintf('%d:%02d', abs((int)$params['mins']/60), abs((int)$params['mins']%60)); 
}

function strToMin($inMin)
{
$minArr = preg_split("/[,.:;-]/",$inMin);
if (count($minArr)==2) $minutes = $minArr[0]*60 + $minArr[1];
else $minutes = $minArr[0];

return $minutes;
}

function TaskArray($start,$end,$dev)
{
global $sqlSess;

$ret = array();

if ($sqlSess)
  {
  $res = mysql_query ( "select distinct tk.projectid, tk.taskid, tk.taskname
                        from time tm, task tk
                        where tm.taskid = tk.taskid
                        and tm.developerid=$dev
                        and tm.date>= '$start'
                        and tm.date < '$end'
                        order by taskname", $sqlSess);
  if ($res)
    {
    $numRows = mysql_num_rows($res);
    
    for ($i=0; $i<$numRows; $i++)
      {
      $row=mysql_fetch_array($res);
      $ret[$row['taskid']]['taskname'] = $row['taskname'];
      $ret[$row['taskid']]['projectid'] = $row['projectid'];
      }
    }
  else $ret = mysql_error($sqlSess);
  }
return $ret;
}

function TotalsArray($start,$end,$dev)
{
global $sqlSess;

$ret = array();

if ($sqlSess)
  { 
  $res = mysql_query ( "select date_format(tm.date, '%e') day, tk.taskid, sum(tm.minutes) minutes
from time tm, task tk
where tm.taskid = tk.taskid
                        and tm.developerid=$dev
                        and tm.date>= '$start'
                        and tm.date < '$end'
                        group by  tm.date, tk.taskid", $sqlSess);
  if ($res)
    {
    $numRows = mysql_num_rows($res);
    
    for ($i=0; $i<$numRows; $i++)
      {
      $row=mysql_fetch_array($res);
      $ret[$row['taskid']][$row['day']] = $row['minutes'];
      $ret[$row['taskid']]['total'] += $row['minutes'];
      $ret['day'][$row['day']] += $row['minutes'];
      $ret['day']['total'] += $row['minutes'];
      }
    }
  else $ret = mysql_error($sqlSess);
  }
return $ret;
}

?>