
$(document).ready(function(){
    $("#head_logout").bind("click", function(){
      logOutCheck(SITE_URL+"/profile/logout.php");
      });
    $("#head_logout").css("cursor","pointer");

    $("#outer_setting").bind("mouseover", function(){
      showSetting(1);
      });
    $("#outer_setting").bind("mouseout", function(){
      showSetting(0);
      });
    $("#outer_setting").css("cursor","pointer");
    $("#inner_setting").bind("mouseout", function(){
      showSetting(0);
      });
    $("#inner_setting").bind("mouseover", function(){
      showSetting(1);
      });
    $("#closeBand").bind("click", function(){
      setBand(0);
      });
    $("#openBand").bind("click", function(){
      setBand(1);
      });
    if(typeof(showSearchBand)=="undefined")
      showSearchBand=0;
    if(showSearchBand != 0)
      setBand(showSearchBand);
    else
      setBand(0);

    setTimeout("load_all_deferred()",2000);
    check_thickbox_command();
	
});	
	function logOutCheck(param){
			if(top.logOut)
					top.logOut();
			if(top.profileId)
					param=param+"?profileId="+top.profileId;
			
			top.location.href=param;
			return true;
	}
	function checkLogOut(){
		if(top.js_window){
			if(top.document.getElementById("browseBottom") && top.document.getElementById("browseBottom").style.display=="block"){
				top.document.getElementById("browseBottom").style.display="none";
				top.document.getElementById("browseBottom").style.visibility="hidden";
			}
			if(top.logOut)
				top.logOut();

		}
		return true;
	}
	function showSetting(wht)
	{
		if(wht)
		{
			$("#inner_setting").show();
		}
		else
			$("#inner_setting").hide();
	}
	var isOpenSB=0;
	
	function setBand (state)
	{
    //open/close the header band
    if (state)
    {   
      if(isOpenSB==0)
      {
        if(typeof(seoField) =="undefined")
          displayResult(searchId,SITE_URL);
        else
          displayResult(searchId,SITE_URL,seoFlag,seoField,seoValue);

        isOpenSB=1;
      }
      $("#topSearchBand").show();
      $("#advancedSearch").show();
      $("#closeBand").show();
      $("#openBand").hide();
    } 
    else 
    {
      $("#topSearchBand").hide();
      $("#advancedSearch").hide();
      $("#closeBand").hide();
      $("#openBand").show();

    }
	} 
	function load_all_deferred()
	{
		//Check screen size
		if(screen.width<=800)
		{
			if((screen.width-10)>document.body.offsetWidth)
			{
					parent.document.body.style.width=(screen.width+30)+"px";
			}
		}
		else
		{
			if(parent.document!=document)
			{
				parent.document.body.style.overflow="hidden";
				parent.document.scroll="no";
				parent.document.body.scroll="no";
			}
		}
	
		//Load google plus
		if(!user_login && google_plus)
		{
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;    po.src = 'https://apis.google.com/js/plusone.js';    var s = document.getElementsByTagName('script')[0];  s.parentNode.insertBefore(po, s);
		}
		
		

        //Work only for detailed page, where banner height is adjusted with profile information
        var profileAds=$("#profileAds").attr('offsetHeight');
        var profileData=$("#profileData").attr('offsetHeight');
        if(typeof(profileAds)!='undefined')
            if(profileAds>profileData)
                $("#profileData").css("height",profileAds);
	}
