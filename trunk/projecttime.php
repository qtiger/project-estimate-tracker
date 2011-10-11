<?php
  require('init_smarty.php');
  require ('tmUtils.php');

  class gFormVar
    {
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
    $g->projID = $proj;
  if (isset($dl)) $download=true;
  }

$g->items = 0;
$g->projName = "";

if (isset($g->projID))
{
  if ($sqlSess)
  {
  $sql = "select ProjectName from project where projectid = " . $g->projID ;
  $projRes = mysql_query($sql,$sqlSess);
  if ($projRes)
    {
    $projRow=mysql_fetch_array($projRes);
    if ($projRow) $g->projName = $projRow['ProjectName'];
    }
  }

  $tl = buildList("select tk.taskname, tm.description,
  date_format(tm.date, '%Y%m') taskMonth, sum(tm.minutes) mins
  from time tm, task tk
  where tm.taskid = tk.taskid
  and tk.projectid = " . $g->projID.
  " group by taskname, description, taskMonth
  order by taskname, description, taskMonth");

  if (!$tl[0]) $g->statusMessage = $tl[1];
  else
  {
    $g->items = count($tl);

    if ($g->items > 0)
    {
      $g->startMon = 300012;
      $g->endMon = 199501;

      foreach ($tl as $row)
      {
        if ($row['taskMonth'] < $g->startMon) $g->startMon = $row['taskMonth'];
        if ($row['taskMonth'] > $g->endMon) $g->endMon = $row['taskMonth'];
      }

      $g->monArr = array();

      $mon = $g->startMon;
      while ($mon <= $g->endMon)
      {
        $g->monArr[$mon] = substr($mon,0,4) . '-' . substr($mon,4,2);
        $mon++;
        if ($mon % 100 > 12)
        {
          $yr = substr($mon,0,4) + 1;
          $mon = $yr . '01';
        }
      }

      $continue = true;
      $i = 0;
      $holdTask = '';
      $holdSub= '';
      $g->timeList = array();
      $g->totalsArr = array();

      while ($i < $g->items)
      {
        $total = 0;
        foreach ($g->monArr as $m=>$mName)
        {
          if ($m == $g->startMon)
          {
            $tlr = array();
            $tlr[] = $tl[$i]['taskname'];
            $tlr[] = $tl[$i]['description'];
          }
         if ($m == $tl[$i]['taskMonth'])
         {
           $tlr[]=minsToHours(array('mins'=>$tl[$i]['mins']));
           $total += $tl[$i]['mins'];
           $g->totalsArr[$m] += $tl[$i]['mins'];

           if ($i<$g->items)
           {
             if ($tl[$i+1]['taskname']==$tlr[0] && $tl[$i+1]['description']==$tlr[1]) $i++;
           }
         }
         else $tlr[] = '';
        }
        $tlr[] = minsToHours(array('mins'=>$total));
        $g->totalsArr['total'] += $total;

        $g->timeList[] = $tlr;
        $i++;
      }
      
      foreach ($g->totalsArr as $k=>$v)
        {
        $g->totalsArr[$k] = minsToHours(array('mins'=> $v));
        $g->manDaysArr[$k] = minsToDays($v,$manHours);
        }
    }
  }
}
else $g->statusMessage = "No project specified";
$tmpl->assign('g',$g);
if ($download) $tmpl->display('projecttime.dwn');
else $tmpl->display('projecttime.tpl');
?>
