<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="Header.tpl"}
{include file="ButtonBar.tpl"}
<h2>{$g->name} ({$g->user})</h2>

{ if $g->showlist }
Number of Months to show: <a href ="subtask.php?months=1"  class='textButton'>1</a>
<a href ="subtask.php?months=3" class='textButton'>3</a>
<a href ="subtask.php?months=6"  class='textButton'>6</a>
<a href ="subtask.php?months=12"  class='textButton'>12</a>
<h2>Current Subtasks ({$g->months} months)</h2>

<table class='de'>
<tr><th>Task Name</th><th>SubTaskName</th></tr>
{assign var=rcnt value=1}
{foreach from=$g->subTaskList item=subTask}
{if $rcnt mod 2 == 0 }
  <tr class='even'>
{else}
  <tr class='odd'>
{/if}
{assign var=rcnt value=`$rcnt+1`}
<td class='de'>{$subTask.taskname}</td>
<td class='de'>{$subTask.description}</td>
</tr>
{/foreach}
</table>
{/if}

<hr>
{$g->statusMessage}
{include file="footer.tpl"}
</div>
</body>
</html>