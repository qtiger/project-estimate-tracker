<?php
require('init_smarty.php');
require ('tmUtils.php');
DBConnect();

if ($sqlSess)
{
  $sql = "select p.projectname Project,
tk.taskname Task,
tm.description SubTask,
u.name User,
u.team Team,
tm.date Date,
tm.minutes Minutes,
tm.minutes/60 Hours,
tm.minutes/1440 Days
from project p, task tk, time tm, users u
where p.projectid = tk.projectid
and tm.taskid = tk.taskid
and tm.developerid = u.userid";

  $res = mysql_query($sql,$sqlSess);

  $numFields = mysql_num_fields($res);
  for ($i=0; $i<$numFields; $i++)
    $fNames[] = mysql_field_name($res,$i);

  $cube[] = $fNames;
  
  if ($res)
  {
    while ($row=mysql_fetch_row($res))
    {
      $cube[] = $row;
    }
  }

  $tmpl->assign('cube',$cube);
  $tmpl->display('timecube.dwn');
}
?>
