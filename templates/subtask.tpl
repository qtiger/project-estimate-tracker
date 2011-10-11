{if $items >0 }
<div id='subtasklist'>
  {foreach from=$dl key=id item=desc}
  <div id="task-{$id}" onmouseover="highlight('{$id}')" onclick="popClick('{$desc}')" class='menuNormal'>
    &nbsp;{$desc}
  </div>
  {/foreach}
  <hr/>
  <div id="task-{$items}" onmouseover="highlight('{$items}')" onclick="popClick('XXX')" class='menuNormal'>
    &nbsp;Close
  </div>
</div>
{/if}
