{$g->daysInMonth} days in month.

{if $g->error eq ""}
<table>
<th>Id</th>
<th>Task</th>
{section name=day start=1 loop=`$g->daysInMonth+1` step=1}
{if $g->totals.day[$smarty.section.day.index] gt 0}
<th class='centered'><a href='timesheet.php?date={$g->year}-{$g->mon}-{$smarty.section.day.index}'> {$smarty.section.day.index}</a></th>
{/if}
{/section}
<th class='centered'>Total</th>
{foreach from=$g->ts key=tsKey item=ts}
</tr>
<tr>
<td class='de'>{$tsKey}</td>
<td class='de'><a href='projecttime.php?proj={$ts.projectid}'>{$ts.taskname}</a></td>
{section name=day start=1 loop=`$g->daysInMonth+1` step=1}
{if $g->totals.day[$smarty.section.day.index] gt 0}
<td>{minsToHours mins=$g->totals[$tsKey][$smarty.section.day.index]}</td>
{/if}
{/section}
<td>{minsToHours mins=$g->totals[$tsKey].total}</td>
</tr>
{/foreach}
<tr>
<th></th>
<th>Total</th>
{section name=day start=1 loop=`$g->daysInMonth+1` step=1}
{if $g->totals.day[$smarty.section.day.index] gt 0}
<th class='centered'>{minsToHours mins=$g->totals.day[$smarty.section.day.index]}</th>
{/if}
{/section}
<th class='centered'>{minsToHours mins=$g->totals.day.total}</th>
</tr>
</table>
{else}
Error: {$g->error}
{/if}