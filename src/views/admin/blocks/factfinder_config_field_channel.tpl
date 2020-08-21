[{if $module_var === 'ffChannel'}]
  [{foreach from=$shopLanguages item=shopLanguage name=shopLanguages}]
    <div style="width:262px; display: flex; flex-direction: row; justify-content: space-between; align-items: baseline;[{if not $smarty.foreach.shopLanguages.last}]margin-bottom:5px[{/if}]">
      <span style="font-weight: normal;margin-right: 5px;">[{$shopLanguage}]</span>
      <input type=text class="txt" style="flex: 1 0 auto" name="confaarrs[[{$module_var}]][[{$shopLanguage}]]"
             value="[{$localizedFields.$module_var.$shopLanguage|escape}]" />
    </div>
  [{/foreach}]
[{else}]
  [{$smarty.block.parent}]
[{/if}]
