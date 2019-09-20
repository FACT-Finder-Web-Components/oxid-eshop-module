[{strip}]
<ff-communication [{foreach key=key item=value from=$oView->getCommunicationParams()}]
                  [{$key}]="[{$value|escape}]"
                  [{/foreach}]></ff-communication>
[{/strip}]
