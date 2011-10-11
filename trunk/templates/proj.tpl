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
<h2>{$g->name} ({$g->projDetails})</h2>
<form action="proj.php?action=submit" method="post">
  <table class='de'>
    {if $error ne ""}
    <tr>
      <td bgcolor="yellow" colspan="2">
        {if $error eq "name_empty"}You must supply a name.
        {elseif $error eq "comment_empty"} You must supply a comment.
        {/if}
      </td>
    </tr>
    {/if}
    <tr>
      <td class="de">Project Name<input type="hidden" name="ProjectID" value="{$g->proj}" size="4"></td>
      <td class="de"><input type="text" name="ProjectName" value="{$g->post.ProjectName|escape}" size="40"></td>
    </tr>
    <tr>
      <td class="de">Started Date</td>
      <td class="de"><input type="text" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40" disabled>
      <input type="hidden" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40"></td>
    </tr>
    <tr>
      <td class="de">Status</td>
      <td class="de">{html_options name="Status" options=$g->statList selected=$g->status}</td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="ProjAction" value="{$g->formAction}">
      </td>
    </tr>
  </table>
</form>

{if $g->taskRows ge 1}
<h2>Current Status of Tasks in Project</h2>
<p><a href='projecttime.php?proj={$g->proj}'>View Project Timesheet</a></p>
{html_table loop=$g->compDetails cols=$g->compCols tr_attr = $g->trAttr td_attr="class='de'""}
{/if}

{ if $g->showlist }
<h2>Current Projects</h2>
<table class='de'>
<tr><th>Project ID</th><th>Project Name</th><th>Status</th></tr>
{foreach from=$g->projList item=proj}
<tr>
<td class='de{if $proj.live ne "L"} start-o{/if}'>{$proj.projectid}</td>
<td class='de{if $proj.live ne "L"} start-o{/if}'><a href='proj.php?proj={$proj.projectid}'>{$proj.projectname}</a> (<a href='projecttime.php?proj={$proj.projectid}'>Timesheet</a>)</td>
<td class='de{if $proj.live ne "L"} start-o{/if}'>{if $proj.live eq 'L'}In Progress{else}Completed{/if}</td>
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