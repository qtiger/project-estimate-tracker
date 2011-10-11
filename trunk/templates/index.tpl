<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<title>{$g->GetPageTitle()}</title>
</head>

<body>
<div id='main'>
{include file="Header.tpl"}
<h2>User List</h2>

<p>Welcome {$g->name}</p>

<table>
{section name=outer loop=$g->list}
{strip}
   <tr>
   {section name=inner loop=$g->list[outer]}
   {if $smarty.section.outer.index==0}
     <th>{$g->list[outer][inner]}</th>
   {else}
     <td>{$g->list[outer][inner]}</td>
   {/if}
   {/section}
   </tr>
{/strip}
{/section}
</table>

{$g->rowCnt} Row{if $g->rowCnt != 1}s{/if} returned<br>

<p> And here is some text with a duff variable {$g->duff} to see how smarty handles it </p>

<p>And a test of how smarty handles an {$g->tURL}</p>
{include file="footer.tpl"}
</div>
</body>
</html>