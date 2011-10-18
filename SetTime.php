<?php
class gFormVar
  {
  public $devID;
  public $date;
}

require ('tmUtils.php');
require('init_smarty.php');
DBConnect();

if ($_SERVER['REQUEST_METHOD']=='POST')
  {
  parse_str($_SERVER['QUERY_STRING'],$query);
  
  $allOk="";
  if (!array_key_exists("action", $query)) $allOk .= "action not set. ";
  if (!array_key_exists("user", $query)) $allOk .= "user not set. ";
  if (!array_key_exists("date", $query)) $allOk .= "date not set. ";
  if (!array_key_exists("ids", $query)) $allOk .= "IDs not set. ";
  if (!array_key_exists("subs", $query)) $allOk .= "Subs not set. ";
  if (!array_key_exists("mins", $query)) $allOk .= "Mins not set.";

  if ($allOk=="")
    {
    $g = new gFormVar(); 
    
    $g->devID = $query['user'];
    $g->date = $query['date'];
    
    $ids = explode("|",$query['ids']);
    $mins = explode ("|",$query['mins']);
    $subs = explode ("|",$query['subs']);

    // Apply all outstanding changes before action
    $i=0;
    foreach ($ids as $id)
    {
      if ($id!="")
        $res = mysql_query("update time set minutes = '" . $mins[$i] . "', description = '" . $subs[$i] . "' where timeid = '$id'", $sqlSess);
      $i++;
    }
    
    // Add new Time Line
    if ($query['action'] == 'add')
      {
      if (!array_key_exists("task", $query)) $allOk .= "task not set. ";
      if (!array_key_exists("minutes", $query)) $allOk .= "minutes not set. ";

      if (array_key_exists("sub", $query)) $sub = $query['sub'];
      else $sub = '';

      if ($allOk=="")
        {
        $sql = "insert into time (taskid,developerid,minutes,date,starttime,description) values (" .
        $query['task'] . "," . $query['user'] . "," . $query['minutes'] .
        ",'". $query['date'] . "',now(), '$sub')";
        
        $res = mysql_query($sql, $sqlSess);
        }
      }
    // Delete Time Line
    else if ($query['action'] == 'delete')
      {
      if (!array_key_exists("timeid", $query)) $allOk .= "timeif not set. ";
      
      if ($allOk=="")
        {
        $res = mysql_query("delete from time where timeid = " . $query['timeid'], $sqlSess);
        }
      }
    // Update current time line to now
    else if ($query['action'] == 'now')
      {
      if (!array_key_exists("timeid", $query)) $allOk .= "timeif not set. ";
      if (!array_key_exists("minutes", $query)) $allOk .= "minutes not set. ";
      
      if ($allOk=="")
        {
        if (array_key_exists("sub", $query)) $sub = $query['sub'];
        else $sub = '';

        if ($query['minutes'] ==0)
          {
          $res = mysql_query("select timediff(now(), starttime) from time where timeid = " . $query['timeid'], $sqlSess);
          
          if ($res)
            {
            $timeArr = mysql_fetch_array($res);
            $time = explode(":",$timeArr[0]);
            
            $mins = ($time[0] * 60) + $time[1];           
            if ($time[2] >=30) $mins++; 
            
            $res = mysql_query("update time set minutes = $mins, description = '$sub' where timeid = " . $query['timeid'], $sqlSess);
            }
          }
        }
      }
    if ($allOk=="")
      {
      include "timesheetsub.php";

      $tmpl->assign('g',$g);
      echo $tmpl->fetch('timesheetsub.tpl');
      }
    else echo "Incorrect Params(2): " . $allOk;
    }
  else echo "Incorrect Params(3): " . $allOk;
  }
else echo "Incorrect Method. Expecting Post!";
?>