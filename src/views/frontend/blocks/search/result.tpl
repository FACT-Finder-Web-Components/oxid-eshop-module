<div class="boxwrapper">
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
        [{include file="ff/record_list.tpl"}]
    </div>

    <div class="toolbar toolbar-bottom">
        <div class="refineParams row clear bottomParams">
            [{include file="ff/paging.tpl"}]
        </div>
    </div>
</div>
