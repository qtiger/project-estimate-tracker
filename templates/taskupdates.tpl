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
<h2>Task Updates</h2>
{if ($g->updates=='yes')}
<p>List of all task changes which have comments from the last month (excluding completed tasks).</p>
{html_table loop=$g->compDetails cols=$g->compCols tr_attr = $g->trAttr td_attr="class='de'" "}
{else}<p><b>No Updates in the last month</b></p>
{/if}{include file="footer.tpl"}
</div>
</body>
</html>