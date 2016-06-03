var staticData="";
window.onload = function (){
	var height = $(window).height();
	$("#searchMainForm").css({"height":height,"overflow":"auto"});        
	setStaticData();
	$("[dropdownmenu]").each(function(){
		var attr=$(this).attr("dropdownmenu").split(",");
		(new DropDown(this,attr));

	});
	
	$("#searchform_gender1").bind("click",function()
	{
		toggleSelected(this,"search_gender");
	});
	$("#searchform_gender2").bind("click",function()
	{
		toggleSelected(this,"search_gender");
	});
	$("#searchform_photo1").bind("click",function()
	{
		toggleSelected(this,"search_photo");
	});
	$("#searchform_photo2").bind("click",function()
	{
		toggleSelected(this,"search_photo");
	});
	
	$("#search_submit").bind("click",function()
	{
		var valjson={};
		var output = fieldArray.split(",");
		
		$.each(output,function(key,obj){
			var ele = $("#search_"+obj).find("div[data]");
			valjson[obj] = $(ele).attr("data");
		});

		if(!ISBrowser("UC") && !ISBrowser("safari"))
		{
			var myUrl = window.location.href;
			var randomnumber=$.now();
			var title = "Search Form"
		        var param = '?isMobile=Y&random'+randomnumber;
		        var stateObj = {};
		      
		        history.replaceState(stateObj,title,param);
		}
		var ele = $("#search_RELIGION").find("span[data]");
		valjson["CASTE"] = $(ele).attr("data");
		valjson["LOCATION"]= valjson["LOCATION"].replace("DE", "DE00"); 
		valjson["LOCATION"]= valjson["LOCATION"].replace("DE0000", "DE00"); 
		valjson["GENDER"] = $("#search_gender").attr("data");
		valjson["PHOTO"] = $("#search_photo").attr("data");
		valjson =  JSON.stringify(valjson);
		$('#search_form').html("<form action='/search/perform?QuickSearchBand=1' name='searchForm' method='post' style='display:none;'><input type='text' name='json' value='" + valjson + "' /></form>");
		document.forms['searchForm'].submit();
		
		
	});
		
        $('.showmorelink').on('click',function(){
                var rel = $(this).attr('rel');
                $(this).addClass('dn');
                $('#lessoptions'+rel).removeClass('dn');
                $('#moreoptblock'+rel).slideDown();
        });
        $('.showlesslink').on('click',function(){
               var rel = $(this).attr('rel');
                $(this).addClass('dn');
                $('#moreoptions'+rel).removeClass('dn');
                $('#moreoptblock'+rel).slideUp();
        });
	function toggleSelected(ele,parent)
	{
		if(!$(ele).hasClass("bg7"))
		{
			$(ele).siblings().removeClass("bg7");
			$(ele).siblings().children("div").removeClass("white").addClass("color2");
			$(ele).addClass("bg7");
			$(ele).children("div").addClass("white").removeClass("color2");
			var data = $(ele).attr("data");
			$('#'+parent).attr("data",data);
		}
		
	}
	
	
	
}

function setStaticData()
{
	var staticUrl = '/api/v1/search/searchFormData';
	var staticResponse="";
	$.ajax(
	{	
		url: staticUrl,
		dataType: 'json',
		type: 'GET', data: 'json={"searchForm":"2013-12-25 00:00:00"}',
		timeout: 60000,
		success: function(response) 
		{ 	
			CommonErrorHandling(response);
			staticResponse = response;
			if(!staticResponse["services"])
			{
				var message = "Something went wrong. Please try again later.";
				showSlider('.photoheader',message,'');
			}
			else
			{
				var staticValues= staticResponse["services"]["searchForm"]["data"];
				var data = JSON.stringify(staticValues);
				setStaticDataResponse(data);
				
			}

		},
		error: function(xhr) 
		{
			//var message = 'Connection Lost – Retry.';
			//showSlider('.photoheader',message,'');
		}
	})
	
}
function setStaticDataResponse(data)
{
	if(isStorageExist())
		localStorage.setItem("searchFormStaticData",data);
	else	
		staticData= JSON.parse(data);
}
function isStorageExist()
{
        var bVal = true;
        if(typeof(Storage)=='undefined')
            bVal = false;
        
        try{
            localStorage.setItem('testLS',"true");
            localStorage.getItem('testLS');
            localStorage.removeItem('testLS');
        }catch(e)
        {
            bVal = false;
        }
        return bVal;
}
