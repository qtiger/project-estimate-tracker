<?php
$g->monthStart = date($g->year . "-" . $g->mon ."-01");
$monthEnd = strtotime ( '+1 month' , strtotime ( $g->monthStart ) ) ;
$g->monthEnd = date ("Y-m-d",$monthEnd);

$g->daysInMonth = cal_days_in_month  (CAL_GREGORIAN,$g->mon,$g->year);

$ts = TaskArray($g->monthStart, $g->monthEnd, $g->devID);

$g->dayOfWeek = date("w",strtotime($g->monthStart));
if ($g->dayOfWeek == 0) $g->dayOfWeek = 7;

if (is_array($ts)) $g->error = "";
else $g->error = $ts;

$g->ts = $ts;

$g->totals = TotalsArray($g->monthStart, $g->monthEnd, $g->devID);

$tmpl->registerPlugin("function","minsToHours","minsToHours");
?>