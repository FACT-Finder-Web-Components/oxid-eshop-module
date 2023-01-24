function registerAddToCartListener(selector, productData) {
    if (typeof factfinder === 'undefined') {
        document.addEventListener('ffCommunicationReady', function () {
            init(selector, productData);
        });
    } else {
        init(selector, productData);
    }

    function init(selector, productData) {
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
                trackAddToCart(productData);
            });
        }
    }
}
