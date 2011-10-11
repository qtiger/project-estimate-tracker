<?php
require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }
$g = new gFormVar();  

DBConnect();

if (isset($_COOKIE['userno']))
  {
  $g->devID = $_COOKIE['userno'];
  $g->devList  = MakeDropDown("userid","name","users",null,"name");
  $g->monList = array("January","February","March","April", "May", "June", "July","August","September","October","November","December");
  $g->mon = date("m");
  $yr = date("Y");
  
  $g->yearList = array($yr-1 => $yr-1, $yr => $yr);
  $g->year = $yr;
  
  include "tsrep_sub.php";

  $tmpl->assign('g',$g);
  $tmpl->display('tsrep.tpl');
  }
?>