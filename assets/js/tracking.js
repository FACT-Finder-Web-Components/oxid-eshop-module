function registerAddToCartListener({selector, productData, useSidAsUserId}) {
    if (typeof factfinder === 'undefined') {
        document.addEventListener('ffCommunicationReady', function () {
            init(selector, productData, useSidAsUserId);
        });
    } else {
        init(selector, productData, useSidAsUserId);
    }

    function init(selector, productData, useSidAsUserId) {
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

        function getUserId()
        {
            const userId = cookies['ff_user_id'];

            if (userId) {
                return userId;
            }

            if (useSidAsUserId) {
                return localStorage.getItem('ff_sid');
            }
        }

        function trackAddToCart(product) {
            factfinder.communication.Tracking.cart({
                id: trackingHelper.getTrackingProductId(product),
                masterId: trackingHelper.getMasterArticleNumber(product),
                price: trackingHelper.getPrice(product),
                title: trackingHelper.getTitle(product),
                count: getQuantity(),
                userId: getUserId()
            });
        }

        if (element) {
            element.addEventListener('submit', function (e) {
                trackAddToCart(productData);
            });
        }
    }
}
