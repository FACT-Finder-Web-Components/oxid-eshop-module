function registerAddToCartListener(selector, productId) {
    const element = document.querySelector(selector);

    function waitForFactFinder() {
        return new Promise(resolve => {
            if (typeof window.factfinder !== 'undefined') {
                resolve(window.factfinder);
            } else {
                document.addEventListener('ffReady', function (event) {
                    resolve(event.factfinder);
                });
            }
        });
    }

    if (element) {
        element.addEventListener('click', function () {
            waitForFactFinder().then(function (factfinder) {
                const trackingHelper = factfinder.communication.Util.trackingHelper;
                factfinder.communication.EventAggregator.addFFEvent({
                    type: 'getRecords',
                    recordId: productId,
                    idType: 'productNumber',
                    success: function (response) {
                        if (response && response[0]) {
                            const product = response[0];
                            factfinder.communication.trackingManager.cart({
                                id: trackingHelper.getTrackingProductId(product),
                                masterId: trackingHelper.getMasterArticleNumber(product),
                                price: trackingHelper.getPrice(product),
                                count: 1,
                            });
                        }
                    },
                });
            });
        });
    }
}
