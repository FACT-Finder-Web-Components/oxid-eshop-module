<div class="boxwrapper">

    [{assign var="oConfig" value=$oViewConf->getConfig()}]

    [{if $oConfig->getConfigParam("ffCampaigns")}]
        [{block name="ff_campaign_feedback_top"}]
            [{include file="ff/campaign/feedbacktext.tpl" label="above search result"}]
        [{/block}]
    [{/if}]

    [{include file="ff/campaign/search_advisor.tpl"}]
    <div class="toolbar toolbar-top">
        <div class="pull-left">
            [{include file="ff/result_count.tpl"}]
        </div>
        <div class="pull-right options">
            <div class="btn-group">[{include file="ff/sortbox.tpl"}]</div>
            <div class="btn-group">[{include file="ff/ppp.tpl"}]</div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="list-container">
        [{if $oConfig->getConfigParam("ffInfiniteScroll")}]
            [{include file="ff/infinite_scroll_record_list.tpl"}]
        [{else}]
            [{include file="ff/record_list.tpl"}]
        [{/if}]
    </div>

    <div class="toolbar toolbar-bottom">
        <div class="refineParams row clear bottomParams">
            [{include file="ff/paging.tpl"}]
        </div>
    </div>
    [{if $oConfig->getConfigParam("ffCampaigns")}]
        [{block name="ff_campaign_feedback_bottom"}]
            [{include file="ff/campaign/feedbacktext.tpl" label="below search result"}]
        [{/block}]
    [{/if}]
</div>
