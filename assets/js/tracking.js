function registerAddToCartListener({selector, productData, useSidAsUserId}) {
    if (typeof factfinder === 'undefined') {
        document.addEventListener('ffCoreReady', function () {
            init(selector, productData, useSidAsUserId);
        });
    } else {
        init(selector, productData, useSidAsUserId);
    }

    function init(selector, productData, useSidAsUserId) {
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
            let cartObj = {
                id: product.record.ProductNumber,
                masterId: product.record.Master,
                price: product.record.Price,
                title: product.record.Name,
                count: getQuantity(),
                sid: JSON.parse(localStorage.ffwebco).sid,
            }

            factfinder.tracking.cart([cartObj]);
        }

        if (element) {
            element.addEventListener('submit', function (e) {
                trackAddToCart(productData);
            });
        }
    }
}
