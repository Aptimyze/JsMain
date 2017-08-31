var messageKeyName="";
var viewerImage = "";
var messageCount = "";
var Button= function(elementObj) {
  this.pagination=false;
  this.elementObj=elementObj;
  this.divName=this.elementObj.attr('id');
  this.layerDiv=this.elementObj.closest('#contactEngineLayerDiv');
  this.parent=this.elementObj.closest('.CEParent');
  this.messageCount = messageCount;
	var arr=this.divName.split('-');
	this.profileChecksum=arr[1];
	this.pageSource=arr[2];
	this.name=arr[0];
	this.undoLayer=this.elementObj.attr('undoLayer')=='1'?1:0;
	this.params=this.elementObj.attr('data');
	if(typeof(this.params)=="undefined")
		this.params="";
	this.url=actionUrl[this.name];
	this.error=0;
	this.postMessage=this.elementObj.attr('postData');
	this.displayObj=new ContactEngineCard(this.pageSource);	
  	this.displayObj.buttonObj=this;
  	if(this.name == "MESSAGE_WRITE")
	{
		this.pageName="M";
	}
	else
  		this.pageName=this.displayObj.name;
        
}


Button.prototype.request= function() {
	
	/* GA tracking */
	try{
		if(this.name=="IGNORE")
		{
			if(this.params==="&ignore=0")
				GAMapper("GA_CE" ,{action:"UNBLOCK"});
			else
				GAMapper("GA_CE" ,{action:"BLOCK"});
		}	
		else
			GAMapper("GA_CE" ,{action:this.name});
	}
	catch(e){}
if(this.name=='WRITE_MESSAGE_LIST')
    {
        this.params+=('&pagination=1');
        if(typeof this.MSGID != 'undefined')this.params+=('&MSGID='+this.MSGID);
        if(typeof this.CHATID != 'undefined')this.params+=('&CHATID='+this.CHATID);
    }

if(!this.url){prePostResponse(this.name,this.parent); return;}
if (!this.profileChecksum) return;
if(this.name=="MEMBERSHIP" || this.name=="EDITPROFILE")
{
	if(this.name=="MEMBERSHIP")
		{
			location.href="/profile/mem_comparison.php";
			return;
		}

	else
		{
			location.href="/profile/viewprofile.php?ownview=1";
			return;
		}

}
ajaxData=this.makePostDataForAjax(this.profileChecksum);
         $.myObj.ajax({
          type: "POST",
          dataType: "json",
          url: (this.url),
            beforeSend : function(data){
            	if(data.pageName =="search" ||data.pageName =="VSP" || data.pageName =="VDP" ||data.pageName =="VSP_VDP"  ||data.pageName =="CC" || data.pageName == "M")
            		if(data.parent[0]!=undefined)
						var divid = data.parent[0].id;
				if(divid !=undefined)
				{
					$("#"+divid).addClass("ce-blur");
					NProgress.configure({parent: '#'+divid});
					NProgress.start();
				}
				if(data.name =="MESSAGE_WRITE")
				{
					var messageKeyName="MESSAGE_WRITE-"+data.profileChecksum+"-"+data.pageSource;
					$("#"+messageKeyName).html("Sending")
				}
            },
          data:ajaxData,
          context: this,
          success: function(response,data) {
                if(data.name=='INITIATE')
			callAfterContact();
		if(data.name=="ACCEPT")
		{
			$('.js-showDetail'+data.profileChecksum).find(".showText").each(function(index, element) {
				$(this).next().show(),$(this).remove();
			});
		}
          	if(data.name =="MESSAGE_WRITE")
			{
				var messageKeyName="MESSAGE_WRITE-"+data.profileChecksum+"-"+data.pageSource;
				if($( "#"+messageKeyName+"-cEMessageText" ).val())
				{
					var mytuple = $('#mymessagetuple').html();
					var mymessage = '';
					mymessage = mytuple.replace(/\{time\}/g,removeNull("Sent"));
					mymessage = mymessage.replace(/\{myimage\}/g,removeNull(viewerImage));
					mymessage = mymessage.replace(/\{message\}/g,removeNull($( "#"+messageKeyName+"-cEMessageText" ).val().split('\n').join("</br>")));
					mymessage = mymessage.replace(/\{id\}/g,removeNull(messageCount));
					messageCount = messageCount+1;
					$("#list-"+data.profileChecksum).html($("#list-"+data.profileChecksum).html()+mymessage);
				}
				$("#msgListScroller-"+data.profileChecksum).mCustomScrollbar("scrollTo","bottom");
				$( "#"+messageKeyName+"-cEMessageText" ).css('height', 'auto')
				$( "#"+messageKeyName+"-cEMessageText" ).val("")
				$("#"+messageKeyName).html("Send Message")
				$( "#"+messageKeyName).removeClass("cursp bg_pink contactEngineIcon").addClass("bg10");
				$( "#"+messageKeyName).unbind();
			}
           else if((data.name=="INITIATE" || data.name=="REMINDER") && data.pageName=="VDP" && (response.actiondetails.redirect !=null && response.actiondetails.redirect ==true))
			{
				var queryStringParams=window.location.href.slice(window.location.href.indexOf('?') + 1);
				var url = '/search/viewSimilarProfile';
                                var form = $("<form action='" + url + "' method='post'>" +
                                   "<input type='hidden' name='profilechecksum' value='" + ProCheckSum + "' />"+
                                    "<input type='hidden' name='SIM_USERNAME' value='" + ViewedUserName + "' />"+
                                    "<input type='hidden' name='queryStringParams' value='" + queryStringParams + "' />"+
                                     "<input type='hidden' name='Stype' value='V'/>"+
                                     
                                     "<input id='hiddenVspInput' type='hidden' name='actions_buttons' value='' /></form>"); $('body').append(form);
                                     $("#hiddenVspInput").val(JSON.stringify(response));
                                form.submit();
			}
           else 
           {
				data.data = response;
				data.post();
				if(data.name=="DECLINE" && data.pageName=="VDP")
				{
					var address_url=window.location.href;
					 if(address_url.indexOf("?") >= 0){
						var hash;
						var pageSource='';
						var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
						for(var i = 0; i < hashes.length; i++)
						{
							hash = hashes[i].split('=');
							if(hash[0]=="contact_id")
								pageSource=hash[1];
						}
						if(pageSource!='' && (pageSource.indexOf("INTEREST_RECEIVED") >= 0 ||pageSource.indexOf("FILTERED_INTEREST") >= 0) && $("#show_nextListingProfile").length && $("#show_nextListingProfile")[0]!=undefined)
							$("#show_nextListingProfile")[0].click();
					}
					
				}
			}
          },
          error: function(response,data){
          data.error=1;
          data.noResultCase();
    	  },
    	  complete: function(data,settings)
    	  {
    	  	if( typeof(settings.context) != "undefined" && settings.context !== null)
		  	{	
				if(settings.context.pageName =="search" ||settings.context.pageName =="VSP" ||settings.context.pageName =="VSP_VDP" ||settings.context.pageName =="CC"||settings.context.pageName =="VDP" || settings.context.pageName == "M")
					if(settings.context.parent[0]!=undefined)
								var divid = settings.context.parent[0].id;
			}
			if(divid !=undefined)
			{
				NProgress.set(1.0);
				$("#"+divid).removeClass("ce-blur");
			}
			if(settings.context.name =="MESSAGE_WRITE")
			{
				var messageKeyName="MESSAGE_WRITE-"+settings.context.profileChecksum+"-"+settings.context.pageSource;
				$("#"+messageKeyName).html("Send Message")
		    	  }
		        }
		    }); 



}


Button.prototype.noResultCase= function() {

	if(this.pageName != "M")
	{
		innerLayerHtml=this.displayObj.postDisplay(this.data,this.profileChecksum,this.error);
		this.parent.find('#contactEngineLayerDiv').remove();
		this.parent.prepend(innerLayerHtml);
		$(".cEcontent").mCustomScrollbar();
	}
	else
	{
		$('#MESSAGE_WRITE_error').show().delay(5000).fadeOut('slow');;	
	}
	cECommonBinding();
	cECloseBinding(); 
}


Button.prototype.setPostActionData= function(data) {
	this.data=data;
	this.data.buttondetails="";
}

Button.prototype.post= function() {

this.actionDetails=this.data.actiondetails;
this.buttonDetails=this.data.buttondetails;
	
//remove layer after send_Message overlay
if((this.name=="SEND_MESSAGE"|| this.name=="WRITE_MESSAGE") && this.data.isSent)
{
  var contactLayerDiv=this.parent.find("#contactEngineLayerDiv").eq(0);
  contactLayerDiv.addClass("disp-none");
	contactLayerDiv.html("");
}

if(this.name=='REMOVE'){
prePostResponse(this.name,this.parent);
return;

}


  if(this.actionDetails)
{
	if(this.actionDetails.notused ==undefined ||!this.actionDetails.notused)
	{
		if($("#jsCcVSP-"+this.profileChecksum).length)
			$("#jsCcVSP-"+this.profileChecksum).css("display","none");
			
		innerLayerHtml=this.displayObj.postDisplay(this.data,this.profileChecksum,this.error);  
		this.parent.find('#contactEngineLayerDiv').remove();
		this.parent.prepend(innerLayerHtml);

		
	if(typeof(this.actionDetails.lastsent)!='undefined' && this.actionDetails.lastsent)
		{	
				var currentObj=this;
				currentObj.parent.find('.js-msgBoxForBinding').attr('lastSentSaved',currentObj.actionDetails.lastsent);
				currentObj.parent.find('.js-newMsgText').addClass('brdrl-2');

				lastSentFunction=function() {
					currentObj.parent.find('.js-newMsgText').removeClass('colr4').addClass('cursp');
				currentObj.parent.find('.js-lastSentText').addClass('colr4').removeClass('cursp');
				currentObj.parent.find('.js-msgBoxForBinding').attr('savedMsg',currentObj.parent.find('.js-msgBoxForBinding').val());
				currentObj.parent.find('.js-msgBoxForBinding').val(currentObj.parent.find('.js-msgBoxForBinding').attr('lastSentSaved'));
				currentObj.parent.find("#"+currentObj.actionDetails.writemsgbutton.id+"-"+currentObj.profileChecksum+"-"+currentObj.pageSource+"-cEMessageText").focus();
				currentObj.parent.find("#"+currentObj.actionDetails.writemsgbutton.id+"-"+currentObj.profileChecksum+"-"+currentObj.pageSource+"-cEMessageText").keyup();
		
				}
				lastSentFunction();
				currentObj.parent.find('.js-lastSentText').removeClass('disp-none').bind('click',function(){
				lastSentFunction();
					});
				writeMessageOnClickFunction=function() {
					currentObj.parent.find('.js-newMsgText').addClass('colr4').removeClass('cursp');
				currentObj.parent.find('.js-lastSentText').removeClass('colr4').addClass('cursp');
				currentObj.parent.find('.js-msgBoxForBinding').attr('lastSentSaved',currentObj.parent.find('.js-msgBoxForBinding').val());
				currentObj.parent.find('.js-msgBoxForBinding').val(currentObj.parent.find('.js-msgBoxForBinding').attr('savedMsg'));
				currentObj.parent.find("#"+currentObj.actionDetails.writemsgbutton.id+"-"+currentObj.profileChecksum+"-"+currentObj.pageSource+"-cEMessageText").focus();
				currentObj.parent.find("#"+currentObj.actionDetails.writemsgbutton.id+"-"+currentObj.profileChecksum+"-"+currentObj.pageSource+"-cEMessageText").keyup();

				}
				currentObj.parent.find('.js-newMsgText').bind('click',function(){
				writeMessageOnClickFunction();
			})	


		}
		$(".cEcontent").mCustomScrollbar();
		
		
		if(currentActionLayer=="Message")
		{
			messageKeyName=this.actionDetails.writemsgbutton.id+"-"+this.profileChecksum+"-"+this.pageSource;
			$( "#"+messageKeyName+"-cEMessageText" ).focus();
			MessageLayerTypeBinding(messageKeyName);
			 $( "#"+messageKeyName+"-cEMessageText" ).bind('keyup',function() {
				
				MessageLayerTypeBinding(messageKeyName);
			});
		}
		currentActionLayer="";
	}
}

if (this.buttonDetails)
{
if(this.buttonDetails.buttons!=null){

  
var newHtml=this.displayObj.buttonDisplay(this.buttonDetails,this);
  
	$("#cEButtonsContainer-"+this.profileChecksum+"-"+this.pageSource).html(newHtml);
cECommonBinding();
cECloseBinding();

  
}
else if(this.buttonDetails.button!=null){
	var newHtml=this.displayObj.singleButtonDisplay(this.buttonDetails.button,this,this.name);
	this.elementObj.replaceWith(newHtml);
	
}
}


//Bottom Ignore layer on VDP 
if(this.name=="IGNORE" && this.pageName=="VDP" )
{
    
        if(this.data.responseStatusCode==1)
        {
            showCustomCommonError(this.data.responseMessage,5000);return;
        }
            callAfterContact();
	if(ignoreLayerOpened==1){
		
		if(this.data.message!=undefined && this.data.message!=null)
			$("#ignoredText").html(this.data.message);
		else if(isIgnored=="1")
			$("#ignoredText").html("You have unblocked the user.");
		if(isIgnored=="1")
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").addClass("disp-none");
		else
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").removeClass("disp-none");
		$('.js-overlay').fadeIn(200,"linear",function(){ $('#ignore-layer').fadeIn(300,"linear")});
		if(this.buttonDetails.buttons[0].params!=undefined && this.buttonDetails.buttons[0].params!=null && isIgnored!="1")
		{
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data',this.buttonDetails.buttons[0].params);
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data',this.buttonDetails.buttons[0].params);
		}
		else
		{
			if(isIgnored=="1")
			{
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data','&ignore=1');
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data','&ignore=1');
			}	
			else
			{
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data','&ignore=0');
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data','&ignore=0');
			}
		}
		if(isIgnored=="1")
		{
			isIgnored="0";
			$("#ignoreProfileToolTip").html("Block Profile");
		}
		else
		{
			isIgnored="1";
			$("#ignoreProfileToolTip").html("Unblock Profile");
		}
		hpOverlayBinding();
	}
	else if(ignoreLayerOpened==0)
	{
		$('.js-overlay').unbind('click');
		$('#ignore-layer').fadeOut(300,"linear",function(){ $('.js-overlay').fadeOut(200,"linear")});	
		if(this.buttonDetails.buttons[0].params!=undefined && this.buttonDetails.buttons[0].params!=null && isIgnored!='1')
		{
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data',this.buttonDetails.buttons[0].params);
			$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data',this.buttonDetails.buttons[0].params);
		}
		else
		{
			if(isIgnored=="1")
			{
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data','&ignore=1');
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data','&ignore=1');
			}	
			else
			{
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data','&ignore=0');
				$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data','&ignore=0');
			}
		}
		if(isIgnored=="1")
		{
			isIgnored="0";
			$("#ignoreProfileToolTip").html("Block Profile");
		}
		else
		{
			isIgnored="1";
			$("#ignoreProfileToolTip").html("Unblock Profile");
		}	
		hpOverlayBinding();
	}
	else
	{
		$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-UNDO").attr('data','&ignore=1');
		$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageName+"-IGNORE").attr('data','&ignore=1');
		
		if(isIgnored=="1")
		{
			isIgnored="0";
			$("#ignoreProfileToolTip").html("Block Profile");
		}
		else
		{
			isIgnored="1";
			$("#ignoreProfileToolTip").html("Unblock Profile");
		}
		
	}
	ignoreLayerOpened=3;
	
}
if(this.name == "WRITE_MESSAGE_LIST" && this.pageName=="CC")
{
	var data = this.data;
	viewerImage = data.viewer;
	var innerHtml = $("#messageDisplaytuple").html();
	
	var profile = data.profile;
	
	innerHtml=innerHtml.replace(/\{age\}/g,profile.age);
	innerHtml=innerHtml.replace(/\{height\}/g,profile.height);
	innerHtml=innerHtml.replace(/\{mstatus\}/g,profile.mstatus);
	innerHtml=innerHtml.replace(/\{mtongue\}/g,removeNull(profile.mtounge));
	innerHtml=innerHtml.replace(/\{edu_level_new\}/g,removeNull(profile.edu_level_new));
	innerHtml=innerHtml.replace(/\{occupation\}/g,removeNull(profile.occupation));
	innerHtml=innerHtml.replace(/\{income\}/g,removeNull(profile.income));
	innerHtml=innerHtml.replace(/\{username\}/g,removeNull(profile.username));
	innerHtml=innerHtml.replace(/\{profilechecksum\}/g,removeNull(profile.profilechecksum));
	innerHtml=innerHtml.replace(/\{userloginstatus\}/g,removeNull(profile.userloginstatus));
	innerHtml=innerHtml.replace(/\{subscription_icon\}/g,removeNull(profile.subscription_icon));
	innerHtml=innerHtml.replace(/\{ccTupleImage\}/g,removeNull(profile.profilepic120url));
	var messages = data.messages;
	var mytuple = $('#mymessagetuple').html();
	var othertuple = $('#othermessagetuple').html();
	var message = "";
	if(typeof this.totalIndex=='undefined')this.totalIndex=1;
	$.each(messages,function( index, val ){	
		if(val.mymessage == "true")
		{
			var mymessage = '';
			mymessage = mytuple.replace(/\{time\}/g,removeNull(val.timeTxt));
			mymessage = mymessage.replace(/\{myimage\}/g,removeNull(data.viewer));
			mymessage = mymessage.replace(/\{message\}/g,removeNull(val.message.split('\n').join("</br>")));
			mymessage = mymessage.replace(/\{id\}/g,removeNull(this.totalIndex++));
			message = message+mymessage;
		}
		else
		{
			var mymessage = '';
			mymessage = othertuple.replace(/\{time\}/g,removeNull(val.timeTxt));
			mymessage = mymessage.replace(/\{otherimage\}/g,removeNull(data.viewed));
			mymessage = mymessage.replace(/\{message\}/g,removeNull(val.message.split('\n').join("</br>").replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,"")));
			mymessage = mymessage.replace(/\{id\}/g,removeNull(this.totalIndex++));
			message = message+mymessage;
		}
                mymessage = mymessage.replace(/\{id\}/g,index);
		messageCount = index+1;
	});
        var tempJObj=$(innerHtml);

        if(this.pagination){
            tempObj=$("#messageWindow").find('#list-'+profile.profilechecksum);
            tempLiObj=tempObj.find('li').eq(0);
            tempObj.prepend(removeNull(message));
            $("#msgListScroller-"+profile.profilechecksum).mCustomScrollbar('scrollTo',tempLiObj,{scrollInertia:0});
            $("#msgHistoryLoader").css('visibility','hidden');

        }
        else 
        {
        tempJObj.find('#list-'+profile.profilechecksum).prepend(removeNull(message));
	innerHtml=$('<div>').append(tempJObj.clone()).html();
	
        $("#messageWindow").html(innerHtml);
    }
		var typeArray = new Array("{ccTupleImage}","{otherimage}","{myimage}");
		$('img[dsrc]').each(function() {
			var src = $(this).attr("dsrc");
			if($.inArray(src,typeArray)<0)
			{
				$(this).attr("src",src);
			}
		});

        
        if(data.hasNext!=true)this.allMessageLoaded=true;
        this.MSGID=data.MSGID;        
        this.CHATID=data.CHATID;

        if(this.pagination==false){
            var requestObj =this;
            this.pagination=true;
	if(data.cansend=="true")
	{
		$("#WriteArea").show();
		var messageKeyName="MESSAGE_WRITE-"+this.profileChecksum+"-"+this.pageSource;
		$( "#"+messageKeyName+"-cEMessageText" ).focus();
		$( "#"+messageKeyName+"-cEMessageText" ).attr('placeholder','Write message');
		 $( "#"+messageKeyName+"-cEMessageText" ).bind('keyup',function(el) {
			if($( "#"+messageKeyName+"-cEMessageText" ).val().replace(/ /g, '').length>0)
			{
				
				$( "#"+messageKeyName).removeClass("bg10").addClass("cursp bg_pink contactEngineIcon");
				cECommonBinding();
				cECloseBinding();		
			}
			else
			{
				$( "#"+messageKeyName).removeClass("cursp bg_pink contactEngineIcon").addClass("bg10");
				$( "#"+messageKeyName).unbind();
			}
			
		});
	}
	else
	{
		$("#membershipArea").show();
	}
	$("#listingWindow").addClass('disp-none');
	$("#ccPaginationDiv").hide();
	$("#messageWindow").removeClass('disp-none');
	//
	$("#js-ccContainerMessage").removeClass('disp-none');
	$('html,body').animate({
        scrollTop: $("#ccSection").offset().top},
        'fast');
	
	var height = $(".msglist2").height() - 200;
	var height = "-"+height+"px";
	
    //$(".cEcontent").mCustomScrollbar("scrollTo","bottom");
    $( "#"+messageKeyName+"-cEMessageText" ).focus();
    var tempScroller=$("#msgListScroller-"+profile.profilechecksum);
    tempScroller.mCustomScrollbar({callbacks:{
                                                                onTotalScrollBackOffset:100,
								onTotalScrollBack:function(){if(requestObj.allMessageLoaded)return;$("#msgHistoryLoader").css('visibility','visible');requestObj.request();}
							}});
    tempScroller.mCustomScrollbar('scrollTo','bottom',{scrollInertia:0});                                                
    }
    
    }
$( "#backToMessage" ).click(function() {
	$("#messageWindow").addClass('disp-none');
	$("#messageWindow").html("");
  $("#listingWindow").removeClass('disp-none');
  $("#ccPaginationDiv").show();
  //reload listing on back
	performCCListingAction($("#HorizontalTab"+activeHorizontalTabInfoID));	
});
  cECommonBinding();
  cECloseBinding(); 
  
  
}
  
Button.prototype.makePostDataForAjax= function(profileChecksum) {

	var paramArray=this.params.split('&');

	var postData={};
	postData['profilechecksum']=profileChecksum;
	postData['channel']='pc';
	postData['pageSource']=this.pageName;
	if(this.name=="SEND_MESSAGE"|| this.name=="WRITE_MESSAGE" || this.name=="MESSAGE_WRITE")
		postData['draft']=$("#"+this.name+"-"+this.profileChecksum+"-"+this.pageSource+"-cEMessageText").val();
	for (i=0;i<paramArray.length;i++)
	{
		if (!paramArray[i]) continue;
		var  arr=paramArray[i].split('=');
		postData[arr[0]]=arr[1];
	}
	return postData;

}


function MessageLayerTypeBinding(messageKeyName)
{
	if($( "#"+messageKeyName+"-cEMessageText" ).val().replace(/ /g, '').length>0)
		{
			if(PAGETYPE=="CC")
				$( "#"+messageKeyName).removeClass("opa40").addClass("cursp contactEngineIcon");	
			else
				$( "#"+messageKeyName).removeClass("bg10").addClass("bg_pink contactEngineIcon");
			cECommonBinding();
			cECloseBinding();		
		}
		else
		{
			if(PAGETYPE=="CC")
				$( "#"+messageKeyName).removeClass("contactEngineIcon cursp").addClass("opa40");
			else
				$( "#"+messageKeyName).removeClass("bg_pink contactEngineIcon").addClass("bg10");
			$( "#"+messageKeyName).unbind();
		}
}
