<?php
$g->timeList = buildList("select tm.timeid, tk.taskname, tm.minutes, tm.date,
date_format(tm.starttime,'%d-%m-%Y %H:%i') starttime, tk.taskid, tm.description
from time tm, task tk
where tm.taskid = tk.taskid
and date = '" . $g->date ."'
and tm.developerid = " . $g->devID .
" order by tm.starttime");
$g->name = $_COOKIE['userfullname'];

if ($g->timeList[0]) $g->showList = true;
else $g->showList = false;

$tmpl->register_function("minsToHours","minsToHours");
?>