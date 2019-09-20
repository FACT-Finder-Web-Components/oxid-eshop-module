[{if $success}]
    [{if is_array($result)}]
        [{foreach from=$result item=el}]
            <span class="text-success">[{$el}]</span>
        [{/foreach}]
    [{else}]
        <span class="text-success">[{$result}]</span>
    [{/if}]
[{else}]
    <span class="text-error">[{$result}]</span>
[{/if}]
