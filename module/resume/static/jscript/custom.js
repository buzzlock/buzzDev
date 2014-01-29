$Core.custom.action = function (oObj, sAction) {
    aParams = $.getParams(oObj.href);
    $(".dropContent").hide();
    switch (sAction) {
      case "delete":
        if (confirm(oTranslations['core.are_you_sure'])) {
            $.ajaxCall("resume.deleteField", "id=" + aParams.id);
        }
        break;
      default:
        $.ajaxCall("resume.toggleActiveField", "id=" + aParams.id);
        break;
    }
    return false;
};