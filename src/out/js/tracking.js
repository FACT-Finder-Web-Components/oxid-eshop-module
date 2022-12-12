function registerAddToCartListener(selector, productId) {
    let trackingSent = false;
    const trackingHelper = factfinder.communication.Util.trackingHelper;

    function trackAddToCart(product) {
        factfinder.communication.Tracking.cart({
            id: trackingHelper.getTrackingProductId(product),
            masterId: trackingHelper.getMasterArticleNumber(product),
            price: trackingHelper.getPrice(product),
            title: trackingHelper.getTitle(product),
            count: 1,
        });
    }

    const element = document.querySelector(selector);

    if (element) {
        element.addEventListener('submit', function (e) {
            if (trackingSent === false) {
                e.preventDefault();
            }

            factfinder.communication.EventAggregator.addFFEvent({
                type: 'getRecords',
                recordId: productId,
                idType: 'productNumber',
                success: function (response) {
                    if (response && response[0]) {
                        const product = response[0];
                        trackAddToCart(product);
                    }

                    if (trackingSent === false) {
                        trackingSent = true;
                        e.target.submit();
                    }
                },
            });
        });
    }
}
