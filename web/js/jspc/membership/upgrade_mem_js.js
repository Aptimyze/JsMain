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
        eraseCookie('selectedVas');
        $(this).parent().addClass("selected");
        var selectedAddon = $(this).attr("id");
        if(checkEmptyOrNull(selectedAddon)){
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
    memUpgradeCheckbox("MONTH[]");
    
    //binding on click on upgrade main membership button
    bindMainMemUpgradeBtnClick();

    //preselect first vas
    preSelectVasForUpgradePage();

    $(".vascell").hover(function(event) {
        event.preventDefault();
    });
}

function bindMainMemUpgradeBtnClick(){
    $("#upgradeMainMemBtn").click(function(e){
        //flush vas selection when upgrade button clicked
        eraseCookie('selectedVas');
        //console.log("clicked on upgrade button");
        var upgradeType = "~$data.upgradeMembershipContent.type`",mainMem = "~$data.upgradeMembershipContent.upgradeMainMem`",mainMemDur = "~$data.upgradeMembershipContent.upgradeMainMemDur`";
        createCookie('mainMemTab', mainMem);
        createCookie('mainMem', mainMem);
        createCookie('mainMemDur', mainMemDur);
        //console.log("ankita",mainMem,mainMemDur,upgradeType);
        $.redirectPost('/membership/jspc', {'displayPage':3, 'mainMem':mainMem, 'mainMemDur':mainMemDur, 'device':'desktop' , 'upgradeMem':upgradeType});
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
        console.log("firstPresentVas",firstPresentVas);
        $('input[id="' + firstPresentVas + '"]').click();
    }
}