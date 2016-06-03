$(document).ready(function(){
    
    //$('#searchModify').unbind('click');
    //$('#searchModify').bind('click', function() {
                customCheckHP("search_havePhoto");
                //$('#qsbModifyBar').removeClass("disp-tbl");
                //$('#qsbModifyBar').addClass("disp-none");
                $('#qsb').removeClass("disp-none");
               
                //$('#qsb').removeClass("z4").addClass("z7"); Removed cos of GNB overlap on searchid expire page
                setStaticData();

        //});
    //$('#searchModify').click();
});
