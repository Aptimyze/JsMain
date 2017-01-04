 var total = 0;
var current = 0;



var SMSContactsDivBinding = function(obj) {
$(obj).unbind('click');
var proChecksum=$(obj).attr('profilechecksum');
var ajaxConfig={};
ajaxData={'profileChecksum':proChecksum};
ajaxConfig.url="/phone/SMSContactsToMobile";
ajaxConfig.data=ajaxData;
ajaxConfig.type='POST';
ajaxConfig.success=function(response){

if(response.responseStatusCode=='0') 
$(obj).html('Details sent to '+response.mobile);
else 
$(obj).html(response.SMSError);
$(obj).removeClass('cursp').css('color','#999');

 };

jQuery.myObj.ajax(ajaxConfig);
$(obj).html($("#SMSLoader").html());
}


jQuery.fn.outerHtml = function() {
  return jQuery('<div />').append(this.eq(0).clone()).html();
};


var engagementBar = function(name) {
  this.ContainerHtml = $("#engagementContainer").html();
  this.innerHtml= $("#faceCard").html();
};

engagementBar.prototype = new container();

var photoRequestBar = function(name) {
  this.ContainerHtml = $("#photoRequestContainer").html();
  this.innerHtml= $("#smallCard1").html();
  this.viewAllInnerHtml=$("#smallCard2").html();
  this.emptyInnerHtml=$("#noFaceCard").html();
};


var interestReceivedBar = function(name) {
  this.ContainerHtml = $("#interestReceivedContainer").html();
  this.innerHtml= $("#interestReceivedCard").html();
  this.emptyInnerHtml=$("#noEngagementCard").html();
};


var filteredInterestBar = function(name) {
  this.ContainerHtml = $("#filteredInterestContainer").html();
  this.innerHtml= $("#interestReceivedCard").html();
  this.emptyInnerHtml=$("#noEngagementCard").html();
};

var expiringInterestBar = function(name) {
  this.ContainerHtml = $("#expiringInterestContainer").html();
  this.innerHtml= $("#interestReceivedCard").html();
  this.emptyInnerHtml=$("#noEngagementCard").html();
};

var MessageBar = function(name) {
  this.ContainerHtml = $("#messageContainer").html();
  this.innerHtml= $("#messageCard").html();
  this.emptyInnerHtml=$("#noEngagementCard").html();
};

MessageBar.prototype = new container();

var AcceptanceBar = function(name) {
  this.ContainerHtml = $("#messageContainer").html();
  this.innerHtml= $("#acceptanceCard").html();
  this.emptyInnerHtml=$("#noEngagementCard").html();
};

AcceptanceBar.prototype = new container();

photoRequestBar.prototype = new container();
interestReceivedBar.prototype = new container();
filteredInterestBar.prototype = new container();
expiringInterestBar.prototype = new container();

//MESSAGES
    
var messages = function() {
        this.name = "MESSAGES";
        this.containerName = this.name+"_Container";
		this.heading = "These people have Messaged you";
		this.headingId = this.name+"_head";
		this.isEngagementBar = 1;
		this.list = this.name+"_List";
		this.error=0;
        component.apply(this, arguments);
    };
    messages.prototype = Object.create(component.prototype);
    messages.prototype.constructor = messages;

    messages.prototype.post = function() {

      try{
      var profiles=this.data.profiles;
        var innerHtml="";
         var loopCount=0;
        if(profiles!=null)
			loopCount = profiles.length;
       var total = Math.ceil(loopCount/2);

        var current = 1;
        
		if(loopCount){
			for (i = 0; i < loopCount; i++) {
       // profiles[i]["count"]=4;
			   MSG_SEND='MSG_SEND';
			   MSG_UPGRADE='MSG_UPGRADE';
			   MSG_SEND=MSG_SEND.concat(i);
			   MSG_UPGRADE=MSG_UPGRADE.concat(i);
				innerHtml=innerHtml+this.innerHtml;
				innerHtml=innerHtml.replace(/\{\{USERNAME\}\}/g,profiles[i]["username"]);
			innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name);
      innerHtml=innerHtml.replace(/\{\{BlankMsg_MSG\}\}/g,profiles[i]["profilechecksum"]+'_BlankMsg_MSG');
				innerHtml=innerHtml.replace(/\{\{PROFILE_MESSAGE_CARD_ID\}\}/g,profiles[i]["profilechecksum"]+this.name+"_id");
				innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+profiles[i]["profilepic235url"]+"'");
				innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,profiles[i]["time"]);
				innerHtml=innerHtml.replace(/\{\{MESSAGE_DATE\}\}/g,profiles[i]["timetext"]);
				innerHtml=innerHtml.replace(/\{\{MESSAGE\}\}/g,profiles[i]["last_message"]);
		  innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,"/profile/viewprofile.php?profilechecksum="+profiles[i]["profilechecksum"]+"&"+this.data.tracking);
		  this.containerHtml=this.containerHtml.replace(/\{\{type\}\}/g,"MESSAGES_List");
				this.containerHtml=this.containerHtml.replace(/\{\{TOTAL_NUM\}\}/gi,total);
				this.containerHtml=this.containerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
         if(profiles[i]["count"]!='0')
         innerHtml=innerHtml.replace(/\{\{MESSAGE_COUNT\}\}/g,"("+profiles[i]["count"]+")");
         else
         {
          innerHtml=innerHtml.replace(/\{\{MESSAGE_COUNT\}\}/g,"");
         //innerHtml=innerHtml.replace(/\{\{CountShow\}\}/g,"disp-none");

       }
        //$("#MessageCount").addClass("disp-none");
				sendMessage = profiles[i]["buttonDetailsJSMS"]["canwrite"];
				if(sendMessage==null){
          innerHtml=innerHtml.replace(/\{\{opa40\}\}/g,"");
				   innerHtml=innerHtml.replace(/\{\{MSG_ID\}\}/g,MSG_UPGRADE);
           if(profiles[i]["gender"]=='M')
			 innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Only paid member can initiate message. Become a Paid member.");
			 else
       innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Only paid member can initiate message. Become a Paid member.");
       
       innerHtml=innerHtml.replace(/\{\{button\}\}/g,"UPGRADE MEMBERSHIP");
                  innerHtml = innerHtml.replace(/\{\{text_area_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name+'_textarea');
                  innerHtml=innerHtml.replace(/\{\{SEND_BUTTON_ID\}\}/g,profiles[i]["profilechecksum"]+"_MSG_UPGRADE_id");
			 innerHtml=innerHtml.replace(/\{\{MESSAGE_RESPONSE_ID\}\}/g,"MESSAGE_RESPONSE_"+i);
       innerHtml=innerHtml.replace(/\{\{active\}\}/g,"disabled");
			 innerHtml=innerHtml.replace(/\{\{color\}\}/g,"colr5");
       innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG\}\}/gi,"javascript:location.href='/profile/mem_comparison.php'");
				}
				 else{
          innerHtml=innerHtml.replace(/\{\{opa40\}\}/g,"opa40");
			 innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG\}\}/gi,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray[profiles[i]["buttonDetailsJSMS"]["buttons"]['primary'][0]["action"]]+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','message','"+i+"')");
       innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG_ERROR\}\}/g,"postActionError('"+profiles[i]["profilechecksum"]+"','"+this.name+"')");
				   innerHtml=innerHtml.replace(/\{\{MSG_ID\}\}/g,MSG_SEND);
				   innerHtml = innerHtml.replace(/\{\{text_area_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name+'_textarea');
					innerHtml=innerHtml.replace(/\{\{SEND_BUTTON_ID\}\}/g,profiles[i]["profilechecksum"]+"_MSG_SEND_id");
           innerHtml=innerHtml.replace(/\{\{MESSAGE_RESPONSE_ID\}\}/g,"MESSAGE_RESPONSE_"+i);
           innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Everything starts with a conversation");
			 innerHtml=innerHtml.replace(/\{\{button\}\}/g,"Reply");
			 innerHtml=innerHtml.replace(/\{\{active\}\}/g,"");  
			 innerHtml=innerHtml.replace(/\{\{color\}\}/g,"color11");   
				}

			}
			
			this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
			this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,this.data.total);
			$("#engagementContainer").after(this.containerHtml);
			$("#engagementContainer").addClass("disp-none");
		if(total>1)
			  $("#panelCounter_message").removeClass("opa50");
		//console.log(document.getElementById("slideTotal{{type}}"));
		//document.getElementById("slideTotal{{MESSAGES_List}}").textContent=total;
   // $("#"+profiles[i]["profilechecksum"]+"_MESSAGES_textarea").bind(clickEventType,function(){
     //     $("#"+profiles[i]["profilechecksum"]+"_BlankMsg_MSG").addClass("disp-none");
      //});

			var listName=this.list; 
			$("#prv-"+this.list).addClass('cursp').bind(clickEventType,function(){
				myjsSlider("prv-"+listName);					
			});
			$("#nxt-"+this.list).addClass('cursp').click(function(){
				myjsSlider("nxt-"+listName);
			});
			topSliderInt('init');
		}
		else
		{	
			this.noResultCase();
		}

		removeOtherDiv();
		photo_init();
	}

  catch(e){
  console.log('getting error '+e+' in function post of messages Object')

  }

  }


  
	messages.prototype.noResultCase = function() {
		
		this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+this.name);
		if(this.error){
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
		}
		else
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Messages you receive will appear here");
		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,'');
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,'');
		$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none")
		$("#disp_"+this.list).after(this.emptyInnerHtml);
		if(!this.error)
			$("#Error"+this.name).remove();
		$("#disp_"+this.list).remove();
		$("#seeAll"+this.list).remove();
		$("#panelCounter_message").remove();
	}

//messages


var acceptance = function() {
        this.name = "ACCEPTANCE";
		this.containerName = this.name+"_Container";
		this.heading = "These people have accepted you";
		this.headingId = this.name+"_head";
		this.isEngagementBar = 1;
		this.list = this.name+"_List";
		this.error=0;
        component.apply(this, arguments);
    };
    acceptance.prototype = Object.create(component.prototype);
    acceptance.prototype.constructor = messages;

    acceptance.prototype.post = function() {
        var profiles=this.data.profiles;
        var innerHtml="";
         var loopCount=0;
        if(profiles!=null)
		var	loopCount = profiles.length;
		
       var total = Math.ceil(loopCount/2);
        var current = 1;
    if(loopCount){
        for (i = 0; i < loopCount; i++) {
        innerHtml=innerHtml+this.innerHtml;
        ACCEPTANCE_SEND='ACCEPTANCE_SEND';
        ACCEPTANCE_UPGRADE='ACCEPTANCE_UPGRADE';
        CONTACT_ID='contactDiv';
        CONTACT_ID=CONTACT_ID.concat(i);
        ACCEPTANCE_SEND=ACCEPTANCE_SEND.concat(i);
        ACCEPTANCE_UPGRADE=ACCEPTANCE_UPGRADE.concat(i);
        innerHtml=innerHtml.replace(/\{\{USERNAME\}\}/g,profiles[i]["username"]);
        innerHtml=innerHtml.replace(/\{\{PROFILE_ACCEPTANCE_CARD_ID\}\}/g,profiles[i]["profilechecksum"]+this.name+"_id");
        innerHtml=innerHtml.replace(/\{\{MARITAL_STATUS\}\}/g,profiles[i]["mstatus"]);
        innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+profiles[i]["profilepic235url"]+"'");
        innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,profiles[i]["userloginstatus"]);
        this.containerHtml=this.containerHtml.replace(/\{\{TOTAL_NUM\}\}/gi,total);
        innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name);
        innerHtml=innerHtml.replace(/\{\{BlankMsg_MSG\}\}/g,profiles[i]["profilechecksum"]+'_BlankMsg_ACCEPTANCE');
        innerHtml=innerHtml.replace(/\{\{AGE\}\}/g,profiles[i]["age"]);      
        innerHtml=innerHtml.replace(/\{\{HEIGHT\}\}/g,profiles[i]["height"]);
        innerHtml=innerHtml.replace(/\{\{RELIGION\}\}/g,profiles[i]["religion"]);
        innerHtml=innerHtml.replace(/\{\{MTONGUE\}\}/g,profiles[i]["mtongue"]);
        innerHtml=innerHtml.replace(/\{\{LOCATION\}\}/g,profiles[i]["location"]);
        this.containerHtml=this.containerHtml.replace(/\{\{type\}\}/g,"ACCEPTANCE_List");
        innerHtml=innerHtml.replace(/\{\{INCOME\}\}/g,profiles[i]["income"]);
        innerHtml=innerHtml.replace(/\{\{contactDivId\}\}/g,CONTACT_ID);
        var caste = profiles[i]["caste"].split(':');
          innerHtml=innerHtml.replace(/\{\{CASTE\}\}/g,caste[caste.length-1]);
         if(profiles[i]["subscription_icon"])
			innerHtml=innerHtml.replace(/\{\{SUBSCRIPTION_STATUS\}\}/g,profiles[i]["subscription_icon"]);
		else
			innerHtml=innerHtml.replace(/\{\{SUBSCRIPTION_STATUS\}\}/g,"");
          innerHtml=innerHtml.replace(/\{\{OCCUPATION\}\}/g,profiles[i]["occupation"]);  
        innerHtml=innerHtml.replace(/\{\{contact1\}\}/g,'contact1'+i);
        innerHtml=innerHtml.replace(/\{\{contact2\}\}/g,'contact2'+i);
        innerHtml=innerHtml.replace(/\{\{contact3\}\}/g,'contact3'+i);
        innerHtml=innerHtml.replace(/\{\{postedById\}\}/g,'postedBy'+i);
        innerHtml=innerHtml.replace(/\{\{phone_view_Contact\}\}/g,'phone'+i);
        innerHtml=innerHtml.replace(/\{\{handled_contact\}\}/g,'profileHandled'+i);
        innerHtml=innerHtml.replace(/\{\{email\}\}/g,'email'+i);
          innerHtml=innerHtml.replace(/\{\{EDUCATION_STR\}\}/g,profiles[i]["edu_level_new"]);
          
        innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,"/profile/viewprofile.php?profilechecksum="+profiles[i]["profilechecksum"]+"&"+this.data.tracking+"&total_rec="+this.data.total+"&actual_offset="+(i+1)+"&contact_id="+this.data.contact_id);
        this.containerHtml=this.containerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
        sendMessage = profiles[i]["buttonDetailsJSMS"]["canwrite"];
			if(sendMessage==null){
        innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG\}\}/gi,"javascript:location.href='/profile/mem_comparison.php'");
        innerHtml=innerHtml.replace(/\{\{opa40\}\}/g,"");
        innerHtml=innerHtml.replace(/\{\{POST_ACTION_VIEWCONTACT\}\}/g,"postActionViewContact('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray["VIEWCONTACT"]+"','"+i+"','"+ACCEPTANCE_UPGRADE+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"')");
         innerHtml=innerHtml.replace(/\{\{ACCEPTANCE_ID\}\}/g,ACCEPTANCE_UPGRADE);
if(profiles[i]["gender"]=='M')
       innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Only paid member can initiate message. Become a Paid member.");
       else
       innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Only paid member can initiate message. Become a Paid member.");
        innerHtml=innerHtml.replace(/\{\{MESSAGE_RESPONSE_ID\}\}/g,"ACCEPTANCE_RESPONSE_UPGRADE"+i);
        innerHtml = innerHtml.replace(/\{\{text_area_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name+'_textarea');      
        innerHtml=innerHtml.replace(/\{\{SEND_BUTTON_ID\}\}/g,profiles[i]["profilechecksum"]+"_ACCEPTANCE_UPGRADE_id");
         innerHtml=innerHtml.replace(/\{\{button\}\}/g,"UPGRADE MEMBERSHIP");
         innerHtml=innerHtml.replace(/\{\{active\}\}/g,"disabled");
         innerHtml=innerHtml.replace(/\{\{POST_ACTION_VIEWCONTACT_CLOSE\}\}/g,"postActionViewContactClose('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray["VIEWCONTACT"]+"','"+i+"','"+ACCEPTANCE_UPGRADE+"')");
          innerHtml=innerHtml.replace(/\{\{color\}\}/g,"color5");
			}
			 else{
        innerHtml=innerHtml.replace(/\{\{opa40\}\}/g,"opa40");
        innerHtml=innerHtml.replace(/\{\{POST_ACTION_VIEWCONTACT\}\}/g,"postActionViewContact('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray["VIEWCONTACT"]+"','"+i+"','"+ACCEPTANCE_SEND+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"')");
        innerHtml=innerHtml.replace(/\{\{POST_ACTION_VIEWCONTACT_CLOSE\}\}/g,"postActionViewContactClose('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray["VIEWCONTACT"]+"','"+i+"','"+ACCEPTANCE_SEND+"')");
       innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG\}\}/gi,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray[profiles[i]["buttonDetailsJSMS"]["buttons"]['primary'][0]["action"]]+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','message','"+i+"')");
        innerHtml=innerHtml.replace(/\{\{POST_ACTION_MSG_ERROR\}\}/g,"postActionError('"+profiles[i]["profilechecksum"]+"','"+this.name+"')");
        innerHtml = innerHtml.replace(/\{\{text_area_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name+'_textarea');		  
        innerHtml=innerHtml.replace(/\{\{SEND_BUTTON_ID\}\}/g,profiles[i]["profilechecksum"]+"_ACCEPTANCE_SEND_id");
        innerHtml=innerHtml.replace(/\{\{ACCEPTANCE_ID\}\}/g,ACCEPTANCE_SEND);
        innerHtml=innerHtml.replace(/\{\{textPlaceholder\}\}/g,"Everything starts with a conversation");
         innerHtml=innerHtml.replace(/\{\{MESSAGE_RESPONSE_ID\}\}/g,"ACCEPTANCE_RESPONSE_"+i);
         innerHtml=innerHtml.replace(/\{\{button\}\}/g,"SEND");
         innerHtml=innerHtml.replace(/\{\{active\}\}/g,"");  
         innerHtml=innerHtml.replace(/\{\{color\}\}/g,"color11");       
			}
        
        //post action handling
		}
    
		//document.getElementById("slideTotalACCEPTANCE_List").textContent=total;
		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,this.data.total);
		$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none");
		if(total>1)
			$("#panelCounter_message").removeClass("opa50");
		var listName=this.list;
		$("#prv-"+this.list).addClass('cursp').bind(clickEventType,function(){
			myjsSlider("prv-"+listName);					
		});
		$("#nxt-"+this.list).addClass('cursp').click(function(){
			myjsSlider("nxt-"+listName);
		});
		topSliderInt('init');
    }
	else
	{
		this.noResultCase();
	}
	removeOtherDiv();
	photo_init();
 }

	acceptance.prototype.noResultCase = function() {
		this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+this.name);
		if(this.error)
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
		else
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Acceptances to interests you have sent will appear here.");
		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,'');
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,'');
		$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none")
		$("#disp_"+this.list).after(this.emptyInnerHtml);
		$("#disp_"+this.list).remove();
		if(!this.error)
			$("#Error"+this.name).remove();
		$("#seeAll"+this.list).remove();
		$("#panelCounter_message").remove();
		
	}



  var filteredInterest = function() {


		this.name = "FILTEREDINTEREST";
		this.containerName = this.name+"_Container";
		this.heading = "These people have sent interests to you.";
		this.headingId = this.name+"_head";
		this.isEngagementBar = 1;
		this.list = this.name+"_List";
		this.error=0;		
		component.apply(this, arguments);
    };
    filteredInterest.prototype = Object.create(component.prototype);
    filteredInterest.prototype.constructor = filteredInterest;
    
    filteredInterest.prototype.post = function() {

try{
		var profiles=this.data.profiles;
		var tracking = this.data.tracking;
		var interestsCount=0;
		var innerHtml="";
		var viewAllCard="";
		var noOfRes=this.data.no_of_results;
		var current = 1;
		var interestsCount=0;
		var totalCount=this.data.total;
		var showViewAll=0,totalPanels=0;
		if (totalCount>20) showViewAll=1;
        if(!noOfRes){
        	this.noResultCase(); return ;
        }
		else {
                    for (i = 0; i < noOfRes && interestsCount<20; i++) 
                       {
          if(++interestsCount==20 && showViewAll==1)
            break;
        innerHtml=innerHtml+this.innerHtml;
        innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name);
        innerHtml=innerHtml.replace(/\{\{ACCEPT_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['ACCEPT']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','accept','"+tracking+"','Y')");
        innerHtml=innerHtml.replace(/\{\{DECLINE_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['DECLINE']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','decline','"+tracking+"','Y')");
        innerHtml=innerHtml.replace(/\{\{PROFILE_FACE_CARD_ID\}\}/g,profiles[i]["profilechecksum"]+"_id");
        innerHtml=innerHtml.replace(/\{\{js-AlbumCount\}\}/gi,profiles[i]['album_count']);
        
        if(profiles[i]['album_count']=='0')
            innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,'disp-none'); 
        else 
            innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,''); 

        innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+profiles[i]["profilepic450url"]+"'");
        innerHtml=innerHtml.replace(/\{\{EDUCATION_STR\}\}/g,profiles[i]["edu_level_new"]);
        innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,profiles[i]["userloginstatus"]);
        innerHtml=innerHtml.replace(/\{\{OCCUPATION\}\}/g,profiles[i]["occupation"]);
        innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,'/profile/viewprofile.php?profilechecksum='+profiles[i]["profilechecksum"]+"&"+this.data.tracking+"&total_rec="+this.data.total+"&actual_offset="+(interestsCount)+"&contact_id="+this.data.contact_id);
        innerHtml=innerHtml.replace(/\{\{LOCATION\}\}/g,profiles[i]["location"]);
        innerHtml=innerHtml.replace(/\{\{INCOME\}\}/g,profiles[i]["income"]);
        var caste = profiles[i]["caste"].split(':');
        innerHtml=innerHtml.replace(/\{\{CASTE\}\}/g,caste[caste.length-1]);
        innerHtml=innerHtml.replace(/\{\{AGE\}\}/g,profiles[i]["age"]);
        innerHtml=innerHtml.replace(/\{\{HEIGHT\}\}/g,profiles[i]["height"]);
        innerHtml=innerHtml.replace(/\{\{RELIGION\}\}/g,profiles[i]["religion"]);
        innerHtml=innerHtml.replace(/\{\{MTONGUE\}\}/g,profiles[i]["mtongue"]);
			}
    var totalPanels = Math.ceil(interestsCount/4);
		}
    innerHtml=innerHtml+this.getCards(interestsCount,showViewAll);
    this.containerHtml=this.containerHtml.replace(/\{\{TOTAL_NUM\}\}/gi,totalPanels);  
    this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
    $("#totalFilteredInterestReceived").text(totalCount);
    if (!totalCount){
    temp2= $(this.containerHtml.trim());
    temp2.find("#seeAllId_FILTEREDINTEREST").addClass('disp-none');
    this.containerHtml=temp2.outerHtml();
    }
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,totalCount?totalCount:'');
    if($("#"+Object.name+"_Container").length == 1)
    { 

      $("#FILTEREDINTEREST_Container").html($(this.containerHtml.trim()).html());
    }
    else
   	$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none");

    
	if (totalPanels>=2)
        {
		var listName=this.list;
		$("#panelCounter_FILTEREDINTEREST").removeClass('disp-none');
		$("#arrowKeys_FILTEREDINTEREST").removeClass('opa50');
		$("#prv-"+this.list).addClass('cursp').bind(clickEventType,function(){
			myjsSlider("prv-"+listName);
		});
		$("#nxt-"+this.list).addClass('cursp').click(function()
                {
		  myjsSlider("nxt-"+listName);
		});

	}

        if($('#totalFilteredInterestReceived').text()>4)
          $('#seeAll_FILTEREDINTEREST_List').show();

              
            topSliderInt('init');
	    removeOtherDiv();
	    photo_init();

            
}
catch(e){
  console.log('getting error '+e+' in function post of interestReceived object');

}


}


filteredInterest.prototype.noResultCase = function() {
		this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+this.name);
		if(this.error)
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
		else
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Interests received by you which don't match your filter criteria will appear here");
		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,'');
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,'');
		$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none")
		$("#disp_"+this.list).after(this.emptyInnerHtml);
		$("#disp_"+this.list).remove();
		if(!this.error)
                    $("#Error"+this.name).remove();
		$("#seeAll_"+this.list).remove();
		$("#panelCounter_message").remove();
		$("#arrowKeys_"+this.name).remove();
}


	filteredInterest.prototype.getCards=function(interestsCount,showViewAll) {    
		var html="";
		if (showViewAll==1){
			viewAllCard=$("#viewAllCard li").html();
			
      viewAllCard=viewAllCard.replace(/myjs-dim9/g,'myjs-dim11');
      viewAllCard=viewAllCard.replace(/\{\{disp-none\}\}/g,'');
      viewAllCard=viewAllCard.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
     	var tempDiv=$('<li style="padding-top:0px;background:none"></li>');
		tempDiv.append(viewAllCard);
			
      html+=tempDiv.outerHtml();
      return html;
		}
                
                return "";
	}


  var expiringInterest = function() {
    this.name = "EXPIRINGINTEREST";
    this.containerName = this.name+"_Container";
    this.headingId = this.name+"_head";
    this.isEngagementBar = 1;
    this.list = this.name+"_List";
    this.error=0;   
    component.apply(this, arguments);
    };
    expiringInterest.prototype = Object.create(component.prototype);
    expiringInterest.prototype.constructor = expiringInterest;
    
    expiringInterest.prototype.post = function() {

try{
    var profiles=this.data.profiles;
    var tracking = this.data.tracking;
    var interestsCount=0;
    var innerHtml="";
    var viewAllCard="";
    var noOfRes=this.data.no_of_results;
    var current = 1;
    var interestsCount=0;
    var totalCount=this.data.total;
    var showViewAll=0,totalPanels=0;
    if (totalCount>20) showViewAll=1;
        if(!noOfRes){
          this.noResultCase(); return ;
        }
    else {
                    for (i = 0; i < noOfRes && interestsCount<20; i++) 
                       {
          if(++interestsCount==20 && showViewAll==1)
            break;
        innerHtml=innerHtml+this.innerHtml;
        innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name);
        innerHtml=innerHtml.replace(/\{\{ACCEPT_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['ACCEPT']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','accept','"+tracking+"','Y')");
        innerHtml=innerHtml.replace(/\{\{DECLINE_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['DECLINE']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','decline','"+tracking+"','Y')");
        innerHtml=innerHtml.replace(/\{\{PROFILE_FACE_CARD_ID\}\}/g,profiles[i]["profilechecksum"]+"_id");
        innerHtml=innerHtml.replace(/\{\{js-AlbumCount\}\}/gi,profiles[i]['album_count']);
        
        if(profiles[i]['album_count']=='0')
            innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,'disp-none'); 
        else 
            innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,''); 

        innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+profiles[i]["profilepic450url"]+"'");
        innerHtml=innerHtml.replace(/\{\{EDUCATION_STR\}\}/g,profiles[i]["edu_level_new"]);
        innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,profiles[i]["userloginstatus"]);
        innerHtml=innerHtml.replace(/\{\{OCCUPATION\}\}/g,profiles[i]["occupation"]);
        innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,'/profile/viewprofile.php?profilechecksum='+profiles[i]["profilechecksum"]+"&"+this.data.tracking+"&total_rec="+this.data.total+"&actual_offset="+(interestsCount)+"&contact_id="+this.data.contact_id);
        innerHtml=innerHtml.replace(/\{\{LOCATION\}\}/g,profiles[i]["location"]);
        innerHtml=innerHtml.replace(/\{\{INCOME\}\}/g,profiles[i]["income"]);
        var caste = profiles[i]["caste"].split(':');
        innerHtml=innerHtml.replace(/\{\{CASTE\}\}/g,caste[caste.length-1]);
        innerHtml=innerHtml.replace(/\{\{AGE\}\}/g,profiles[i]["age"]);
        innerHtml=innerHtml.replace(/\{\{HEIGHT\}\}/g,profiles[i]["height"]);
        innerHtml=innerHtml.replace(/\{\{RELIGION\}\}/g,profiles[i]["religion"]);
        innerHtml=innerHtml.replace(/\{\{MTONGUE\}\}/g,profiles[i]["mtongue"]);
      }
    var totalPanels = Math.ceil(interestsCount/4);
    }
    innerHtml=innerHtml+this.getCards(interestsCount,showViewAll);
    this.containerHtml=this.containerHtml.replace(/\{\{TOTAL_NUM\}\}/gi,totalPanels);  
    this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
    setTimeout(function(){ 
      $("#engBarInfoMessage").html('<span id="expiringCount">'+expiringCount + '</span> interests are expiring this week and will be removed from your Inbox. Please Accept/Decline');
    }, 50);
    
    $("#totalExpiringInterestReceived").text(totalCount);
    if (!totalCount){
    temp2= $(this.containerHtml.trim());
    temp2.find("#seeAllId_EXPIRINGINTEREST").addClass('disp-none');
    this.containerHtml=temp2.outerHtml();
    }
    this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,totalCount?totalCount:'');
    if($("#"+Object.name+"_Container").length == 1)
    { 
      $("#"+Object.name+"_Container").html($(this.containerHtml.trim()).html());
    }
    else
    $("#engagementContainer").after(this.containerHtml);
    $("#engagementContainer").addClass("disp-none");

    
  if (totalPanels>=2)
        {
    var listName=this.list;
    $("#panelCounter_EXPIRINGINTEREST").removeClass('disp-none');
    $("#arrowKeys_EXPIRINGINTEREST").removeClass('opa50');
    $("#prv-"+this.list).addClass('cursp').bind(clickEventType,function(){
      myjsSlider("prv-"+listName);
    });
    $("#nxt-"+this.list).addClass('cursp').click(function()
                {
      myjsSlider("nxt-"+listName);
    });

  }
  if($('#totalExpiringInterestReceived').text() > 4)
    $('#seeAll_EXPIRINGINTEREST_List').show();
            
            topSliderInt('init');
      removeOtherDiv();
      photo_init();

            
}
catch(e){
  console.log('getting error '+e+' in function post of interestReceived object');

}


}


expiringInterest.prototype.noResultCase = function() {
    this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+this.name);
    if(this.error)
      this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
    else
      this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Interests which expire in next 7 days will appear here. Respond to them immediately after they appear here");
    this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,'');
    this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,'');
    $("#engagementContainer").after(this.containerHtml);
    $("#engagementContainer").addClass("disp-none")
    $("#disp_"+this.list).after(this.emptyInnerHtml);
    $("#disp_"+this.list).remove();
    if(!this.error)
                    $("#Error"+this.name).remove();
    $("#seeAll_"+this.list).remove();
    $("#panelCounter_message").remove();
    $("#arrowKeys_"+this.name).remove();
}


  expiringInterest.prototype.getCards=function(interestsCount,showViewAll) {    
    var html="";
    if (showViewAll==1){
      viewAllCard=$("#viewAllCard li").html();
      
      viewAllCard=viewAllCard.replace(/myjs-dim9/g,'myjs-dim11');
      viewAllCard=viewAllCard.replace(/\{\{disp-none\}\}/g,'');
      viewAllCard=viewAllCard.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
      var tempDiv=$('<li style="padding-top:0px;background:none"></li>');
    tempDiv.append(viewAllCard);
      
      html+=tempDiv.outerHtml();
      return html;
    }
                
                return "";
  }


	var removeOtherDiv = function(){
	$.each(currentPanelArray, function(index,value){
      if(index!=currentPanelEngagement && value ==1)
      {
          $('#'+index).addClass("disp-none");
      }
    }); 

	}

  var interestReceived = function() {


		this.name = "INTERESTRECEIVED";
		this.containerName = this.name+"_Container";
		this.heading = "These people have sent interests to you.";
		this.headingId = this.name+"_head";
		this.isEngagementBar = 1;
		this.list = this.name+"_List";
		this.error=0;		
		component.apply(this, arguments);
    };
    interestReceived.prototype = Object.create(component.prototype);
    interestReceived.prototype.constructor = interestReceived;
    
    interestReceived.prototype.post = function() {

try{
		var profiles=this.data.profiles;
		var tracking = this.data.tracking;
		var filteredCount=0,interestsCount=0;
		var filteredArray=new Array();
		var innerHtml="";
		var viewAllCard="";
		var noOfRes=this.data.no_of_results;
		var current = 1;
		var interestsCount=0;
		var totalCount=this.data.total;
		var showViewAll=0,totalPanels=0;
		if (totalCount>20) showViewAll=1;
        if(!noOfRes){
        	this.noResultCase(); return ;
        }
		else {
			for (i = 0; i < noOfRes && interestsCount<20; i++) {
				if (profiles[i]['filtered']=='Y'){ filteredArray[filteredCount++]=profiles[i]; continue;} 
        else {
          if(++interestsCount==20 && showViewAll==1)
            break;
        }
				innerHtml=innerHtml+this.innerHtml;
        innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,profiles[i]["profilechecksum"]+'_'+this.name);
        innerHtml=innerHtml.replace(/\{\{ACCEPT_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['ACCEPT']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','accept','"+tracking+"','N')");
        innerHtml=innerHtml.replace(/\{\{DECLINE_LINK\}\}/g,"postActionMyjs('"+profiles[i]["profilechecksum"]+"','"+postActionsUrlArray['DECLINE']+"','" +profiles[i]["profilechecksum"]+"_"+this.name+"','decline','"+tracking+"','N')");
				innerHtml=innerHtml.replace(/\{\{PROFILE_FACE_CARD_ID\}\}/g,profiles[i]["profilechecksum"]+"_id");
			  innerHtml=innerHtml.replace(/\{\{js-AlbumCount\}\}/gi,profiles[i]['album_count']);
        
        if(profiles[i]['album_count']=='0')
        innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,'disp-none'); 
        else 
        innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,''); 

        innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+profiles[i]["profilepic450url"]+"'");
				innerHtml=innerHtml.replace(/\{\{EDUCATION_STR\}\}/g,profiles[i]["edu_level_new"]);
        innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,profiles[i]["userloginstatus"]);
				innerHtml=innerHtml.replace(/\{\{OCCUPATION\}\}/g,profiles[i]["occupation"]);
				innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,'/profile/viewprofile.php?profilechecksum='+profiles[i]["profilechecksum"]+"&"+this.data.tracking+"&total_rec="+this.data.total+"&actual_offset="+(interestsCount)+"&contact_id="+this.data.contact_id.split('_')[0]+"_INTEREST_RECEIVED");
        innerHtml=innerHtml.replace(/\{\{LOCATION\}\}/g,profiles[i]["location"]);
				innerHtml=innerHtml.replace(/\{\{INCOME\}\}/g,profiles[i]["income"]);
				var caste = profiles[i]["caste"].split(':');
          innerHtml=innerHtml.replace(/\{\{CASTE\}\}/g,caste[caste.length-1]);
				innerHtml=innerHtml.replace(/\{\{AGE\}\}/g,profiles[i]["age"]);
				innerHtml=innerHtml.replace(/\{\{HEIGHT\}\}/g,profiles[i]["height"]);
				innerHtml=innerHtml.replace(/\{\{RELIGION\}\}/g,profiles[i]["religion"]);
				innerHtml=innerHtml.replace(/\{\{MTONGUE\}\}/g,profiles[i]["mtongue"]);
			}
    var totalPanels = Math.ceil(interestsCount/4);
		}
    innerHtml=innerHtml+this.getCards(interestsCount,showViewAll,filteredArray,this.data.filtercount);
		this.containerHtml=this.containerHtml.replace(/\{\{TOTAL_NUM\}\}/gi,totalPanels);  
		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
    if (!totalCount){
    temp2= $(this.containerHtml.trim());
    temp2.find("#seeAllId_INTERESTRECEIVED").addClass('disp-none');
    this.containerHtml=temp2.outerHtml();
    }
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,totalCount?totalCount:'');
    if($("#"+Object.name+"_Container").length == 1)
    { 
      $("#INTERESTRECEIVED_Container").html($(this.containerHtml.trim()).html());
    }
    else
   	$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none");

    
	if (totalPanels>=2){
		var listName=this.list;
		$("#panelCounter_INTERESTRECEIVED").removeClass('disp-none');
		$("#arrowKeys_INTERESTRECEIVED").removeClass('opa50');
		$("#prv-"+this.list).addClass('cursp').bind(clickEventType,function(){
			myjsSlider("prv-"+listName);
		});
		$("#nxt-"+this.list).addClass('cursp').click(function(){
		  myjsSlider("nxt-"+listName);
		});

	}    
      if($('#totalInterestReceived').text()>4)
      $('#seeAllId_INTERESTRECEIVED').show();
	    topSliderInt('init');
	    removeOtherDiv();
	    photo_init();
}
catch(e){
  console.log('getting error '+e+' in function post of interestReceived object');

}


}


interestReceived.prototype.noResultCase = function() {
		if(this.error){
			this.emptyInnerHtml=this.emptyInnerHtml.replace('disp-none',"");
			this.emptyInnerHtml=this.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
			this.innerHtml=this.emptyInnerHtml;
			this.containerHtml=this.containerHtml.replace('boxslide','');
		}
		else{
		this.innerHtml=this.getCards(0,0,new Array(),0);
		} 

		this.containerHtml=this.containerHtml.replace(/\{\{INNER_HTML\}\}/g,this.innerHtml);
		this.containerHtml=this.containerHtml.replace(/\{\{SEE_ALL_TOTAL\}\}/g,'');
    temp2=$(this.containerHtml.trim());
    temp2.find("#seeAllId_INTERESTRECEIVED").addClass('disp-none');
    this.containerHtml=temp2.outerHtml();
		$("#engagementContainer").after(this.containerHtml);
		$("#engagementContainer").addClass("disp-none");
		if(this.error){$("#js-INTERESTRECEIVED_List").css('width','100%');}
			    topSliderInt('init');

}


	interestReceived.prototype.getCards=function(interestsCount,showViewAll,filteredArray,filteredTotalCount) {    
		var html="";
		if (showViewAll==1){
			viewAllCard=$("#viewAllCard li").html();
			
      viewAllCard=viewAllCard.replace(/myjs-dim9/g,'myjs-dim11');
      viewAllCard=viewAllCard.replace(/\{\{disp-none\}\}/g,'');
			viewAllCard=viewAllCard.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[this.name]);
     	tempDiv=$('<li style="padding-top:0px;background:none"></li>');
		tempDiv.append(viewAllCard);
			
      html+=tempDiv.outerHtml();
      return html;
		}
		var remainder=interestsCount%4;
		if (remainder==0 && interestsCount!=0) return html;
		if (interestsCount==0){
			tempDiv=$('<li></li>');
			tempDiv.append($("#noInterestsCard").outerHtml());
			html+=tempDiv.outerHtml();
			if (++remainder==4) return html;
		}
		if (filteredArray.length>0){
			clone=$("#filteredCard_dummy").clone(); 
			clone.removeClass('disp-none')
			imageDivs=clone.find(".filteredImage");
			anchorDivs=clone.find(".filteredAnchor");
			for (i=0;i<imageDivs.length && i<filteredArray.length;i++){
				imageDivs.eq(i).attr('src',filteredArray[i].profilepic120url);
			}
			for (i=0;i<anchorDivs.length && i<filteredArray.length;i++){
				anchorDivs.eq(i).attr('href',"/profile/viewprofile.php?profilechecksum="+filteredArray[i].profilechecksum+"&responseTracking=31").removeClass('vishid');
			}
			if (filteredTotalCount && filteredTotalCount>4) clone.find("#filteredMoreCount").removeClass('disp-none').css('display','table').find("span").html(filteredTotalCount-3);
		  // for above line first check filteredTotalCount to be non null as done above .. as if interests received is less than 21 then filtercount is null from api ..hence this would avoid javascript error. 
			temp1=$("<li></li>");
			temp1.append(clone.outerHtml());
			html+=temp1.outerHtml();
		}
		else {
			temp1=$("<li></li>");
			temp1.append($("#noFilteredCard").outerHtml());
			html+=temp1.outerHtml();   
		}
		if (++remainder==4) return html;

		if (remainder==2){
			temp1=$("<li style='width:454px;padding-top: 0px;margin-right: 0px;'></li>");
			temp1.append($("#infoCardDouble").outerHtml());
			html+=temp1.outerHtml();
			return html;
		}
		else if (remainder==3){
			temp1=$("<li style='padding-top: 0px;'></li>");
			clone=$("#infoCardSingle").clone();
			clone.removeClass('disp-none');
			temp1.append(clone.outerHtml());
			html+=temp1.outerHtml();
			return html;
		}
	}

