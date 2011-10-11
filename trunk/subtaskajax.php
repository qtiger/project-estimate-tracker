<?php
require('init_smarty.php');
require ('tmUtils.php');
DBConnect();

if(!isset($_SESSION))
  session_start();

$error="No Session";
$doSQL = true;
$items = 0;

parse_str($_SERVER['QUERY_STRING'],$query);

if (array_key_exists('task',$query)) $qTask = $query['task'];
else $qTask = -1;

if ($sqlSess)
{
  $error="No Query";

  if (array_key_exists('desc', $query))
  {
  if (array_key_exists('desc', $_SESSION) && $qTask == $_SESSION['task'])
    {
      $par = $_SESSION['desc'];
      $parLen = strlen($par);

      if ($par == substr($query['desc'],0,$parLen))
      {
        $temp = unserialize($_SESSION['desclist']);
        $doSQL = false;

        $par = strtolower($query['desc']);
        $parLen = strlen($par);

        foreach ($temp as $t)
        {
          if ($par == substr(strtolower($t),0,$parLen))
            $descList[] = $t;
        }
      }
    }
    $_SESSION['desc'] = $query['desc'];

    if ($doSQL)
    {
      $error="No Result";
      $descList = array();

      $sql = "select distinct description from time where description like '"
      . $query['desc'] . "%'";
      if (array_key_exists('dev',$query)) $sql .= " and developerid = " . $query['dev'];
      if (array_key_exists('task',$query))
        {
          $_SESSION['task'] = $query['task'];
          $sql .= " and taskid = " . $query['task'];
        }
      $sql .= " order by description";

      //echo ($sql);

      $res = mysql_query($sql,$sqlSess);
      if ($res)
      {
        $error="No Rows?";
        while($row=mysql_fetch_array($res))
          $descList[] = $row['description'];
      }

      $_SESSION['desclist'] = serialize($descList);
    }
    $tmpl->assign('dl',$descList);
  }
  $tmpl->assign('items',count($descList));
  $tmpl->assign('error',$error);
  $tmpl->display('subtask.tpl');
}
?>