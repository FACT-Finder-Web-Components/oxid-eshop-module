<ff-paging class="col-xs-12 pagination-options" unresolved>
    <ff-paging-set state="currentPage == 1">
        <div class="prev-link ffw-page-item-container ffw-cursor">&larr; [{oxmultilang ident="PREVIOUS"}]</div>
        <ff-paging-item type="currentLink -1">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink" class="active">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink +1">{{caption}}</ff-paging-item>
        <ff-paging-item type="nextLink">[{oxmultilang ident="NEXT"}] &rarr;</ff-paging-item>
    </ff-paging-set>

    <ff-paging-set state="currentPage == pageCount">
        <ff-paging-item type="previousLink">&larr; [{oxmultilang ident="PREVIOUS"}]</ff-paging-item>
        <ff-paging-item type="currentLink -1">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink" class="active">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink +1">{{caption}}</ff-paging-item>
        <div class="next-link ffw-page-item-container ffw-cursor">[{oxmultilang ident="NEXT"}] &rarr;</div>
    </ff-paging-set>

    <ff-paging-set state="currentPage > 1 && currentPage < pageCount">
        <ff-paging-item type="previousLink">&larr; [{oxmultilang ident="PREVIOUS"}]</ff-paging-item>
        <ff-paging-item type="currentLink -1">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink" class="active">{{caption}}</ff-paging-item>
        <ff-paging-item type="currentLink +1">{{caption}}</ff-paging-item>
        <ff-paging-item type="nextLink">[{oxmultilang ident="NEXT"}] &rarr;</ff-paging-item>
    </ff-paging-set>
</ff-paging>
