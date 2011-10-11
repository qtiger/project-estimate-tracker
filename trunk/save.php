<?php
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
    public $saveSts;
    public $statusMessage;
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
    $g->queryId = $user;
    }
  }

  $g->saveSts = SaveCSV($filePath);
  
  if ($g->saveSts == "ok")
    { $g->statusMessage = "Successfully saved the project matrix to \"$filePath\"";}
  else
    { $g->statusMessage = "<b>Save to \"$filePath\" file failed. " .  $saveSts . "</b>"; }
  $tmpl->assign('g',$g);
  $tmpl->display('save.tpl');
  }
else
  { header( 'Location: login.php' ); }
?>