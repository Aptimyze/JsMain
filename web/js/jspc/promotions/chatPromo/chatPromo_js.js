   $(document).ready(function(){

        var Phgt = $(window).height();
        $('#chatPromoPc').css('height',Phgt);
    })

    function goToPlayStore()
    {
       var partLink = '/static/appredirect?type=androidMobFooter';
       window.location.href = firstPart+partLink;
    }