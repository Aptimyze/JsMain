/**
	 * Binding qsb modify button
	 */
	 
	 var staticData="";
	 
	 
	$('#searchModify').bind('click', function() {
		
		if((!(typeof response ==="undefined") && response.stype=="Q") || isHomepage)
		{
				if(!$("#search_havePhoto").parent().hasClass('custom-checkbox-hp'))
					customCheckHP("search_havePhoto");
				$('#qsbModifyBar').removeClass("disp-tbl");
				$('#qsbModifyBar').addClass("disp-none");
				$('#qsb').removeClass("z4");
				if(!isHomepage)
				{
					$('#commonOverlay').fadeIn();
					$('#qsb-close').removeClass("disp-none");
					$('#qsb').addClass("layersZ");
				}
				else
					$('#qsb').addClass("z4");	
				$("#qsb").slideDown(700,function(){
					$('#qsb').removeClass("disp-none");
				});
				
				setStaticData();
		}
		else
		{
				var extraParam ="";
				if(!(typeof response ==="undefined") && response.searchid)
					extraParam ='?searchId='+response.searchid;
				var advanceSearchUrl = '/search/AdvancedSearch'+extraParam;
				window.location.href = advanceSearchUrl;
				return;	
		}
	});
	
	$('#qsb-close').bind('click', function() {
		$('#qsb-close').addClass("disp-none");
		$('#qsbModifyBar').addClass("disp-tbl");
		$('#qsbModifyBar').removeClass("disp-none");
		$('#qsb').removeClass("z7");
		$('#qsb').addClass("z4");
		$("#qsb").slideUp(7,function(){
			$('#qsb').addClass("disp-none");
			
    });
		
	});

$(".singleDD").each(function(){	

		(new SingleDD(this));
		
		 $(this).find(".nScroll").each(function()
		 {(new DropDown(this,"temp"));
		 });
		
});

$("#search_submit").bind("click",function()
{
		var valjson={};
		var fieldsArray = ['gender','lage','hage','location','mtongue','mstatus','religion','caste'];
		$.each(fieldsArray,function(key,obj){
			var ele = $("#search_"+obj+"Hid").val();
			var len  = ("sf_"+obj+"_").length;
			valjson[obj.toUpperCase()] = ele.substring(len);
		});
		valjson["GENDER"]=valjson["GENDER"]?valjson["GENDER"]:"F";
		valjson["LAGE"]=valjson["LAGE"]?valjson["LAGE"]:(valjson["GENDER"]=="F"?"18":"21");
		valjson["HAGE"]=valjson["HAGE"]?valjson["HAGE"]:valjson["LAGE"];
		valjson["PHOTO"] = $("#search_havePhoto").parent().hasClass("selected")?"Y":"";
		valjson =  JSON.stringify(valjson);
		
		var sid = $("#sf_sid").val();
		var addSid ='';
		if(sid!="")
			addSid = '&searchId='+sid;
		$('#search_form').html("<form action='/search/quick"+addSid+"' name='searchForm' method='post' style='display:none;'><input type='text' name='json' value='" + valjson + "' /></form>");
		document.forms['searchForm'].submit();
});
function customCheckHP(checkboxName) {
    var checkBox = $('input[id="' + checkboxName + '"]');
    $(checkBox).each(function() {
        $(this).wrap("<span class='custom-checkbox-hp'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function() {
        $(this).parent().toggleClass("selected");
    });
}

function setStaticData()
{
	
	if(isStorageExist() && !(localStorage.getItem("searchFormStaticData") === null))
		return true;
	var params = 'json={"searchForm":"2013-12-25 00:00:00"}';
	if(isHomepage)
		params +='&fromHomepage=1';
	var staticUrl = '/api/v1/search/searchFormData';
	var staticResponse="";
	$.myObj.ajax(
	{	
		url: staticUrl,
		dataType: 'json',
		type: 'GET', data: params,
		timeout: 60000,
		success: function(response) 
		{ 	
			staticResponse = response;
			if(!staticResponse["services"])
			{
				
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

function getStaticDataResponse()
{
	if(isStorageExist() && !(localStorage.getItem("searchFormStaticData") === null))
		return JSON.parse(localStorage.getItem("searchFormStaticData"));
	else if(staticData!='')
		return staticData;
}
