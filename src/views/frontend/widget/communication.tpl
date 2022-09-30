[{strip}]
<ff-communication [{foreach key=key item=value from=$oView->getCommunicationParams()}]
                  [{$key}]="[{$value|escape}]"
                  [{/foreach}]></ff-communication>
[{/strip}]


<script>
    document.addEventListener('ffCommunicationReady', ({ factfinder, searchImmediate }) => {
        const cookies = document.cookie.split('; ').reduce((acc, cookie) => {
            const cookieData = cookie.split('=');
            const [key, value] = cookieData;
            acc[key] = value;

            return acc;
        }, {});

        if (cookies['ff_user_id']) {
            console.warn('wk login');

            if (cookies['ff_has_just_logged_in']) {
                console.warn('Logged in!');
            }
        } else {
            console.warn('wk logout');

            if (cookies['ff_has_just_logged_out']) {
                console.warn('Logged out!');
            }
        }

        if ('[{$oView->getSearchImmediate()|escape:"javascript"}]') {
            searchImmediate();
        }
    });
</script>
