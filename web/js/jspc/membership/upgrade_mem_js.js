function memUpgradeVasCheckbox(checkboxName) {
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
        eraseCookie('selectedVas');
        $(this).parent().addClass("selected");
        var selectedAddon = $(this).attr("id");
        var vasKey=$("#"+selectedAddon).attr("vasKey");
    
        if(checkEmptyOrNull(selectedAddon)){
            var vasPrice = $("#"+selectedAddon+"_duration").attr("data-price");
            $("#selectedVasPrice_"+vasKey+" span").html(vasPrice);
            createCookie('selectedVas',selectedAddon);
        }
    });
}

function initializeUpgradePage(){
    eraseCookie('mainMem');
    eraseCookie('mainMemDur');
    //eraseCookie('selectedVas');

    checkLogoutCase(profileid);

    //balance the heights of current and upgrade membership section heights
    setLeftRightMemCompareEqualHeight();
    
    //set the input duration checkbox for vas
    memUpgradeVasCheckbox("MONTH[]");

    //preselect first vas
    preSelectVasForUpgradePage();

    bindVasPayBtnClick();
}

function bindVasPayBtnClick(){
    $(".vasPayBtn").click(function(e){
        eraseCookie('mainMem');
        eraseCookie('mainMemDur');
        var selectedVasCookie = readCookie('selectedVas'),selectedVasKey=$(this).attr("vasKey");
        var price = $("#selectedVasPrice_"+selectedVasKey+" span").html();
       
        if(price!=undefined && checkEmptyOrNull(selectedVasCookie)){
            $.redirectPost('/membership/jspc', {'displayPage':3, 'selectedVas':selectedVasCookie, 'device':'desktop'});
        } else {
            e.preventDefault();
        }
    });
}

function setLeftRightMemCompareEqualHeight(){
    var upgardeMemSectionHeight = $("#upgardeMemSection").height(),currentMemSectionHeight = $("#currentMemSection").height();
    if(upgardeMemSectionHeight != currentMemSectionHeight){
        if(upgardeMemSectionHeight > currentMemSectionHeight){
            $("#currentMemSection").height(upgardeMemSectionHeight);
        }
        else{
            $("#upgardeMemSection").height(currentMemSectionHeight);
        }
    }
}

function preSelectVasForUpgradePage(){
    var firstPresentVas;
    if($('#VASdiv').length != 0 && $('#VASdiv ul li').size()>0){
        firstPresentVas = $('#VASdiv ul li').first().attr("id");
    }
    
    if(checkEmptyOrNull(firstPresentVas)){

        $('input[id="' + firstPresentVas + '"]').click();
    }
}