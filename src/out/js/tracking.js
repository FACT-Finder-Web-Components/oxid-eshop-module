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
        const element = document.querySelector(selector);
        const amountInput = element.querySelector('#amountToBasket');

        function getQuantity()
        {
            if (ffTrackingSettings.addToCart.count === 'count_as_one') {
                return 1;
            }

            if (!amountInput) {
                return 1;
            }

            return parseInt(amountInput.value);
        }

        function trackAddToCart(product) {
            const quantity = getQuantity();

            factfinder.communication.Tracking.cart({
                id: trackingHelper.getTrackingProductId(product),
                masterId: trackingHelper.getMasterArticleNumber(product),
                price: trackingHelper.getPrice(product),
                title: trackingHelper.getTitle(product),
                count: quantity,
            });
        }


        if (element) {
            element.addEventListener('submit', function (e) {
                trackAddToCart(productData);
            });
        }
    }
}
