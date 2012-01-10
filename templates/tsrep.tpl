<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<script language="javascript" src="datepicker.js" type="text/javascript"></script>
<script language="javascript" src="tm_ajax.js" type="text/javascript"></script>
<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="header.tpl"}
{include file="buttonbar.tpl"}
<button onClick="window.location='timecube.php'">Excel Timesheet</button> 
<button onClick="window.location='timecube.php?f=csv'">CSV Timesheet</button><hr/> 
<h2>TimeSheet Report</h2>

<select name="DevID" id="DevID" onchange="TimesheetReport();">{html_options options=$g->devList selected=$g->devID}</select>
<select name="MonthID" id="MonthID" onchange="TimesheetReport();">{html_options options=$g->monList selected=$g->mon-1}</select>
<select name="YearID" id="YearID" onchange="TimesheetReport();">{html_options options=$g->yearList selected=$g->year}</select>
<br/><br/>

<div id="timesheet">
{include file="tsrep_sub.tpl"}
</div>
{include file="footer.tpl"}
</div>
</body>
</html>