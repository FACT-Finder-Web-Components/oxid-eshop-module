function selectMethod(selector)
{
    const selectedVersion = selector.value;
    const excludedVersion = 'ng'

    if (selectedVersion === excludedVersion) {
        jQuery('[name="confstrs[ffAuthPrefix]"]').attr("disabled", true);
        jQuery('[name="confstrs[ffAuthPostfix]"]').attr("disabled", true);
    } else {
        jQuery('[name="confstrs[ffAuthPrefix]"]').attr("disabled", false);
        jQuery('[name="confstrs[ffAuthPostfix]"]').attr("disabled", false);
    }
}
