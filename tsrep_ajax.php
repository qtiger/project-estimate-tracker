<?php
require('init_smarty.php');
require ('tmUtils.php');
DBConnect();

if ($_SERVER['REQUEST_METHOD']=='POST')
  {
  parse_str($_SERVER['QUERY_STRING'],$query);

  $allOk="";
  if (!array_key_exists("dev", $query)) $allOk .= "Developer not set.";
  if (!array_key_exists("mon", $query)) $allOk .= "Month not set.";
  if (!array_key_exists("year", $query)) $allOk .= "Year not set.";
  
  if ($allOk == "")
    {
    $g->devID = $query['dev'];
    $g->mon = $query['mon'];
    $yr = $query['year'];
    $g->yearList = array($yr-1 => $yr-1, $yr => $yr);
    $g->year = $yr;
    
    include "tsrep_sub.php";
    
    $tmpl->assign('g',$g);
    echo $tmpl->fetch('tsrep_sub.tpl');
    }
  else echo "Incorrect Params: " . $allOk;
  }
else echo "Incorrect Method. Expecting Post.";
?>