<?php
require('init_smarty.php');
require ('tmUtils.php');
DBConnect();

$fmt = "xml";
parse_str($_SERVER['QUERY_STRING'],$query);
if (array_key_exists("f",$query))
  if ($query['f']=='csv') $fmt = 'csv';

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
tm.minutes/420 Days
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
    while ($row=mysql_fetch_assoc($res))
    {
      $cube[] = $row;
    }
  }

  $tmpl->register_function('csvHeader','csvHeader');
  $tmpl->assign('cube',$cube);
  if ($fmt == "csv") $tmpl->display('timecube.dwn');
  else  $tmpl->display('xmlcube.dwn');
}
?>
