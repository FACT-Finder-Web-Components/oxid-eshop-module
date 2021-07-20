function registerAddToCartListener(selector, productId) {
    document.addEventListener('WebComponentsReady', function () {
        const trackingHelper = factfinder.communication.Util.trackingHelper;

        function trackAddToCart(product) {
            factfinder.communication.trackingManager.cart({
                id: trackingHelper.getTrackingProductId(product),
                masterId: trackingHelper.getMasterArticleNumber(product),
                price: trackingHelper.getPrice(product),
                count: 1,
            });
        }

        const element = document.querySelector(selector);
        if (element) {
            element.addEventListener('click', function () {
                factfinder.communication.EventAggregator.addFFEvent({
                    type: 'getRecords',
                    recordId: productId,
                    idType: 'productNumber',
                    success: function (response) {
                        if (response && response[0]) {
                            const product = response[0];
                            trackAddToCart(product);
                        }
                    },
                });
            });
        }
    });
}
