///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////

var commonData	 = {};
if(typeof(fromPage)=='undefined')
var fromPage="VDP";
var commonUrl="/contacts";
if(typeof(MES)=='undefined')
var MES=new Array();
var reloadOtherTab=0;
var contact_loader_big='<table align="center" width="100%" height="100%" style="text-align: center;"><tbody><tr><td><img src="IMG_URL/images/contactImages/loader_big.gif"></td></tr></tbody></table>';
var contact_loader_small='<table align="center" width="100%" height="100%" style="text-align: center;"><tbody><tr><td><img src="IMG_URL/images/searchImages/loader_small.gif"></td></tr></tbody></table>';
var clickHolderHeight=($(document).height()-50)+"px";
var clickHolderWidth=($(document).width()-50)+"px";
var upper_div='<style>.ce_357{}.aceh div{margin-left:0px;}</style><div class="crossCE" onclick="javascript:CloseCELayer(\'LAYERID\',event)">X</div><div id="div_LAYERID" class="profile-widget-container_layer">'+contact_loader_big+'</div>';

var conUrl={'Accept':commonUrl+"/PostAccept",'Notinterest':commonUrl+"/PostNotinterest",'Write':commonUrl+"/PostWrite",'ClickHere':commonUrl+"/PostCalldirect", "EOI": commonUrl+"/PostEOI", "Reminder": commonUrl+"/PostSendReminder","PreUnknown":commonUrl+"/PreUnknown"};
var preUrl={'Accept':commonUrl+"/PreAccept",'Notinterest':commonUrl+"/PreNotinterest",'Write':commonUrl+"/PreWrite","EOI": commonUrl+"/PreEoi", "Reminder": commonUrl+"/PreSendReminder","Details":commonUrl+"/PreContactDetails"};

var classes={'contact':'abs_cc',"VDP":'abs'};

var closeCELayer="",ERROR='ERROR',CALLED='CALLED';
$('[id^="expressLayer_"]').bind("click",function(){
	
        showLayer(this.id,"EOI","expressLayer");
});
$('[id^="acceptLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Accept","acceptLayer");
					
        
});
$('[id^="declineacceptLayer_"]').bind("click",function(){
		
        showLayer(this.id,"Accept","declineacceptLayer");
					
        
});
$('[id^="cancelAcceptLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Accept","cancelAcceptLayer");
					
});
$('[id^="notinterestLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Notinterest","notinterestLayer");
					
        
});
$('[id^="reminderLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Reminder","reminderLayer");
					
        
});
$('[id^="detailsLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Details","detailsLayer");
					
       
});
$('[id^="detailsLayerSecond_"]').bind("click",function(){
	
        showLayer(this.id,"Details","detailsLayerSecond");
					
       
});
$('[id^="writeLayer_"]').bind("click",function(){
	
        showLayer(this.id,"Write","writeLayer");
					
     
});
$('[id^="writeLayerSecond_"]').bind("click",function(){
	
        showLayer(this.id,"Write","writeLayerSecond");
					
     
});

function showLayer(id,tobestatus,maindiv,norequest)
{
	if(typeof(norequest)=='undefined')
		norequest=0;
	$("#"+id).unbind();
	var idArr=id.split("_");
	profid=idArr[1];
	
	//Getting left position on fto page.
	var styleLeft="";
	if(dID(id) && norequest)
	{
		if(typeof(getFtoStyleLeft)!='undefined')
			styleLeft=getFtoStyleLeft(id);	
	}
	
	inner_div=upper_div.replace(/LAYERID/g,profid);
	var pageClass=classes[fromPage];	
	var divcontent = $('<div id="over_'+profid+'" class="'+pageClass+'" align="left" '+styleLeft+'>'+inner_div+'</div>');
	updateClickHolderCE(true);
	$("#"+id).append(divcontent);
	
	closeCELayer=closeCELayerFnc(profid);
	commonData[profid]=JSON.parse(JSON.stringify(postDataVar));
	//commonData[profid]=postDataVar;
	
	commonData[profid]["id"]=profid;
	commonData[profid]["maindiv"]=maindiv;
	commonData[profid]["tobestatus"]=tobestatus;
	commonData[profid]["CALLED"]=0;
	var profiles_data=getReceiversLayer(profid);
	if(error_check(profid))
	{
		formData("profilechecksum",profiles_data,profid);
		if(norequest==0)
			sendRequest(preUrl[tobestatus],profid,1);
	}	
	//$('body').append(newdiv1);
	

}
function error_check(layerid)
{
	if(commonData[layerid][ERROR])
	{
		type=commonData[layerid]["tobestatus"];
		commonData[layerid][ERROR]="";
		var url = "/profile/nothing_selected.php?width=520&height=120&TYPE="+type+"&ajax_error=1";
		$.colorbox({href:url});
		CloseCELayer(profid);
		return 0;
	}	
	return 1;
	
}
function getReceiversLayer(data)
{
	selectProfile=new Array();	
	
	if(data=='multi' || data=='bottom')
	{
		var checkboxes=$('input[id^="checkbox_"]');
		 var st=0;
		 for(var i=0;i<checkboxes.length;i++)
		 {
			 if(checkboxes[i].checked)
			 {
				 selectProfile[st]=(checkboxes[i].id).replace("checkbox_","");
				 st++;
			 }
		 }
		 if(st>0)
		 {
			 data=selectProfile.join();
			}
			else
			{
				commonData[data][ERROR]=1;
			}
	}
		return data;
}
function closeCELayerFnc(id)
{
	
	var str="CloseCELayer('"+id+"',event)";
	return str;
}
function updateClickHolderCE(isTrue,event)
{
	    if(isTrue)
        {
                $("#clickHolderCE").css("height",clickHolderHeight);
                $("#clickHolderCE").css("width",clickHolderWidth);
        }
        else
        {
                if(closeCELayer)
                {
					CloseCELayer(profid,event);
					    //eval(""+closeCELayer);
                        closeCELayer="";
                }
                
                $("#clickHolderCE").css("height","0px");
                $("#clickHolderCE").css("width","0px");
        }
}

function CloseCELayer(id,e)
{
	var divid=commonData[id]["maindiv"]+"_"+id;
	var to_be_status=commonData[id]["tobestatus"];
	var main_div=commonData[id]["maindiv"];
	
	$("#"+divid).bind("click",function(){
		if(main_div=='fto')
			showftoLoader(divid,to_be_status,main_div);
    else
			showLayer(divid,to_be_status,main_div);
});
	AfterExpressAll(id);

	$("#over_"+id).remove();
	if(!e) e=window.event;
		if (e.stopPropagation)    e.stopPropagation();
	 if (e.cancelBubble!=null) e.cancelBubble = true;
	closeCELayer="";
	updateClickHolderCE(false);
	if(typeof(reloadAlbumPageData)!='undefined')
		reloadAlbumPageData();
		
	return false ;
}

///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
function onSelection(val)
{
	text_val=MES[val];
	text_val  = removeJunk(text_val);
	$("#draft").val(text_val);
}
function formData(name,val,layerid)
{
	if(!commonData[layerid])
		commonData[layerid]={};
	commonData[layerid][name]=val;	
	
	var dd=1;

}
function sendRequest(url,id)
{
	commonData[id][CALLED]=commonData[id][CALLED]+1;
	$.ajax({
				url: url,
				type: "POST",
				data: commonData[id],
				success: function(result){
				AfterPostRequestLayer(id,result);
				}
			});
}
function checkerrorsCE(result)
{
        var tempResult=result.replace( /[\s\n\r]+/, '');
        if(tempResult=="Login")
        {
                if(typeof(handleLoginLayer)!='undefined')
			handleLoginLayer();
		else
			$.colorbox({href:"/profile/login.php?SHOW_LOGIN_WINDOW=1"});
		
                        return "Please login to continue.";
                }
                if(tempResult.substr(0,5)=='ERROR' || tempResult=="A_E")
                        if(tempResult=="A_E")
                                return errorMes;
                else
                        return tempResult.substr(6,result.length);
        return "";
}
function AfterPostRequestLayer(id,result)
{
		if(checkerrorsCE(result))
		{
			result=checkerrorsCE(result);
		}
		var divid=commonData[id]["divname"]+"_"+id;
		
		if(!commonData[id]['notreload'])
			reloadOtherTab=id;
			
		commonData[id]['notreload']=0;
		$("#"+divid).html(result);
		
		if(typeof(updateTabData)!='undefined')
			updateTabData();
			
	 bindThickbox();		
}

function getProfiles(layerid)
{
	return layerid;
}
function showLoader(id,typeofloader)
{
	if(!typeofloader)
		typeofloader="big";
	if(typeofloader=='big')
		$("#"+id).html(contact_loader_big);
	else
		$("#"+id).html(contact_loader_small);
	
}
var id="id";


function getFormElements(layerid,formId)
{
	formData("id",layerid,layerid);
	
if(!formId || document[formId]!='undefined')
		formId="contact_engine";
		
	profilechecksum=getProfiles(layerid);
  formData("profilechecksum",profilechecksum,layerid);
	if(document[formId])
	{
		for(i=0; i<document[formId].elements.length; i++)
		{
			my_form_val = document[formId].elements[i].value;
			my_form_name = document[formId].elements[i].name;
			formData(my_form_name,my_form_val,layerid);
			
		}
	}	
}
function onAccept(layerid)
{	
	getFormElements(layerid);
	showLoader("div_"+layerid);
	formData("divname","div",layerid);
	sendRequest(conUrl.Accept,layerid);
	
}

function onNotInterested(layerid)
{	if(MES)
	{
		var dr_mes=MES['PRE_2'].replace(/&#039;/g,"'");
			if(dr_mes==$("#draft").val())
			$("#draft").val(MES['D1']);
        }		
	getFormElements(layerid);
	showLoader("div_"+layerid);
	formData("divname","div",layerid);
                sendRequest(conUrl.Notinterest,layerid);
	

}
function changeRadio(value)
{
	$("#interest").css("display","none");
	$("#notinterest").css("display","none");	
	if(value=="notinterest")
	{	
		
		updateDropdown(declineDrop);
		onSelection("D1");
		//$("#draft").val(MES["D1"]);
		$("#"+value).css("display","block");
		//button value
	}
	else
	{
		updateDropdown(acceptDrop);
		onSelection("PRE_2");
		//$("#draft").val(MES["PRE_2"]);
		$("#"+value).css("display","block");
		
	}
}
function updateDropdown(jsonArray)
{
	var str="";
	var selected='';
	for(key in jsonArray)
	{
		selected='';
		if(key=='D1' || key=='PRE_2' || key=='PRE_1')
			selected='selected';
			
		str+="<option  value='"+key+"' "+selected+">"+jsonArray[key]+"</option>";
	} 
  
  $("#draft_name").html(str); 
	
}
function SaveDraft(isreplace)
{
	var DRAFT_ID=$("#draft_name").val();
	var DRAFT_NAME=$.trim($("#after_draft_post_id").val());
	var DRAFT_MES=$("#draftMessage").val();
	var contactType=$("#contactType").val();
	if(DRAFT_NAME == "" )
	{
		$("#errId").html("Please fill the field before save");
		return (false);
	}
	if(DRAFT_ID == "" && isreplace==1)
    {
		$("#errId").html("Please select to replace with");
        return (false);
    }
	var saveData={'DRAFT_ID':DRAFT_ID,'DRAFT_NAME':DRAFT_NAME,'DRAFT_MES':DRAFT_MES,'contactType':contactType};
	var ce_url="/contacts/SaveDraft";
	showLoader("saveDraft");
	$.ajax({
				url: ce_url,
				type: "POST",
				data: saveData,
				success: function(result){
				AfterSaveDraft(result);
				}
			});
	
}
function AfterSaveDraft(result)
{
	var saveDraft = $("#saveDraft");
	if(result.substr(0,5)=='ERROR')
	{
		saveDraft.html("<div class='sp15'></div><div class='fs14'>"+result.substr(6,result.length)+"</div>");
	}
	else
	{
		saveDraft.html("<div class='sp15'></div><div class='fs14'><img src='IMG_URL/profile/images/small_tick.gif' align='absmiddle' /> Your message has  been saved as <b>"+result+"</b><div class='sp5'></div></div>");
	}
	
}


function onExpressInterestDetail(layerid)
{
		onExpressInterest(layerid);
}

function onNotInterestDetail(layerid)
{
		getFormElements(layerid,"contact_engine")
        //showLoader("contact_"+layerid);
        showLoader("div_"+layerid);
       // formData("divname","contact",layerid);
        formData("divname","div",layerid);
        formData("contactdetail","1",layerid);
		sendRequest(conUrl.Notinterest,layerid);
}
function onClickHereDetail(layerid)
{
	getFormElements(layerid,document.contact_engine);
    //showLoader("contact_"+layerid);
    showLoader("div_"+layerid);
    formData("divname","div",layerid);
    formData("CAll_DIRECT_ALLOWED","Y",layerid);

     sendRequest(conUrl.ClickHere,layerid);
}
function onCompleteNow()
{
	var url="/profile/viewprofile.php?ownview=1&EditWhatNew=incompletProfile";
	RedirectFromCE(url);
}

function onExpressInterest(layerid)
{
	
	{
					if(typeof(noExpressInterest)!='undefined')
					{
					
									$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=EOI&flag=1&width=700'});
									return;
					}
	}
  getFormElements(layerid);  
	showLoader("div_"+layerid);
  formData("divname","div",layerid);
  sendRequest(conUrl.EOI, layerid);
}

function onSendReminder(layerid) {
  getFormElements(layerid);
  showLoader("div_" + layerid);
  formData("divname","div",layerid);
  sendRequest(conUrl.Reminder, layerid);
}

function onWrite(layerid)
{
	getFormElements(layerid);
	formData("divname","div",layerid);
	showLoader("div_"+layerid);
	sendRequest(conUrl.Write,layerid);
	
}

function AfterExpressAll(id)
{
	var sendersArry=new Array();
	if(!commonData[id].ERROR && commonData[id]['divname'] && commonData[id]["CALLED"]>1)
	{
		if(fromPage=="contact" && commonData[id]["tobestatus"]!="Details"){
			profChecksum=commonData[id]["profilechecksum"];
			sendersArry=profChecksum.split(",");
			for(i=0;i<sendersArry.length;i++)
			{
				$("#checkbox_"+sendersArry[i]).prop("checked",false);
				$("#checkbox_"+sendersArry[i]).css("visibility","hidden");
				$("#"+commonData[id]["maindiv"]+"_"+sendersArry[i]).unbind("click");
				$("#"+commonData[id]["maindiv"]+"_"+sendersArry[i]).children().removeClass('green_btn').addClass("gray disable_link").css({height:"25px"});
				if(commonData[id]["tobestatus"] == "Accept"){
					$("#"+commonData[id]["maindiv"]+"_"+sendersArry[i]).children().val("Accepted");
					$("#notinterestLayer_"+sendersArry[i]).unbind("click");
					$("#notinterestLayer_"+sendersArry[i]).children().removeClass('gray_btn').addClass("gray disable_link").css({height:"25px"});
				}
                                if(commonData[id]["tobestatus"] == "Notinterest"){
                                        $("#notinterestLayer_"+sendersArry[i]).unbind("click");
                                        $("#notinterestLayer_"+sendersArry[i]).children().removeClass('gray_btn').addClass("gray disable_link").css({height:"25px"});
                                        $("#notinterestLayer_"+sendersArry[i]).children().val("Declined");
                                }				
			}
		}else{
		if(commonData[id]["tobestatus"]=="EOI"){
			profChecksum=commonData[id]["profilechecksum"];
			sendersArry=profChecksum.split(",");
			for(i=0;i<sendersArry.length;i++)
			{
				$("#checkbox_"+sendersArry[i]).prop("checked",false);
				$("#checkbox_"+sendersArry[i]).css("visibility","hidden");
				$("#"+commonData[id]["maindiv"]+"_"+sendersArry[i]).unbind("click");
				$("#"+commonData[id]["maindiv"]+"_"+sendersArry[i]).html("<span class='gray'> &nbsp;&nbsp;&nbsp;Interest Expressed</span>");
			}
		}
		}
	}
}
function unknownTab(maindiv,layerid,tabname)
{
	getFormElements(layerid);
	showLoader(maindiv+"_"+layerid);
	formData("divname",maindiv,layerid);
	formData("tabname",tabname,layerid);
	formData("notreload",1,layerid);
	formData("divname",maindiv,layerid);
	
	sendRequest(conUrl.PreUnknown,layerid);
}
function verify_layer_dp()
{
	$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=CONTACT&flag=1&width=700'});
}
function bindThickbox()
{
	$(".profile-widget-container .thickbox").unbind();
	$(".profile-widget-container .thickbox").bind("click",function(){$.colorbox({href:this.href});return false});
	$(".profile-widget-container_layer .thickbox").unbind();
	$(".profile-widget-container_layer .thickbox").bind("click",function(){$.colorbox({href:this.href});return false});
	
	$(".inner_div .thickbox").unbind();
	$(".inner_div .thickbox").bind("click",function(){$.colorbox({href:this.href});return false});

	
}
function RedirectFromCE(url)
{
	document.location=url;
}
