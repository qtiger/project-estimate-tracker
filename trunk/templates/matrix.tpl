<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen'>
<link rel='StyleSheet' href='print.css' type='text/css' title='PrintStyle' media='print'>

<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="header.tpl"}
{include file="buttonbar.tpl"}
<h2>Task Matrix - {$smarty.now|date_format:"%d/%m/%Y"}</h2>

<p class='prhide'>Welcome {$g->name}
{if $g->queryId ne $g->userid and !$g->ufilter} - <a href="matrix.php?user={$g->userid}">View your tasks only</a>{/if}
{if $g->ufilter}
 - <a href='matrix.php?user='>View all users</a>
{/if}
{if $g->pfilter}
 - <a href='matrix.php?proj='>View all projects</a>
{/if}

</p>

{assign var=rcnt value=1}
<table>
{section name=outer loop=$g->projTable}
{if $rcnt mod 2 == 0 }
  <tr class='even'>
{else}
  <tr class='odd'>
{/if}
{assign var=rcnt value=$rcnt+1}
{section name=inner loop=$g->projTable[outer]}
    <td {$g->projTableAttr[outer][inner]}>{$g->projTable[outer][inner]}</td>
{/section}
  </tr>
{/section}
</table>

<div class='prhide'>
<h2>Key</h2>
<table class='key'>
  <tr>
    <th width='60px'>Item</th>
    <th>Description</th>
  </tr>
  <tr>
    <td class='pr'>Project</td>
    <td class='de'>The Project Name (click to filter)</td>
  </tr>
  <tr>
    <td class='kb ind' >Task</td>
    <td class='de' >The Task Name (click to edit)</td>
  </tr>
  <tr>  
    <td class='kb de'>User</td>
    <td class='de'>Task assigned to user (click to filter and see users tasks only)</td>
  </tr>
  <tr>  
    <td>12n</td>
    <td class='de'>Estimated completion week (week 12)  - task not started or on hold</td>
  </tr>
  <tr>  
    <td class='start-e'></td>
    <td class='de'>Task start date in past, but no completion estimate</td>
  </tr>
  <tr>  
    <td class='start-e'>12N</td>
    <td class='de'>Estimated completion week (week 12)  - after task start date but not yet in progress</td>
  </tr>
  <tr>  
    <td class='proj-e'>12</td>
    <td class='de'>Estimated completion week (week 12)</td>
  </tr>
  <tr>  
    <td class='proj-e change'>12</td>
    <td class='de'>Estimate changed this week (to week 12)</td>
  </tr>
  <tr>  
    <td class='late-e'>12</td>
    <td class='de'>Estimate completion week is in the past</td>
  </tr>
  <tr>  
    <td class='block-e'>12b</td>
    <td class='de'>Estimate completion week (week 12) but task is blocked</td>
  </tr>
  <tr>  
    <td class='proj-e today'>12</td>
    <td class='de'>The current estimated completion week</td>
  </tr>
  <tr>  
    <td class='comp-e change'>12c</td>
    <td class='de'>Task was completed in week 12</td>
  </tr>
  <tr>  
    <td class='proj-e'>*</td>
    <td class='de'>Future week inside scope of current estimate</td>
  </tr>
    <tr>  
    <td class='hol-e today'>Out</td>
    <td class='de'>Staff member out of the office for 3 or more days in week</td>
  </tr>
</table>  
</div>
{include file="footer.tpl"}
{if isset($g->showPerf)}
<table class='key prhide'>
{foreach from=$g->perf item=p}
<tr><td>{$p.name}</td><td>{$p.step}</td><td>{$p.start}</td></tr>
{/foreach}
</table>
{/if}
</div>
</body>
</html>