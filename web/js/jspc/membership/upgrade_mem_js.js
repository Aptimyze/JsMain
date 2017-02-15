function memUpgradeCheckbox(checkboxName) {
    var checkBox = $('input[name="' + checkboxName + '"]');
    $(checkBox).each(function() {           
        $(this).wrap("<span class='customMem-checkbox'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function() {          
        $(checkBox).each(function() {               
            $(this).parent().removeClass("selected");               
        });
        $(this).parent().addClass("selected");
    });
}