<form class="form search" role="form" onsubmit="return false;">
    <ff-searchbox class="input-group" suggest-onfocus="true" use-suggest="true" select-onclick="true">
        <input type="text" class="form-control" placeholder="[{oxmultilang ident="SEARCH"}]" aria-label="[{oxmultilang ident="SEARCH"}]"/>

        <ff-searchbutton class="input-group-btn">
            <button type="submit" class="btn btn-primary" title="[{oxmultilang ident="SEARCH_SUBMIT"}]">
                <i class="fa fa-search"></i>
            </button>
        </ff-searchbutton>
    </ff-searchbox>
</form>

[{block name="ff_suggest"}]
    [{include file="ff/suggest.tpl"}]
[{/block}]
