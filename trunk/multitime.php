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
  if (!array_key_exists("user", $query)) $allOk .= "Developer not set.";
  if (!array_key_exists("ids", $query)) $allOk .= "IDs not set.";
  if (!array_key_exists("subs", $query)) $allOk .= "Subs not set.";
  if (!array_key_exists("mins", $query)) $allOk .= "Mins not set.";

  if ($allOk == "")
  {
    $ids = explode("|",$query['ids']);
    $mins = explode ("|",$query['mins']);
    $subs = explode ("|",$query['subs']);

    $i=0;
    foreach ($ids as $id)
    {
      if ($id!="")
        $res = mysql_query("update time set minutes = '" . $mins[$i] . "', description = '" . $subs[$i] . "' where timeid = '$id'", $sqlSess);
      $i++;
    }

  $g = new gFormVar();

  $g->devID = $query['user'];
  $g->date = $query['date'];

  include "timesheetsub.php";

  $tmpl->assign('g',$g);
  echo $tmpl->fetch('timesheetsub.tpl');
  }
  else echo $allOk;
  }
?>