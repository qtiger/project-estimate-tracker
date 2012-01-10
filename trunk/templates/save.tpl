<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<title>{$g->pageTitle}</title>
</head>

<body>
<div id='main'>
{include file="header.tpl"}
{include file="buttonbar.tpl"}
{if $g->saveSts eq "ok" }
<h2>Saved Matrix To CSV</h2>
{else}
<h2>Save Failed</h2>
{/if}
<p>{$g->statusMessage}</p>

<p>To change the file save location, click the "Settings" button above</p>
{include file="footer.tpl"}
</div>
</body>
</html>