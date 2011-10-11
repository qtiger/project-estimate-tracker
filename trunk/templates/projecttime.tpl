<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen'>
<link rel='StyleSheet' href='print.css' type='text/css' title='PrintStyle' media='print'>
<script language="javascript" src="tm_ajax.js" type="text/javascript"></script>
<script language="javascript" src="datepicker.js" type="text/javascript"></script>
<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="Header.tpl"}
{include file="ButtonBar.tpl"}
<h2>{$g->projID} - {$g->projName} - Time ({$g->startMon} - {$g->endMon})</h2>
{if ($g->items > 0)}
<p><a href="projecttime.php?dl=y&proj={$g->projID}">Open Timesheet in Excel</a></p>
<table>
  <tr>
    <th>Task</th>
    <th>Subtask</th>
    {foreach from=$g->monArr item=mon}
    <th class='centered'>{$mon}</th>
    {/foreach}
    <th class='centered'>Total</th>
  </tr>
  {foreach from=$g->timeList item=subtask}
  <tr>
  {foreach from=$subtask key=k item=stCell}
  <td{if $k <=1} class='de'{/if}>{$stCell}</td>
   {/foreach}
  </tr>
  {/foreach}
  <tr>
    <th>Total (hours)</th>
    <th></th>
    {foreach from=$g->monArr key=mon item=mName}
      <th class='centered'>{$g->totalsArr[$mon]}</th>
    {/foreach}
    <th class='centered'>{$g->totalsArr.total}</th>
  </tr>
    <tr>
    <th>Total (person days)</th>
    <th></th>
    {foreach from=$g->monArr key=mon item=mName}
      <th class='centered'>{$g->manDaysArr[$mon]}</th>
    {/foreach}
    <th class='centered'>{$g->manDaysArr.total}</th>
  </tr>
</table>
{/if}
<hr/>
{$g->statusMessage}
{include file="footer.tpl"}
</div>
</body>
</html>
