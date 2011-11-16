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
<button onClick="window.location='timecube.php'">Excel Timesheet</button> 
<button onClick="window.location='timecube.php?f=csv'">CSV Timesheet</button><hr/> 
<h2>Settings</h2>
<form action="settings.php?action=submit" method="post">
  <table class='de'>
    <tr>
      <td class="de">Number of Weeks &nbsp;
        <input type="text" name="NumWeeks" value="{$g->numWeeks}" size="2">
      </td>
    </tr>
    <tr>
      <td class="de">Hours in a day &nbsp;
        <input type="text" name="ManHours" value="{$g->manHours}" size="2">
      </td>
    </tr>
    <tr>
      <td class="de">Start Hour&nbsp;
        <input type="text" name="startHour" value="{$g->startHour}" size="2">
        &nbsp;Start Minute&nbsp;
        <input type="text" name="startMin" value="{$g->startMin}" size="2">
      </td>
    </tr>
    <tr>
      <td  class="de"> 
        <input type="radio" name="DateFmt" value="week" {if $g->showAsWeeks}checked="checked"{/if}> Show completion estimate as week number
        <br>
        <input type="radio" name="DateFmt" value="date" {if !$g->showAsWeeks}checked="checked"{/if}> Show completion estimate as date
      </td>
    </tr>
    <tr>
      <td class="de">Save Location &nbsp;
        <input type="text" name="FilePath" value="{$g->filePath}" size="100">
      </td>
    </tr>
    <tr>
      <td  class="de"> 
        <input type="radio" name="ShowHol" value="Y" {if $g->showHoliday}checked="checked"{/if}> Show Out of Office
        <br>
        <input type="radio" name="ShowHol" value="N" {if !$g->showHoliday}checked="checked"{/if}> Hide Out of Office
      </td>
    </tr>
    <tr>
      <td  class="de"> 
        <input type="radio" name="DevSort" value="Y" {if $g->devSort}checked="checked"{/if}> Order by Staff Member
        <br>
        <input type="radio" name="DevSort" value="N" {if !$g->devSort}checked="checked"{/if}> Order by Project
      </td>
    </tr>
    <tr>
      <td>
        <input type="submit" name="Action" value="Save">
      </td>
    </tr>
  </table>
</form>
<hr>
{$g->statusMessage}
{include file="footer.tpl"}
</div>
</body>
</html>