<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<script language="javascript" src="tm_ajax.js" type="text/javascript"></script>
<script language="javascript" src="datepicker.js" type="text/javascript"></script>
<title>{$g->pageTitle}</title>
</head>

<body onload="hrTimer({$g->startHour},{$g->startMin})">
<div id='main'>
{include file="header.tpl"}
{include file="buttonbar.tpl"}

<h2>Timesheet ({$g->name} - {$g->date})</h2>
<p>View <a href='tsrep.php'>Timesheet Reports</a></p>
<div id ="timesheet">
{include file="timesheetsub.tpl"}
</div>
<hr/>

<h2>Add Time (<span id="devID">{$g->devID}</span>)</h2>

<form action=''>
   <table class='de'>
   <tr>
   <td class='de'>Number of Minutes</td>
   <td class='de'><input type='text' name='minues' id='minutes' size='10' value='15' /></td>
   </tr>
   <tr>
   <td class="de">Date</td>
   <td class="de"><input type="text" name="Date" value="{$g->date|escape}" size="40" id='date' />
      <input type=button value="Date" onclick="displayDatePicker('Date');"></td>
    </tr>
   </table>
</form>
   
<br/>
<table class='de'>
<tr><th>Project</th><th>Task</th><th>Add</th><th>Start</th></tr>
{foreach from=$g->taskList item=task}
<tr>
<td class='de{if $task.type eq 1}{if $task.developerid ne 0} proj-o{else} start-o{/if}{/if}'><a href="proj.php?proj={$task.projectid}">{$task.projectname}</a> (<a href="projectTime.php?proj={$task.projectid}">Timesheet</a>)
{if $task.developerid eq 0} Team Task {/if}
</td>
<td class='de{if $task.type eq 1}{if $task.developerid ne 0} proj-o{else} start-o{/if}{/if}'><a href="task.php?task={$task.taskid}">{$task.taskname}</a></td>
<td class='de{if $task.type eq 1}{if $task.developerid ne 0} proj-o{else} start-o{/if}{/if}'><button onclick="AddTime({$g->devID}, {$task.taskid},false)">Add</button></td>
<td class='de{if $task.type eq 1}{if $task.developerid ne 0} proj-o{else} start-o{/if}{/if}'><button onclick="AddTime({$g->devID}, {$task.taskid},true)">Start</button></td>
</tr>
{/foreach}
</table>

<hr/>

{$g->statusMessage}
{include file="footer.tpl"}
</div>
</body>
</html>