<?php
#if(!isset($_SESSION))
#  session_start();

require('init_smarty.php');
require ('tmUtils.php');

if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);
  
  if (isset($user))
    {
    class gFormVar
      {
      public $holCols;
      public $holDetails;
      }
    $g = new gFormVar();  

    DBConnect();

    $today = date('Y-m-d');
    $g->holCols = "Developer, Start Date, EndDate";
    $g->holDetails = MakeTable("name, date_format(startdate,\"%d-%m-%Y\"), date_format(enddate,\"%d-%m-%Y\")", 
     "holiday, users", "holiday.developerid = users.userid and enddate > '$today' and developerid = $user", "startdate desc");

    if (count($g->holDetails) == 0) { echo "No Out of Office weeks found<br>"; }
    else
      {
      $tmpl->assign('g',$g);
      echo $tmpl->fetch('holtable.tpl');
      }
    }
  }
else
  { echo "User not set<br>"; }
?>

