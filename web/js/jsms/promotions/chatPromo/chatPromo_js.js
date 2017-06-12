    $(document).ready(function(){

        var Phgt = $(window).height();
        var pht = $('#PLayer').height();
        if(Phgt < pht)
        {   
            $('#PLayer').parent().removeClass('fullheight').css('height',(pht+100));
        }     
        $('#chatPormoMS').css('height',Phgt);
        trackJsEventGA('CHAT PROMOTION','Display','MS','');
    })

    function goToPlayStore()
    {
       var partLink = '/static/appredirect?type=androidMobFooter';
       window.location.href = firstPart+partLink;
    }