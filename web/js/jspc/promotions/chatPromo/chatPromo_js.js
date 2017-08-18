   $(document).ready(function(){

        var Phgt = $(window).height();
        $('#chatPromoPc').css('height',Phgt);
        trackJsEventGA('CHAT PROMOTION','Display','PC','');
    })

    function goToPlayStore()
    {
       var partLink = '/static/appredirect?type=androidMobFooter';
       window.location.href = firstPart+partLink;
    }

