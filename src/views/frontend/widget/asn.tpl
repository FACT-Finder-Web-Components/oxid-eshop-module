<div class="ff-asn">
    <p class="text-uppercase h6 hidden-sm-down">[{oxmultilang ident="FF_FILTER_BY"}]</p>
    <ff-asn align="vertical" unresolved>

        <ff-asn-group for-group="CategoryPathROOT"   [{if $oView->getClassKey() eq "alist"}] class="hidden" [{/if}]>
            <div slot="groupCaption" class="groupCaption">
                <p class="h4 facet-title hidden-sm-down">{{group.name}}</p>
            </div>

            <ff-asn-group-element>
                <div slot="selected">
                    <span class="custom-checkbox">
                        <input type="checkbox" checked />
                        <span class="filterName">{{element.name}}</span>
                    </span>
                </div>
                <div slot="unselected">
                    <span class="custom-checkbox">
                        <input type="checkbox" />
                        <span class="filterName">{{element.name}}</span>
                    </span>
                </div>

                <div data-container="removeFilter">[{oxmultilang ident="FF_REMOVE_FILTER"}]</div>
                <div data-container="showMore">[{oxmultilang ident="FF_SHOW_MORE"}]</div>
                <div data-container="showLess">[{oxmultilang ident="FF_SHOW_LESS"}]</div>
            </ff-asn-group-element>
        </ff-asn-group>

        <ff-asn-group class="facet clearfix">
            <div slot="groupCaption" class="groupCaption">
                <p class="h4 facet-title hidden-sm-down">{{group.name}}</p>
            </div>

            <ff-asn-group-element>
                <div slot="selected">
                    <span class="custom-checkbox">
                        <input type="checkbox" checked />
                        <span class="filterName">{{element.name}}</span>
                    </span>
                </div>
                <div slot="unselected">
                    <span class="custom-checkbox">
                        <input type="checkbox" />
                        <span class="filterName">{{element.name}}</span>
                    </span>
                </div>

                <div data-container="removeFilter">[{oxmultilang ident="FF_REMOVE_FILTER"}]</div>
                <div data-container="showMore">[{oxmultilang ident="FF_SHOW_MORE"}]</div>
                <div data-container="showLess">[{oxmultilang ident="FF_SHOW_LESS"}]</div>
            </ff-asn-group-element>
        </ff-asn-group>

        <ff-asn-group-slider class="facet clearfix">
            <div slot="groupCaption" class="groupCaption">
                <p class="h4 facet-title hidden-sm-down">{{group.name}}</p>
            </div>

            <div data-container="removeFilter">[{oxmultilang ident="FF_REMOVE_FILTER"}]</div>
        </ff-asn-group-slider>
    </ff-asn>
</div>
