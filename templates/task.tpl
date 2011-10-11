<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<script language="javascript" src="datepicker.js" type="text/javascript"></script>
<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="Header.tpl"}
{include file="ButtonBar.tpl"}
<h2>{$g->name} ({$g->taskDetails})</h2>

<form action="task.php?action=submit" method="post">
  <table class='de'>
    {if $g->statusMessage ne "Ready"}
    <tr>
      <td {if $g->statusMessage eq "Task Updated" or $g->statusMessage eq "Task Inserted"}class='pr'{else}class='error'{/if} colspan="2">
        {$g->statusMessage}
       </td>
    </tr>
    {/if}
    <tr>
      <td class="de">Project</td>
      <td class="de">{html_options name="ProjectID" options=$g->projList selected=$g->proj}</td>
    </tr>
    <tr>
      <td class="de">Task</td>
      <td class="de"><input type="text" name="TaskName" value="{$g->post.TaskName|escape}" size="40">
      <input type="hidden" name="TaskID" value="{$g->taskid}" size="4"></td>
    </tr>
    <tr>
      <td class="de">Staff Member</td>
      <td class="de">{html_options name="DevID" options=$g->devList selected=$g->dev}</td>
    </tr>
    <tr>
      <td class="de">Task Created Date</td>
      <td class="de"><input type="text" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40" disabled>
      <input type="hidden" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40"></td>
    </tr>
    <tr>
      <td class="de">Estimated Start Date</td>
      <td class="de"><input type="text" name="StartDate" value="{$g->startDate|escape}" size="40">
      <input type=button value="Date" onclick="displayDatePicker('StartDate');"></td>
    </tr>
    <tr>
      <td class="de">Estimated Completion Date</td>
      <td class="de"><input type="text" name="CompDate" value="{$g->compDate|escape}" size="40">
      <input type=button value="Date" onclick="displayDatePicker('CompDate');"></td>
    </tr>
    <tr>
      <td class='de'>Estimated Minutes</td>
      <td class='de'><input type='text' name='minutes' id='minutes' size='10' value='{$g->post.minutes|escape}' />{if isset($g->post.minutes)}({minsToHours mins=$g->post.minutes} hours){/if}</td>
    </tr>
    <tr>
      <td class="de">Status</td>
      <td class="de">{html_options name="Status" options=$g->statList selected=$g->status}</td>
    </tr>
    <tr>
      <td class="de">Tracked Task</td>
      <td class="de">{html_radios name="Tracked" options=$g->yesNo selected=$g->post.Tracked}</td>
    </tr>    <tr>
      <td class="de" valign="top">Comment:</td>
      <td class="de" ><textarea name="Comment" cols="40" rows="3">{$g->post.Comment|escape}</textarea></td>
    </tr>
    {if $g->status ne 5}
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="TaskAction" value="{$g->formAction}">
      </td>
    </tr>
    {/if}
  </table>
</form>
<br/>
{php}MakeCalendar();{/php}

{if $g->taskid ge 1 and $g->post.Tracked eq 'Y'}
<h2>Completion Estimate History for Task</h2>
{html_table loop=$g->compDetails cols=$g->compCols tr_attr = $g->trAttr td_attr="class='de'" "}
{/if}

{include file="footer.tpl"}
</div>
</body>
</html>