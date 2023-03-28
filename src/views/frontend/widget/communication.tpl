[{strip}]
<ff-communication [{foreach key=key item=value from=$oView->getCommunicationParams()}]
                  [{$key}]="[{$value|escape}]"
                  [{/foreach}]></ff-communication>
[{/strip}]


<script>
    [{if $oView->getTrackingSettings()}]
        const ffTrackingSettings = JSON.parse('[{$oView->getTrackingSettings()|@json_encode}]');
    [{else}]
        const ffTrackingSettings = {};
    [{/if}]

    document.addEventListener('ffCommunicationReady', ({ factfinder, searchImmediate }) => {
        const cookies = document.cookie.split('; ').reduce((acc, cookie) => {
            const cookieData = cookie.split('=');
            const [key, value] = cookieData;
            acc[key] = value;

            return acc;
        }, {});

        if (cookies['ff_user_id']) {
            factfinder.communication.sessionManager.setLoginData(cookies['ff_user_id']);

            if (cookies['ff_has_just_logged_in']) {
                factfinder.communication.Tracking.loginWithConfig();
            }
        } else {
            factfinder.communication.sessionManager.clearLoginData();

            if (cookies['ff_has_just_logged_out']) {
                factfinder.communication.sessionManager.clearAllSessionData();
            }

            if ('[{$oView->useSidAsUserId()|escape:"javascript"}]') {
                factfinder.communication.sessionManager.setLoginData(localStorage.getItem('ff_sid'));
            }
        }

        if ('[{$oView->getSearchImmediate()|escape:"javascript"}]') {
            [{if $oView->useInfiniteScroll()}]
                if (!!history.state?.scrollInfo) {
                    history.scrollRestoration = `manual`;
                    searchImmediate(history.state.scrollInfo.searchParams);
                    factfinder.elements.InfiniteScrolling.restoreScrollPosition(history.state.scrollInfo);
                } else {
                    searchImmediate();
                }
            [{else}]
                searchImmediate();
            [{/if}]
        }
    });
</script>
