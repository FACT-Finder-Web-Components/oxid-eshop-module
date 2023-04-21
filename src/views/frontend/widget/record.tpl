<ff-record class="productData col-xs-12 col-sm-6 col-md-4 productBox" style="[{$recordStyle|default:""}]">
    <form action="[{$oViewConf->getSelfActionLink()|escape}]" method="post">
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()|escape}]" />
        <input type="hidden" name="fnc" value="tobasket" />
        <div class="picture text-center">
            <a data-anchor="{{record.Deeplink}}" data-redirect="{{record.Deeplink}}" data-redirect-target="_self" title="{{record.Name}}">
                <img data-image="{{record.ImageName}}" alt="{{record.Name}}" class="img-responsive" />
            </a>
        </div>
        <div class="listDetails text-center">
            <div class="title">
                <a data-redirect="{{record.Deeplink}}" data-redirect-target="_self" data-anchor="{{record.Deeplink}}" title="{{record.Name}}">
                    <span>{{record.Name}}</span>
                </a>
            </div>
            <div class="price text-center">
                <div class="content">
                    <span class="lead text-nowrap">{{record.Price}} *</span>
                </div>
            </div>
            <div class="actions text-center">
                <div class="btn-group">
                    <button type="submit" class="btn btn-default hasTooltip" data-placement="bottom"
                            title="" data-container="body"
                            data-original-title="[{oxmultilang ident="ADD_TO_CART"}]">
                        <i class="fa fa-shopping-cart"></i>
                    </button>
                    <a class="btn btn-primary" data-redirect="{{record.Deeplink}}" data-redirect-target="_self" data-anchor="{{record.Deeplink}}">[{oxmultilang ident="MORE_INFO"}]</a>
                </div>
            </div>
        </div>
    </form>
</ff-record>
