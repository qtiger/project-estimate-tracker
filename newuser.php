<?php
if(!isset($_SESSION))
  session_start();

require('init_smarty.php');
require ('tmUtils.php');

class gFormVar
  {
  public $name = "User Maintenance";
  public $status;
  public $projid;
  public $taskid;
  public $post;
  public $trAttr;
  public $userDetails = "New User";
  public $formAction = "Insert";
  public $statusMessage="Ready";
  public $stylesheet='normal.css';
  public $pageTitle='Project Tracking Database';
  }
$g = new gFormVar(); 


DBConnect();

if($_SERVER['QUERY_STRING'])
  {
  parse_str($_SERVER['QUERY_STRING']);
  if (isset($user))
    {
    $g->userDetails = 'Modify ' . $user;
    
    if ($sqlSess)
      {
      $sql = "select UserID, UserName, Name, Team from users where username = '$user'";
      
      $userRes = mysql_query($sql,$sqlSess);
      
      if ($userRes)
        {
        $userRow = mysql_fetch_array($userRes);
        
        if ($userRes)
          {
          $g->formAction = "Update";
          $g->post      = $userRow;

          
          $g->taskDetails = $g->taskDetails . " - " . $taskRow['TaskName'];
          }
        }
      }
     }
   }
   
if ($_POST['UserAction'])
  {
  if ($_POST['UserAction'] == "Insert")
    {
    $sts = mysql_query("insert into users (UserName,Name, Team) values('" . $_POST['UserName'] . "','" . $_POST['Name'] . "','" . $_POST['Team'] . "')",$sqlSess);
    if ($sts)
      $g->statusMessage = "New User inserted";
    else $g->statusMessage = "An error occured inserting the user. " . $sts;
    
    }
  if ($_POST['UserAction'] == "Update")
     {
     $sts = mysql_query("update users set UserName = '" . $_POST['UserName'] . "', Name = '" . $_POST['Name'] . "', Team = '" . $_POST['Team'] . "' where userid = '" . $_POST['UserID'] . "'",$sqlSess);
     if ($sts)$g->statusMessage = "User Updated";
     else $g->statusMessage = "An error occured updating the user. " . $_POST['UserID'] . $sts;
     }
  }
  
$g->userCols = "ID, User Name, Name";
$g->userList = MakeTable("UserID, UserName, Name, Team" , "users", "", "Name");
  
$tmpl->assign('g',$g);
$tmpl->display('newuser.tpl');
?>