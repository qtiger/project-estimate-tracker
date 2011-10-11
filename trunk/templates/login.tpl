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
<h2>Login</h2>

<form action="login.php?action=submit" method="post">
  <table class='de'>
    {if $g->error ne ""}
    <tr>
      <td class='pr'>
        {if $g->error eq "name_empty"}I'm drawing a blank here. Please enter a user name!
        {elseif $g->error eq "name_wrong"}I don't recognise that name. Are you sure you work here?
        {elseif $g->error eq "not_connected"}Not connected to the database.  Please check that database name and login credentials are correct in environment.php
        {/if}
      </td>
    </tr>
    {/if}
    <tr>
      <td class="de">Please enter user name &nbsp;
        <input type="text" name="UserName" value="" size="4"><br><br>
        <input type="submit" name="Action" value="Log In">
      </td>
    </tr>
  </table>
</form>
{include file="footer.tpl"}
</div>
</body>
</html>