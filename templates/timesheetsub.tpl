<div id='timesheet'>
<h2>Timesheet ({$g->name} - {$g->date})</h2>
<p>View <a href='tsrep.php'>Timesheet Reports</a></p>
<div id='subtaskPop'>Empty Menu</div>
{if $g->showList}
<table class='tm'>
<tr>
<th>Date Created</th>
<th>Task</th>
<th>SubTask</th>
<th>Minutes</th>
<th>Hours</th>
<th width='40px'>Delete</th>
<th width='40px'>Now</th>
<th width='15px'></th>
</tr>
{assign var=total value=0}
{foreach from=$g->timeList item=time}
{assign var=total value=$total+$time.minutes}
<tr>
<td class='de'>{$time.starttime}</td>
<td class='de'>{$time.taskname}</td>
<td class='de'><input type="text" name="Sub-{$time.timeid}" value="{$time.description}" id="Sub-{$time.timeid}" size="35" onkeyup='keyHandler(event,"Sub-{$time.timeid}",{$g->devID},{$time.taskid})' onchange='timeChange({$time.timeid})' class='subtask' /></td>
<td class='de'><input type="text" name="Minutes-{$time.timeid}" value="{$time.minutes}" id="Minutes-{$time.timeid}" size="6" onchange='timeChange({$time.timeid})' /></td>
<td class='de'>{minsToHours mins=$time.minutes}</td>
<td class='de'><button class='btn' onclick="DeleteTime({$g->devID},{$time.timeid})">Delete</button></td>
<!--<td class='de'><button  class='btn' onclick="UpdateTime({$g->devID},{$time.timeid},'Minutes-{$time.timeid}','Sub-{$time.timeid}')">Update</button></td>-->
<td class='de'>{if $time.minutes eq 0}<button  class='btn' onclick="UpdateTime({$g->devID},{$time.timeid},'Minutes-{$time.timeid}','Sub-{$time.timeid}')">Now</button>{/if}</td>
<td class='de'>{if $time.minutes ne 0}<button  class='btn' onclick="AddTime({$g->devID}, {$time.taskid},true,'Sub-{$time.timeid}')">+</button>{/if}</td>
</tr>
{/foreach}
<tr>
<th>Total</th>
<th></th>
<th></th>
<th>{$total}</th>
<th>{minsToHours mins=$total}</th>
<th><em><span id='wkHrs'></span></em></th>
<th></th>
<th></th>
</tr>
<tr><td colspan='9' class='btm'><button class='btn' onclick="getTimeFields({$g->devID})">&nbsp;&nbsp;Update All&nbsp;&nbsp;</button></td></tr>
</table>
{else}
<p>No Timesheet for day</p>
{/if}
</div>