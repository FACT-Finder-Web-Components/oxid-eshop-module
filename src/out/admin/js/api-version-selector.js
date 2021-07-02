function selectMethod(selector)
{
    let selectedVersion = selector.value;
    const excludedVersion = 'ng'

    if (selectedVersion === excludedVersion) {
        jQuery('[name="confstrs[ffAuthPrefix]"]').prop("disabled", true);
        jQuery('[name="confstrs[ffAuthPostfix]"]').prop("disabled", true);
    } else {
        jQuery('[name="confstrs[ffAuthPrefix]"]').prop("disabled", false);
        jQuery('[name="confstrs[ffAuthPostfix]"]').prop("disabled", false);
    }
}
