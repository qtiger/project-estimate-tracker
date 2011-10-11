<?php
require_once ("perf.php");

$p = new perf();

if (isset($_COOKIE['userno']))
  {
  require('init_smarty.php');
  require ('tmUtils.php');

  class gFormVar
    {
    public $name;
    public $queryId = "";
    public $userid;
    public $colList;
    public $debug;
    public $pfilter = false;
    public $ufilter = false;
    public $stylesheet='normal.css';
    public $pageTitle='Project Tracking Database';
    }
  $g = new gFormVar();

  DBConnect();
  GetPrefs();

  if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);#
  if (isset($user))
    {
    $_SESSION['devID'] = $user;
    $devID = $user;
    $g->queryId = $user;
    }
  if (isset($proj))
    {
    $_SESSION['projID'] = $proj;
    $projID = $proj; 
    }
  }

  if ($projID !="") { $g->pfilter=true; }
  if ($devID !="")  { $g->ufilter=true; }

  $dates = StartDate();
  $p->mark("Pre Project Walker");
  $proj  = ProjectWalker($devID, false, $projID);

  //SaveCSV("c:\ipc1.csv");

  $g->projTable = $projTable;
  $g->projTableAttr = $projTableAttr;

  $g->name = $_COOKIE['userfullname'];
  $g->userid = $_COOKIE['username'];
  $g->colList = $numWeeks*2 + 2;

  $g->cellStyle = explode(",",$tdAttr);
  $g->perf = $p->show('Pre Display');
  $tmpl->assign('g',$g);
  $tmpl->display('matrix.tpl');
  }
else
  { header( 'Location: login.php' ); }
?>