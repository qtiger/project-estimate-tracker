<?php
require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $numWeeks;
  public $showAsWeeks;
  public $filePath;
  public $showHoliday;
  public $devSort;
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }

$g = new gFormVar();  

GetPrefs();

$expire = time()+60*60*24*365*10;

if (isset($_POST['Action']))
  {
  if (isset($_POST['NumWeeks']))
    {
    $numWeeks = $_POST['NumWeeks']/2;
    setcookie("NumWeeks",$_POST['NumWeeks'],$expire);
    }
  if (isset($_POST['ManHours']))
    {
    $manHours = $_POST['ManHours'];
    setcookie("ManHours",$manHours,$expire);
    }
  if (isset($_POST['FilePath']))
    {
    $filePath = str_replace("\\\\", "\\", $_POST['FilePath']);
    setcookie("FilePath",$filePath,$expire);
    }
  if (isset($_POST['DateFmt']))
    {
    if ($_POST['DateFmt'] == 'date')
      {
      $showAsWeeks = false;
      setcookie("ShowAsWeeks","N",$expire);
      }
    else
      {
      $showAsWeeks = true;
      setcookie("ShowAsWeeks","Y",$expire);
      }
    }

  if (isset ($_POST['startHour'])) $startHour = $_POST['startHour'];
  else $startHour = '9';
  setcookie("startHour",$startHour,$expire);

  if (isset ($_POST['startMin'])) $startMin = $_POST['startMin'];
  else $startMin = '0';
  setcookie("startMin",$startMin,$expire);

  if (isset($_POST['ShowHol']))
    {
    if ($_POST['ShowHol'] == 'Y')
      {
      $showHoliday = true;
      setcookie("ShowHoliday","Y",$expire);
      }
    else
      {
      $showHoliday = false;
      setcookie("ShowHoliday","N",$expire);
      }
    }
  if (isset($_POST['DevSort']))
    {
    if ($_POST['DevSort'] == 'Y')
      {
      $byDeveloper = true;
      setcookie("DevSort","Y",$expire);
      }
    else
      {
      $byDeveloper = false;
      setcookie("DevSort","N",$expire);
      }
    }

  $g->statusMessage="Settings Saved";
  }

$g->numWeeks = $numWeeks*2;
$g->manHours = $manHours;
$g->showAsWeeks = $showAsWeeks;
$g->filePath = $filePath;
$g->showHoliday = $showHoliday;
$g->devSort = $byDeveloper;
$g->startHour = $startHour;
$g->startMin = $startMin;

$tmpl->assign('g',$g);
$tmpl->display('settings.tpl');
?>