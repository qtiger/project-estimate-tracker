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
{include file="header.tpl"}
{include file="buttonbar.tpl"}
<h2>{$g->name} ({$g->userDetails})</h2>

<form action="newuser.php?action=submit" method="post">
  <table class='de'>
    {if $g->statusMessage ne "Ready"}
    <tr>
      <td {if $g->statusMessage eq "User Updated" or $g->statusMessage eq "User Inserted"}class='pr'{else}class='error'{/if} colspan="2">
        {$g->statusMessage}
       </td>
    </tr>
    {/if}
    <tr>
      <td class="de">User Name</td>
      <td class="de"><input type="text" name="UserName" value="{$g->post.UserName|escape}" size="4">
      <input type="hidden" name="UserID" value="{$g->post.UserID}" size="4"></td>
    </tr>
    <tr>
      <td class="de">Full Name</td>
      <td class="de"><input type="text" name="Name" value="{$g->post.Name|escape}" size="40">
    </tr>
    <tr>
    <tr>
      <td class="de">Team</td>
      <td class="de"><input type="text" name="Team" value="{$g->post.Team|escape}" size="10">
    </tr>
    <tr>      <td colspan="2" align="center">
        <input type="submit" name="UserAction" value="{$g->formAction}">
      </td>
    </tr>
  </table>
</form>

<h2>Current Users</h2>

{assign var=rcnt value=0}
<table class='de'>
<tr><th>ID</th><th>User Name</th><th>Name</th><th>Team</th></tr>
{section name=ul loop=$g->userList}
{if $rcnt mod 4 == 0}{if $rcnt != 0}</tr>{/if}<tr>{/if}
{if ($rcnt-1) mod 4 == 0}
  <td class='ind'><a href='newuser.php?user={$g->userList[ul]}'>{$g->userList[ul]}</a></td>
{else}
  <td class='ind'>{$g->userList[ul]}</td>
{/if}
{assign var=rcnt value=$rcnt+1}
{/section}
</tr></table>

{include file="footer.tpl"}
</div>
</body>
</html>