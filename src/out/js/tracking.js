function registerAddToCartListener(selector, productData, userId) {
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
        const cookies = document.cookie.split('; ').reduce((acc, cookie) => {
            const cookieData = cookie.split('=');
            const [key, value] = cookieData;
            acc[key] = value;

            return acc;
        }, {});

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
            const userId = cookies['ff_user_id'];

            factfinder.communication.Tracking.cart({
                id: trackingHelper.getTrackingProductId(product),
                masterId: trackingHelper.getMasterArticleNumber(product),
                price: trackingHelper.getPrice(product),
                title: trackingHelper.getTitle(product),
                count: quantity,
                userId
            });
        }


        if (element) {
            element.addEventListener('submit', function (e) {
                trackAddToCart(productData);
            });
        }
    }
}
