[{foreach from=$result item=el}]
  <div class="text-[{if $success}]success[{else}]error[{/if}]">[{$el}]</div>
[{/foreach}]
