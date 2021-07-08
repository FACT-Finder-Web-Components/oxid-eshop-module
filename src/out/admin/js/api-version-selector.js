function selectMethod(selector)
{
    const selectedVersion = selector.value;
    const excludedVersion = 'ng'

    const changeDisabled = function (value) {
        document.querySelectorAll('[name^="confstrs[ffAuth"]').forEach(function (element) {
            element.disabled = value;
        });
    };

    if (selectedVersion === excludedVersion) {
        changeDisabled(true);
    } else {
        changeDisabled(false);
    }
}
