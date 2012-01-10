<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='StyleSheet' href='{$g->stylesheet}' type='text/css' title='NormalStyle' media='screen, print'>
<script language="javascript" src="tm_ajax.js" type="text/javascript"></script>
<script language="javascript" src="datepicker.js" type="text/javascript"></script>
<title>{$g->pageTitle}</title>
</head>

<body onload="RequestHoliday();">
<div id='main'>
{include file="header.tpl"}
{include file="buttonbar.tpl"}
<h2>Out of Office</h2>

<form action="Holiday.php?action=submit" method="post">
  <table class='de'>
    {if $g->statusMessage ne "Ready"}
    <tr>
      <td {if $g->statusMessage eq "Holiday Updated" or $g->statusMessage eq "Holiday Inserted"}class='pr'{else}class='error'{/if} colspan="2">
        {$g->statusMessage}
       </td>
    </tr>
    {/if}
    <tr>
      <td class="de">Developer</td>
      <td class="de"><select name="DevID" id="DevID" onchange="RequestHoliday();">{html_options options=$g->devList selected=$g->dev}</select></td>
    </tr>
    <tr>
      <td class="de">Created Date</td>
      <td class="de"><input type="text" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40" disabled>
      <input type="hidden" name="CreatedDate" value="{$g->post.CreatedDate|escape}" size="40"></td>
    </tr>
    <tr>
      <td class="de">Start Date</td>
      <td class="de"><input type="text" name="StartDate" value="{$g->StartDate|escape}" size="40"> 
      <input type=button value="Date" onclick="displayDatePicker('StartDate');"> </td>
    </tr>
    <tr>
      <td class="de">End Date</td>
      <td class="de"><input type="text" name="EndDate" value="{$g->EndDate|escape}" size="40">
      <input type=button value="Date" onclick="displayDatePicker('EndDate');"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="HolidayAction" value="{$g->formAction}">
      </td>
    </tr>
  </table>
</form>
<br/>
{$g->calendar}

<hr>
<h2><span id="FullName"></span>Out of Office Dates</h2>
<div id='HolTable'>
WARNING: No AJAX CONTENT
</div>
<br><br>
{include file="footer.tpl"}
</div>
</body>
</html>