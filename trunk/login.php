<?php
if(!isset($_SESSION))
  session_start();

require('init_smarty.php');
if ($templatesOK)
  {
  require ('tmUtils.php');
  DBConnect();

  class gFormVar
    {
    public $error="";
    public $stylesheet='normal.css';
    public $pageTitle='Project Tracking Database';
    }
  $g = new gFormVar(); 

  if (!$sqlSess)
    {
    $g->error = "not_connected";
    }

  $loggedIn = FALSE;

  if (isset($_POST['Action']))
    {
    if (isset($_POST['UserName']))
      {
      if ($_POST['UserName'] == "")
        { $g->error="name_empty"; }
      else
        {
        $user = GetUser($_POST['UserName']);
        if ($user[1] != "")
          {
          $expire = time()+60*60*24*7;

          setcookie("username",$_POST['UserName'],$expire);
          setcookie("userfullname",$user[1], $expire);
          setcookie("userno",$user[2], $expire);

          $loggedIn=TRUE;
          }
        else
          { $g->error="name_wrong"; }
        }
      }
    else
      { $g->error="name_empty"; }
    }

  if ($loggedIn)
    {
    header( 'Location: matrix.php' );
    }
  else
    {
    $tmpl->assign('g',$g);
    $tmpl->display('login.tpl');
    }
  }
?>