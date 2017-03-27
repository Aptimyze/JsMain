var clickEventType="click", cssBrowserAnimProperty=null,sliderNav={'VERIFIEDMATCHES_List':1,'DAILYMATCHES_List':1,'JUSTJOINED_List':1,'LASTSEARCH_List':1,'DESIREDPARTNERMATCHES_List':1,'INTERESTRECEIVED_List':1,'FILTEREDINTEREST_List':1,'EXPIRINGINTEREST_List':1 };

function topSliderInt(param){
	if(param=="init")
	{	
		$('ul.boxslide').each(function(){			
			var ulwid = 0;
			$(this).children("li").each(function(){			
				var Twidth =$(this).outerWidth( true );
				ulwid+=Twidth;		 
			});			
			$(this).css('width',ulwid);
		});
	}	
}

var photo_init = function(){
  $("img").each(function(){
  if($(this).hasClass("imageReplace")){
    var self = $(this);
    var newImg = new Image;
    newImg.onload = function() {
      self.attr('src',this.src);
    }
    if($(this).attr('data-src')!=undefined)
		newImg.src = $(this).attr('data-src');
  }
  });
};
$(function(){
	
	$('.scntrl').click(function(){	
			myjsSlider( $(this).attr('id'));
	});
	topSliderInt('init');
	
});

	function myjsSlider(id)
	{ 
    try{
     var elem = $('#'+id);
		elem.unbind(clickEventType);
		var getID,b,getWidth,visWidth,getLeft,p;					
			getID = id; 
			b= getID.split('-');
			getWidth =$('#js-'+b[1]).width();
			visWidth = $('#disp_'+b[1]).width();
      var idList = b[1].split('_');
			p=Math.abs($('#js-'+b[1]).position().left);
      var idList = (b[1].split('_'));
      var totalBoxes = getTotalBoxes(idList[0]);
      if(!sliderNav[b[1]])
      {
        sliderNav[b[1]]=1;
      }

			if((b[0]=="nxt")&&(getWidth>visWidth))
			{	

        diff=Math.floor(getWidth-p-visWidth);
				if(diff>0)
				{ 
          var currBox=sliderNav[b[1]];
          sliderNav[b[1]] = ++currBox;
        if(currBox == totalBoxes)
          $("#nxt-"+idList[0]+'_List').hide();
          $("#prv-"+idList[0]+'_List').show();
					p=p+visWidth;

					$('#js-'+b[1]).animate({left:-p}, 500, function() {
					// Animation complete.
					elem.bind(clickEventType,function(){
								myjsSlider(id);						
							});
				  }); 


				}
				else{
          
					setTimeout(function(){elem.bind(clickEventType,function(){myjsSlider(id);});},100);
        }
       
        tempDiv=document.getElementById("slideCurrent"+b[1]);
          if (tempDiv){
        currentPanel=parseInt((tempDiv).textContent);
        if (!currentPanel) currentPanel=1;
        tempDiv=document.getElementById("slideTotal"+b[1]);
        totalPanels=(parseInt(tempDiv.textContent));
			   if(currentPanel<totalPanels){
            currentPanel=currentPanel+1;
            document.getElementById("slideCurrent"+b[1]).textContent=currentPanel;
          }
        }
    
      }
			else
			{				
				if(p!=0)
				{
					p=-(p-visWidth);
					if(Math.floor(p)<=0){

              var currBox=sliderNav[b[1]];
              sliderNav[b[1]] =--currBox;
              if(currBox == 1)
              {
                $("#prv-"+idList[0]+'_List').hide();
              }
              $("#nxt-"+idList[0]+'_List').show();

						$('#js-'+b[1]).animate({left:p},500, function() {
						// Animation complete.
						elem.bind(clickEventType,function(){
								myjsSlider(id);						
						});});

             
          }
					else
						setTimeout(function(){elem.bind(clickEventType,function(){myjsSlider(id);});},100);
				  
				}
				else
					setTimeout(function(){elem.bind(clickEventType,function(){myjsSlider(id);});},100);
			        tempDiv=document.getElementById("slideCurrent"+b[1]);
      	 if (tempDiv){
        currentPanel=parseInt((tempDiv).textContent);
        if (!currentPanel) currentPanel=1;
        tempDiv=document.getElementById("slideTotal"+b[1]);
        totalPanels=parseInt(tempDiv.textContent);
         if(currentPanel>1){
            currentPanel=currentPanel-1;
            document.getElementById("slideCurrent"+b[1]).textContent=currentPanel;
          }
        }		

        }
       }
       
       catch(e){
        console.log('getting error '+e+' in function myjsSlider');
       } 
	}
	
function postActionError(profileChecksum,type)
{
  if(type=="MESSAGES")
    typeDiv="MSG";
  else
    typeDiv="ACCEPTANCE";
  $( "#"+profileChecksum+"_"+type+"_textarea" ).keyup(function() {
    if($( "#"+profileChecksum+"_"+type+"_textarea" ).val().length>0)
    {
      $("#"+profileChecksum+"_"+typeDiv+"_SEND_id").removeClass("opa40");
    }
    else if($( "#"+profileChecksum+"_"+type+"_textarea" ).val().length==0)
    {
      $("#"+profileChecksum+"_"+typeDiv+"_SEND_id").addClass("opa40");
    }
  });
        $("#"+profileChecksum+"_BlankMsg_"+typeDiv).addClass("disp-none");
}	

function postActionMyjs(profileChecksum,URL,div,type,tracking,filtered)
{
  try{
	var data = {};
	var ifid = 1;
	if(tracking)
	{
		var strings = tracking.split("&");
		for(var i =0 ;i<strings.length;i++)
		{
			str = strings[i].split("=");
			data[str[0]] = str[1];
		}
	}
	if(type == "message")
	{
    messageType=div.split('_');
    messageType=messageType[1];
    var Message = $("#"+div+"_textarea").val();
    if(Message=="")
    {
      messageType=div.split('_');
      messageType=messageType[1];
     /* if(messageType=="MESSAGES"){
      $("#"+profileChecksum+"_BlankMsg_MSG").removeClass("disp-none");
      $("#"+profileChecksum+"_BlankMsg_MSG").html("Can't send empty message");
     
    }
      else{
        $("#"+profileChecksum+"_BlankMsg_ACCEPTANCE").removeClass("disp-none");
        $("#"+profileChecksum+"_BlankMsg_ACCEPTANCE").html("Can't send empty message");
      }*/
      return false;
    }
    if(messageType=="MESSAGES")
      $("#"+profileChecksum+"_MSG_SEND_id").addClass("disp-none");
    else
      $("#"+profileChecksum+"_ACCEPTANCE_SEND_id").addClass("disp-none");      
		
		
		data.draft = Message;
	}
	if(type=="interest")
	{
		div_id = div+"_id";
	}
	else
		div_id = div;
	data.profilechecksum = profileChecksum;
  data.myjs = 1;
	$.myObj.ajax({
            type: "POST",
            dataType: "json",
            url: URL,
            beforeSend : function(){
              NProgress.configure({parent: '#'+div_id});
              NProgress.start();
            	$("#"+div).addClass("myjs-blur");
            },
            context:{isEngagementBar:1,divid:div_id},
            data: data,
            success: function(response,data) {
              NProgress.set(1.0);
              try{
            	$("#"+div).removeClass("myjs-blur");
            	if(response.actiondetails && response.actiondetails.errmsglabel)
            	{
					if(type=="interest")
					{
						var str ="";
						var prestr = "";
						if(response.actiondetails.footerbutton)
						{
							if(response.actiondetails.footerbutton.action == "MEMBERSHIP")
							{
								str = "<div class='pt10'><a href='/profile/mem_comparison.php' class='colr5 cursp'>Upgrade Membership</a></div>";
							}
							else
							{
								prestr = "<div class='pt20 colr5 f20 fontlig'>Oops!</div>";
							}
						}
						$("#"+div).find(".bg-white").html("<div class='errorHead txtc'><i class='sprite2 myjs-error'></i>"+prestr+"<div class='pt20 color11 f15 fontlig'>"+response.actiondetails.errmsglabel+str+"</div></div>").height("320px").addClass("pos-rel");
					}
					else
					{	$("#"+div).find(".bg-white").html("<i class='sprite2 myjs-error'></i><div class='pt20 color11 f15 fontlig'>"+response.actiondetails.errmsglabel+"</div>");
						$("#"+div).find(".bg-white").addClass("errorHead");
					}
            		$("#"+div).find("div.sendintr").remove();
            	}
            	else{
	            	if(type=="interest" && !(div.indexOf("matchOfDay") >= 0))
	            	{ 
			//	callAfterContact();
	            		$("#"+div).find("div.sendintr").html("Interest Sent");
	            		$("#"+div).find("div.sendintr").removeClass("myjs-block sendintr").addClass("myjs-block-after");
                  var ind = $("#"+div).attr('id');
                  var nameInitials = ind.split('_');
                  var countToUpdate = (nameInitials[1]+"_resultCount");
                  var out = $("#"+countToUpdate).text();
                  --out;
                  $("#"+countToUpdate).text(out);
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(out,countToUpdate,nameInitials[1]);});     
	        
                }
	            	else if(type=="accept")
	            	{ 
              
                  updateExpiringCount(div);
                  field = div.split('_');
                  comingFrom = field[1];
                  if(comingFrom == 'INTERESTRECEIVED')
                  countLeft = $('#totalInterestReceived').text();
                if(comingFrom == 'FILTEREDINTEREST')
                  countLeft = $('#totalFilteredInterestReceived').text();
                if(comingFrom == 'EXPIRINGINTEREST')
                  countLeft = $('#totalExpiringInterestReceived').text();
                if(comingFrom != 'EXPIRINGINTEREST')
                {
                  --countLeft;
                }
	            		$("#"+div).find("div.intdisp").html("Accepted");
                  $("#"+div).find("div.intdisp").removeClass("myjs-block sendintr").addClass("myjs-block-after lh50");
	            		$("#"+div).find("div.intdisp").removeClass("intdisp");
                  if(comingFrom == 'INTERESTRECEIVED')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalInterestReceived','INTERESTRECEIVED');}); 
                  else if(comingFrom == 'FILTEREDINTEREST')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalFilteredInterestReceived','FILTEREDINTEREST');});
                  else if(comingFrom == 'EXPIRINGINTEREST')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalExpiringInterestReceived','EXPIRINGINTEREST');});

                                
	            	}
	            	else if(type=="decline")
	            	{ 
                  updateExpiringCount(div);
                  field = div.split('_');
                  comingFrom = field[1];
                    if(comingFrom == 'INTERESTRECEIVED')
                  countLeft = $('#totalInterestReceived').text();
                if(comingFrom == 'FILTEREDINTEREST')
                  countLeft = $('#totalFilteredInterestReceived').text();
                if(comingFrom == 'EXPIRINGINTEREST')
                  countLeft = $('#totalExpiringInterestReceived').text();
                if(comingFrom != 'EXPIRINGINTEREST')
                {
                  --countLeft;
                }
	            		$("#"+div).find("div.intdisp").html("Declined");
	            		$("#"+div).find("div.intdisp").removeClass("myjs-block sendintr").addClass("myjs-block-after lh50");
                  $("#"+div).find("div.intdisp").removeClass("intdisp");
                  if(comingFrom == 'INTERESTRECEIVED')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalInterestReceived','INTERESTRECEIVED');}); 
                  else if(comingFrom == 'FILTEREDINTEREST')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalFilteredInterestReceived','FILTEREDINTEREST');});
                  else if(comingFrom == 'EXPIRINGINTEREST')
                  $('#'+div).delay(1500).fadeOut('slow',function(){ $(this).remove();reArrangeDivsAfterDissapear(countLeft,'totalExpiringInterestReceived','EXPIRINGINTEREST');});
	            	}
                else if(type=="message")
                {
                  
                  if(messageType=="MESSAGES")
                  {
                  $( "#MSG_SEND"+tracking).addClass( "disp-none" );
                  $( "#MESSAGE_RESPONSE_"+tracking).removeClass("disp-none");
                  $( "#ACCEPTANCE_RESPONSE_"+tracking).removeClass("txtc");
                  }
                  else
                  {
                    $( "#ACCEPTANCE_SEND"+tracking).addClass( "disp-none" );
                    $( "#ACCEPTANCE_RESPONSE_"+tracking).removeClass("disp-none");
                    $( "#ACCEPTANCE_RESPONSE_"+tracking).addClass("txtc");
                  }
                }
                
                if(type=='decline' || type=='accept')
                    {
                        
                    if(typeof filtered!='undefined' && filtered=='Y'){
                        var filCount=$("#totalFilteredInterestReceived").html();
                        filCount--;
                        $("#totalFilteredInterestReceived").text(filCount);
                        $("#seeAllFilteredCount").text(filCount);
                    }
                    if(typeof filtered!='undefined' && filtered=='N'){
                        var intCount=$("#totalInterestReceived").html();
                        intCount--;
                        $("#totalInterestReceived").text(intCount);
                        $("#seeAllIntCount").text(intCount);
                    }

                            
                    }
	       }

         if(type == "interest" && div.indexOf("matchOfDay") >= 0)
         {
          setStackMOD();
         }
            }
            catch(e){
              console.log('getting error '+e+' in function success of postActionMyjs')
            }
            },
            error: function(result) {
              try{
            	NProgress.set(1.0);
            	$("#"+div).removeClass("myjs-blur");	
            	if(type=="message")
                {
                  messageType=div.split('_');
                  messageType=messageType[1];
                  if(messageType=="MESSAGES")
                  {
                    $("#"+profileChecksum+"_BlankMsg_MSG").removeClass("disp-none");
                    $("#"+profileChecksum+"_BlankMsg_MSG").html("Something went wrong.");
                    $("#"+profileChecksum+"_MSG_SEND_id").html("RESEND"); 
                  }
                  else
                  {
                    $("#"+profileChecksum+"_BlankMsg_ACCEPTANCE").removeClass("disp-none");
                    $("#"+profileChecksum+"_BlankMsg_ACCEPTANCE").html("Something went wrong.");
                    $("#"+profileChecksum+"_ACCEPTANCE_SEND_id").html("RESEND"); 
                  }
                }
            else if(type=="interest")
            {
            	var prestr = "<div class='pt20 colr5 f20 fontlig'>Oops!</div>";
            	$("#"+div).find(".bg-white").html("<div class='errorHead'><i class='sprite2 myjs-error'></i>"+prestr+"<div class='text pt20 fontlig color11'>Something Went Wrong</div><div>").height("320px").addClass("pos-rel txtc");
            	$("#"+div).find("div.sendintr").remove();

            }
            else if(type=="accept" || type == "decline")
	        {
	        	var prestr = "<div class='pt20 colr5 f20'>Oops!</div>";;
	        	$("#"+div).find(".bg-white").html("<i class='sprite2 myjs-error'></i><div class='text pt20 fontlig color11'>"+prestr+"Something Went Wrong</div>").addClass("pos-rel txtc errorHead");;
	            $("#"+div).find("div.intdisp").remove;
	        }
      }
       catch(e){
        console.log('getting error '+e+' in function error of postActionMyjs')
       }
    }
      }); 
  
}
catch(e){}
    }


	function updateExpiringCount(div)
  {
      if(div.indexOf("EXPIRINGINTEREST") >= 0)
      {
        expiringCount = $("#totalExpiringInterestReceived").html();
        expiringCount = parseInt(expiringCount) - 1;
        $("#totalExpiringInterestReceived").html(expiringCount);
        $("#seeAllExpiringCount").html(expiringCount);
        $("#expiringCount").html(expiringCount);
      }
  }

//Completion Bar 
function start1() {


  try{
  if (profileCompletionCount >= limit) {
    clearInterval(t1);
    return;
  }
  profileCompletionCount += 1;
  pc_temp1 = pc_temp1 - 3.6;
  if (profileCompletionCount == 50) {

    clearInterval(t1);
    t2 = setInterval("start2()", 30);
  }


  $("#completePercentId").html(profileCompletionCount + "%");
  $(".pie2").css(cssBrowserAnimProperty, "rotate(" + pc_temp1 + "deg)");
}

catch(e){
  console.log('getting error '+e+' in function start1')
}

}


function start2() {

  try{
  if (profileCompletionCount >= limit) {
    clearInterval(t2);
    return;
  }
  pc_temp2 = pc_temp2 - 3.6;
  profileCompletionCount = profileCompletionCount + 1;
  /*if(count==300){
    count = 0;
    clearInterval(t2);
    t1 = setInterval("start1()",100);
  };*/
  $("#completePercentId").html(profileCompletionCount + "%");
  $(".pie1").css(cssBrowserAnimProperty, "rotate(" + pc_temp2 + "deg)");
  }

catch(e){
  console.log('getting error '+e+' in function start2')

}

}

function profile_completion(lim) {

try{
cssBrowserAnimProperty=CssFix();

  limit = parseInt(lim);

  t1 = setInterval("start1()", 30);

}
catch(e){
  console.log('getting error '+e+' in function profile_completion')

}

}

        function  CssFix()
        {
            // create our test div element
            var div = document.createElement('div');
            // css transition properties
            var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
            // test for each property
            for (var i in props) {
                if (div.style[props[i]] !== undefined) {
                    
                    cssPrefix = props[i].replace('Perspective', '').toLowerCase();
                    animProp = '-' + cssPrefix + '-transform';
                    return animProp;
                }
            }
        }

 function postActionViewContact(checksum,url,count,formtype,div)
 {
      var animate=0;
      $( "#ACCEPTANCE_RESPONSE_"+count).addClass("disp-none");
       if($("#"+formtype).hasClass( "done" ) || animate==1) 
       {
          $("#"+formtype).addClass("disp-none");
          $("#contactDiv"+count).removeClass("disp-none");
          return false;
       } 
  $.ajax(
  {   
       
   // type: "POST",
    //dataType: "json",  
    beforeSend : function(){
              $("#"+div).addClass("myjs-blur");
            },    
            context:{isEngagementBar:1,divid:div},   
    url: url,
    data: "actionName=contactDetails&profilechecksum="+checksum,
                        //timeout: 5000,
                        success: function(response) 
                        {

                          try{
                          var animate=1;
                          $("#"+div).removeClass("myjs-blur");
                          $("#"+formtype).addClass("done");
                          $("#"+formtype).addClass("disp-none");
                          $("#contactDiv"+count).removeClass("disp-none");
                          contactDetails=response.actiondetails;
                          if(!contactDetails.errmsglabel) $("#contactDiv"+count).find('.SMSContactsDiv').removeClass('disp-none').attr('profilechecksum',checksum).bind('click',function(){SMSContactsDivBinding(this);});
                          if(contactDetails.contactdetailmsg==null || contactDetails.contactdetailmsg.indexOf("made contact details visible") > -1)
                          {
							  var timeToCall="";
							  var phoneFlag=0;
                var phoneDivFlag=0;
							  if(contactDetails.contact5!=null)
								timeToCall=" ("+contactDetails.contact5.value+")";
                            if(contactDetails.contact1==null){
                              $("#contact1"+count).html("");
                              $("#contact1"+count).addClass("disp-none");
                            }
                            else{
								$("#contact1"+count).html(contactDetails.contact1.value+timeToCall);
								phoneFlag=1;
							}
                            if(contactDetails.contact2==null){
                              $("#contact2"+count).html("");
                               $("#contact2"+count).addClass("disp-none");
                            }
                            else{
								$("#contact2"+count).html(contactDetails.contact2.value);
								phoneFlag=1;
							}
                            if(contactDetails.contact3==null){
                              $("#contact3"+count).html("");
                               $("#contact3"+count).addClass("disp-none");
                            }
                            else{
								$("#contact3"+count).html(contactDetails.contact3.value);
								phoneFlag=1;
							}
              if(phoneFlag==0)
                phoneDivFlag=1;
                            if(contactDetails.contact4==null)
                              $("#contact3"+count).html("");
                            else{
								$("#email"+count).html("Email id: "+contactDetails.contact4.value);
							}

              if(contactDetails.contact6==null)
                              $("#postedBy"+count).html(contactDetails.errmsglabel);
                            else{
                              postedBy=contactDetails.contact6.value; 
                $("#postedBy"+count).html(contactDetails.contact6.label+" "+postedBy);
                phoneFlag=1;
              }

							if(!phoneFlag)
							{
								$("#phone"+count).remove();
							}
              if(phoneDivFlag==1)
                $("#phone"+count).addClass("disp-none");
                          }
                          else
                          {
                            $("#profileHandled"+count).html(contactDetails.contactdetailmsg+'<div class="brdr-0 bgnone fontrobbold f15 colr5 pt25 cursp"><a class="colr5" href="/profile/mem_comparison.php">UPGRADE MEMBERSHIP</a></div><div style="max-width:290px; height:21px; vertical-align: middle" class="colr5 disp_ib pt10 textTru ">'+(contactDetails.footerbutton.text ? contactDetails.footerbutton.text:'') +'</div>');
                            $( "#phone"+count).addClass("disp-none");
                          }
                        }
                        catch(e){
                          console.log('getting error '+e+' in function success of beforeSend of postActionViewContact')

                        }
                        }
                      })
}

function postActionViewContactClose(checksum,url,count,formtype)
 {
    $("#contactDiv"+count).addClass("disp-none");
    if($( "#ACCEPTANCE_RESPONSE_"+count ).hasClass( "txtc" ))
      $( "#ACCEPTANCE_RESPONSE_"+count ).removeClass("disp-none");
    else
      $("#"+formtype).removeClass("disp-none");
  }

function generateFaceCard(Object)
{
  try{
  var searchId = Object.data.searchid;
	var tracking = "";
		if(Object.data.tracking!==undefined)
			tracking = Object.data.tracking;
		else
    {
      // modify for last search
      var stype = {"DAILYMATCHES":"15","JUSTJOINED":"JJPC","DESIREDPARTNERMATCHES":"DPMP","VERIFIEDMATCHES":"VMPC","LASTSEARCH":"LSPC"}
      // when last search are less than 5
      if(PageSrc == 1)
      {
        stype[Object.name] = "DPMD";
      }
			tracking = "stype="+stype[Object.name];
    }
		var innerHtml="";
		var viewAllInnerHtml="";
		var loopCount=0;

		var totalCount=0,GATrackingFunForSubmit='',GATrackingFunForPhoto='';
		if(Object.name=="DAILYMATCHES"){
      GATrackingFunForSubmit="trackJsEventGA('My JS JSPC','Match Alert Section - Send Interest',loggedInJspcGender,'')";
      GATrackingFunForPhoto="trackJsEventGA('My JS JSPC','Match Alert Section - Tuple',loggedInJspcGender,'')";
    }

    else if(Object.name=="JUSTJOINED"){
      GATrackingFunForSubmit="trackJsEventGA('My JS JSPC','Just Joined Section - Send Interest',loggedInJspcGender,'')";
      GATrackingFunForPhoto="trackJsEventGA('My JS JSPC','Just Joined Section - Tuple',loggedInJspcGender,'')";

    }

    else if(Object.name=="VERIFIEDMATCHES"){
      GATrackingFunForSubmit="trackJsEventGA('My JS JSPC','Matches Verified by Visit Section - Send Interest',loggedInJspcGender,'')";
      GATrackingFunForPhoto="trackJsEventGA('My JS JSPC','Matches Verified by Visit Section - Tuple',loggedInJspcGender,'')";

    }


		else if(Object.name=="DESIREDPARTNERMATCHES" || Object.name=="LASTSEARCH"){
      GATrackingFunForSubmit="trackJsEventGA('My JS JSPC','DPP Matches/Last Search Section - Send Interest',loggedInJspcGender,'')";
      GATrackingFunForPhoto="trackJsEventGA('My JS JSPC','DPP Matches/Last Search Section - Tuple',loggedInJspcGender,'')";
    }

		if(Object.name=="DAILYMATCHES"||Object.name=="JUSTJOINED" || Object.name=="DESIREDPARTNERMATCHES" || Object.name=="VERIFIEDMATCHES" || Object.name=="LASTSEARCH")
			totalCount=Object.data.no_of_results;
    var noOfTuples=Object.data.profiles.length;
    if(totalCount > Object.maxCount){
			loopCount=(Object.maxCount-1) > noOfTuples ? noOfTuples : (Object.maxCount-1) ;
      viewAllInnerHtml=Object.viewAllInnerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[Object.name]);
			}
		else
			loopCount=Object.data.profiles.length;
    
      
		if(loopCount){
      var contactId = profileid+'_'+Object.name;
		    for (i = 0; i < loopCount; i++) {
				innerHtml=innerHtml+Object.innerHtml;
				innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,"/profile/viewprofile.php?profilechecksum="+Object.data.profiles[i]["profilechecksum"]+'&'+tracking+"&total_rec="+totalCount+"&actual_offset="+(i+1)+"&hitFromMyjs="+1+"&listingName="+Object.name.toLowerCase());
				innerHtml=innerHtml.replace(/\{\{PROFILE_FACE_CARD_ID\}\}/g,Object.data.profiles[i]["profilechecksum"]+"_"+Object.name+"_id");
        innerHtml=innerHtml.replace(/\{\{js-AlbumCount\}\}/gi,Object.data.profiles[i]['album_count']);
        innerHtml=innerHtml.replace(/\{\{GA_TRACKING_FOR_PHOTO_VIEW\}\}/,GATrackingFunForPhoto);
        if(Object.data.profiles[i]['album_count']=='0')
        innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,'disp-none'); 
        else 
        innerHtml=innerHtml.replace(/\{\{albumHide\}\}/gi,''); 

        innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+Object.data.profiles[i]["photo"]['url']+"'");
				innerHtml=innerHtml.replace(/\{\{EDUCATION_STR\}\}/g,Object.data.profiles[i]["edu_level_new"]);
				innerHtml=innerHtml.replace(/\{\{ONLINE_STR\}\}/g,Object.data.profiles[i]["userloginstatus"]);
        var age = Object.data.profiles[i]["age"].split(' ');
				innerHtml=innerHtml.replace(/\{\{AGE\}\}/g,age[0]);
				innerHtml=innerHtml.replace(/\{\{list_id\}\}/g,Object.data.profiles[i]["profilechecksum"]+'_'+Object.name);
				innerHtml=innerHtml.replace(/\{\{HEIGHT\}\}/g,$.trim(Object.data.profiles[i]["height"]));
				innerHtml=innerHtml.replace(/\{\{INCOME\}\}/g,Object.data.profiles[i]["income"]);
        innerHtml=innerHtml.replace(/\{\{OCCUPATION\}\}/g,Object.data.profiles[i]["occupation"]);  
				innerHtml=innerHtml.replace(/\{\{LOCATION\}\}/g,Object.data.profiles[i]["location"]);
        var caste = Object.data.profiles[i]["caste"].split(':');
          innerHtml=innerHtml.replace(/\{\{CASTE\}\}/g,caste[caste.length-1]);
        innerHtml=innerHtml.replace(/\{\{RELIGION\}\}/g,Object.data.profiles[i]["religion"]);
				innerHtml=innerHtml.replace(/\{\{MTONGUE\}\}/g,Object.data.profiles[i]["mtongue"]);
				
				//post action handling
				if(Object.name=="DAILYMATCHES")
				{ 
					innerHtml=innerHtml.replace(/\{\{ACTION_1_LABEL\}\}/g,Object.data.profiles[i]["buttonDetailsJSMS"]["buttons"][0]["label"]);
					innerHtml=innerHtml.replace(/\{\{POST_ACTION_1\}\}/g,"postActionMyjs('"+Object.data.profiles[i]["profilechecksum"]+"','"+postActionsUrlArray[Object.data.profiles[i]["buttonDetailsJSMS"]["buttons"][0]["action"]]+"','" +Object.data.profiles[i]["profilechecksum"]+"_"+Object.name+"','interest','"+tracking+"')");
				}
				else
				{
					innerHtml=innerHtml.replace(/\{\{ACTION_1_LABEL\}\}/g,Object.data.profiles[i]["buttonDetails"]["buttons"][0]["label"]);
					innerHtml=innerHtml.replace(/\{\{POST_ACTION_1\}\}/g,"postActionMyjs('"+Object.data.profiles[i]["profilechecksum"]+"','"+postActionsUrlArray[Object.data.profiles[i]["buttonDetails"]["buttons"][0]["action"]]+"','" +Object.data.profiles[i]["profilechecksum"]+"_"+Object.name+"','interest','"+tracking+"');"+GATrackingFunForSubmit);
				}
				
			}
			innerHtml=innerHtml+viewAllInnerHtml;
		
			Object.containerHtml=Object.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
			// check for Last search
      if(Object.name=="DAILYMATCHES" || Object.name=="JUSTJOINED" || Object.name=="VERIFIEDMATCHES" || Object.name=="LASTSEARCH" || Object.name=="DESIREDPARTNERMATCHES")
				Object.containerHtml=Object.containerHtml.replace(/\{\{COUNT\}\}/g,totalCount);
			else
				Object.containerHtml=Object.containerHtml.replace(/\{\{COUNT\}\}/g,"");
			if($("#"+Object.name+"_Container").length == 1){
        if(Object.name == 'LASTSEARCH' && Object.data.no_of_results == 0)
        { 
          $("#LASTSEARCH_Container").remove();
        }
        else{
        $("#"+Object.name+"_Container").html($(Object.containerHtml.trim()).html());

        }
      }
      else 
        $("#"+Object.name).after(Object.containerHtml);
      $("#"+Object.name+"_Container").css('height','');
			$("#"+Object.name).addClass("disp-none");
			
			if(Object.name=="DAILYMATCHES")
			{
				//Daily Matches counts in profile bar			
				$("#dailyMatchesCountTotal").html(totalCount);
				$("#dailyMatchesCountBar").removeClass("disp-none");
				$("#dailyMatchesCountBar > .disp-tbl").addClass("bounceIn animated");
				setBellCountHTML(newEngagementArray);
				bellCountStatus++;
				createTotalBellCounts(newEngagementArray["DAILY_MATCHES_NEW"]);				
			}

			else if(Object.name=="JUSTJOINED")
			{
				//Just joined counts in profile bar			
				$("#justJoinedCountTotal").html(totalCount);
				$("#justJoinedCountBar").removeClass("disp-none");
				$("#justJoinedCountBar > .disp-tbl").addClass("bounceIn animated");
				setBellCountHTML(newEngagementArray);
				bellCountStatus++;
				createTotalBellCounts(newEngagementArray["NEW_MATCHES"]);
			}
		  
      var listName=Object.list;
      $("#prv-"+Object.list).bind(clickEventType,function(){
        myjsSlider("prv-"+listName);
        if(listName == 'DAILYMATCHES_List')
        trackJsEventGA('My JS JSPC', 'Match Alert Section - Left',loggedInJspcGender,'');           
       else if (listName == 'JUSTJOINED_List')
        trackJsEventGA('My JS JSPC', 'Just Joined Section - Left',loggedInJspcGender,'');             
       else if (listName == 'VERIFIEDMATCHES_List')
        trackJsEventGA('My JS JSPC', 'Matches Verified by Visit Section - Left',loggedInJspcGender,'');
        else if (listName == 'DESIREDPARTNERMATCHES_List' || listName == 'LASTSEARCH_List')
        trackJsEventGA('My JS JSPC', 'DPP Matches/Last Search Section - Left',loggedInJspcGender,'');
                   
      });
      $("#nxt-"+Object.list).click(function(){
        myjsSlider("nxt-"+listName);
        if(listName == 'DAILYMATCHES_List')
        trackJsEventGA('My JS JSPC', 'Match Alert Section - Right',loggedInJspcGender,'');           
       else if (listName == 'JUSTJOINED_List')
        trackJsEventGA('My JS JSPC', 'Just Joined Section - Right',loggedInJspcGender,'');             
       else if (listName == 'VERIFIEDMATCHES_List')
        trackJsEventGA('My JS JSPC', 'Matches Verified by Visit Section - Right',loggedInJspcGender,'');
        else if (listName == 'DESIREDPARTNERMATCHES_List' || listName == 'LASTSEARCH_List')
        trackJsEventGA('My JS JSPC', 'DPP Matches/Last Search Section - Right',loggedInJspcGender,''); 
        $("#prv-"+Object.list).show();

      });
      topSliderInt('init');
        if(totalCount > 4)
        {
            $('#nxt-'+Object.list).show();
        }
      $("#"+Object.containerName).removeClass("disp-none");
      if(totalCount <= 4)
    { 
      $("#seeAll"+Object.containerName).hide();
    }

    }
    photo_init();
  }
  catch(e){
  console.log('getting error '+e+' in function generateFaceCard')

  }
}

function generateShortCards(Object)
{
  try{
      var tracking = Object.data.tracking;
      var innerHtml="";
      var count = Object.data.total;
      var remainingCount=0;
      if(count>Object.maxCount)
      {
        remainingCount = count-4;
        count = 4;
      }
      if(count>0)
      {
        for (i = 0; i < count; i++) {
         innerHtml=innerHtml+Object.innerHtml;
         innerHtml=innerHtml.replace(/\{\{PROFILE_SMALL_CARD1_ID\}\}/g,Object.data.profiles[i]["profilechecksum"]+Object.name+"_id");
         innerHtml=innerHtml.replace(/\{\{DETAILED_PROFILE_LINK\}\}/g,"/profile/viewprofile.php?profilechecksum="+Object.data.profiles[i]["profilechecksum"]+"&"+tracking+"&total_rec="+Object.data.total+"&actual_offset="+(i+1)+"&contact_id="+Object.data.contact_id);
         innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+Object.data.profiles[i]["profilepic120url"]+"'");
       }
       if(remainingCount!=0)
       {
         if(remainingCount==1)
         {
          numberInnerHtml=$("#smallCard1").html();
          innerHtml=innerHtml+Object.innerHtml;
          innerHtml=innerHtml.replace(/\{\{PROFILE_SMALL_CARD1_ID\}\}/g,Object.data.profiles[i]["profilechecksum"]+Object.name+"_id");
          innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/g,Object.data.profiles[i]["profilepic120url"]);
        }
        else
        {
          innerHtml=innerHtml+Object.viewAllInnerHtml;
          innerHtml=innerHtml.replace(/\{\{PROFILE_SMALL_CARD2_ID\}\}/g,Object.data.profiles[i]["profilechecksum"]+Object.name+"_id");
          innerHtml=innerHtml.replace(/\{\{PHOTO_URL\}\}/gi,"data-src='"+Object.data.profiles[i]["profilepic120url"]+"'");
          innerHtml=innerHtml.replace(/\{\{COUNT\}\}/g,remainingCount);
          innerHtml=innerHtml.replace(/\{\{LISTING_LINK\}\}/g,listingUrlArray[Object.name]);
        }
      }
		Object.containerHtml=Object.containerHtml.replace(/\{\{INNER_HTML\}\}/g,innerHtml);
		if(Object.name=="PHOTOREQUEST")
		{
			$("#engagementContainer").after(Object.containerHtml);
			 if(profilePic == "N" || profilePic=='')
			{
				$("#upload"+Object.list).remove();
			}
		}
		else
			$("#"+Object.name).after(Object.containerHtml);
		$("#"+Object.name).addClass("disp-none");
		topSliderInt("init");
		$("#"+Object.containerName).removeClass("disp-none");
    }
	photo_init();
	}

  catch (e){
  console.log('getting error '+e+' in function generateShortCards')

  }
}

function noResultFaceCard(Object)
{
  try{ 
	Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+Object.name);
		if(Object.error)
			Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
		else
			Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,noResultMessagesArray[Object.name]);
      Object.containerHtml=Object.containerHtml.replace(/\{\{COUNT\}\}/g,'');
      Object.containerHtml=Object.containerHtml.replace(/\{\{INNER_HTML\}\}/g,Object.emptyInnerHtml);
    
    if($("#"+Object.name+"_Container").length == 1){ 
   //   $("#"+Object.name+"_Container").css('height',$("#"+Object.name+"_Container").height());
     $("#"+Object.name+"_Container").html($(Object.containerHtml.trim()).html());
      }
    else      
      $("#"+Object.name).after(Object.containerHtml);
      $("#"+Object.name).addClass("disp-none");
      $("#disp_"+Object.list).after(Object.emptyInnerHtml);
      $("#js-"+Object.list).remove();
      if(!Object.error)
		$("#Error"+Object.name).remove();
      $("#prv-"+Object.list).remove();
      $("#nxt-"+Object.list).remove();
      $("#seeAll"+Object.containerName).remove();
      $("#"+this.containerName).removeClass("disp-none");
		
	if(Object.name=="DAILYMATCHES")
	{
		if(Object.error)
			$("#dailyMatchesCountTotal").html("--");
		else{
			$("#dailyMatchesCountTotal").html(0);
			$("#Error"+Object.name).remove();
		}
		$("#dailyMatchesNewCircle").addClass("disp-none");
		$("#dailyMatchesCountBar").removeClass("disp-none");
		$("#dailyMatchesCountBar > .disp-tbl").addClass("bounceIn animated");
	}
	if(Object.name=="JUSTJOINED")
	{
		if(Object.error)
				$("#justJoinedCountTotal").html("--");
		else{
			$("#justJoinedCountTotal").html(0);
			$("#Error"+Object.name).remove();
		}
		$("#justJoinedNewCircle").addClass("disp-none");
		$("#justJoinedCountBar").removeClass("disp-none");
		$("#justJoinedCountBar > .disp-tbl").addClass("bounceIn animated");
	}
  if(Object.name=="LASTSEARCH")
  {
    if(Object.error)
        $("#lastSearchCountTotal").html("--");
    else{
      $("#lastSearchCountTotal").html(0);
      $("#Error"+Object.name).remove();
    }
    $("#lastSearchNewCircle").addClass("disp-none");
    $("#lastSearchCountBar").removeClass("disp-none");
    $("#lastSearchCountBar > .disp-tbl").addClass("bounceIn animated");
  }
 

}

catch (e){
  console.log('getting error '+e+' in function noResultFaceCard')

}
}

function noShortCards(Object)
{
  try{
		Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{ID\}\}/g,"Error"+Object.name);
		if(Object.error)
			Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,"Failed to Load");
		else
			Object.emptyInnerHtml=Object.emptyInnerHtml.replace(/\{\{NO_PROFILE_TEXT\}\}/g,noResultMessagesArray[Object.name]);
		Object.containerHtml=Object.containerHtml.replace(/\{\{INNER_HTML\}\}/g,Object.emptyInnerHtml);
		if(Object.name=="PHOTOREQUEST")
		{
			$("#engagementContainer").after(this.containerHtml);
			$("#engagementContainer").addClass("disp-none");
		}
		else		
		{
			$("#"+Object.name).after(Object.containerHtml);
			$("#"+Object.name).addClass("disp-none");
		}
		$("#"+Object.headingId).after(Object.emptyInnerHtml);
		$("#"+Object.list).remove();
		if(!Object.error)
			$("#Error"+Object.name).remove();
		if(Object.name=="PHOTOREQUEST")
		{
			$("#headError"+Object.name).removeClass("myjs-bg3");	
			$("#"+Object.headingId).remove();
			$("#upload"+Object.list).remove();
			$("#"+Object.containerName).removeClass("disp-none").addClass("pt45");
		}
		else
			$("#"+Object.containerName).removeClass("disp-none");
}

catch(e){
  console.log('getting error '+e+' in function noShortCards')
 }

}

function createTotalBellCounts(totalCount)
{
  try {
	if(totalCount!="undefined")
		totalBellCounts=+totalCount + +totalBellCounts;
	if(bellCountStatus==3)
	{
		var data={};
		data["TOTAL_NEW"]=totalBellCounts;
		setBellCountHTML(data);
	}
}


catch (e){
  console.log('getting error '+e+' in function createTotalBellCounts')

}
}

function reArrangeDivsAfterDissapear(value,position,id)
{ 
  if(value <= 4)
  { 
    if(id == 'INTERESTRECEIVED')
    { 
      $('#seeAllId_'+id).hide();
    }
    else if (id == 'FILTEREDINTEREST' || id == 'EXPIRINGINTEREST')
    {
     $('#seeAll_'+id+'_List').hide(); 
    }
    else
    {
    $('#seeAll'+id+'_Container').hide();
    }
  }

  if(value == 0 && id == 'EXPIRINGINTEREST')
  {
    $('#ExpiringAction').hide();
  }
  
  var currentBox = getCurrentBox(id);
  topSliderInt("init");
  var totalBoxes = getTotalBoxes(id);
  var numberOfProfiles = getNumberOfProfiles(id);

    if(currentBox <= totalBoxes && numberOfProfiles%4 == 0 && value < 20 && id == 'INTERESTRECEIVED')
    {
      shortBigCard(id);
    }

  if(id == 'FILTEREDINTEREST' || id == 'INTERESTRECEIVED' || id == 'EXPIRINGINTEREST')
  { 
    $('#slideTotal'+id+'_List').text(totalBoxes);
    $('#slideCurrent'+id+'_List').text(currentBox);
  }
  
  if(value == 0 && id == 'INTERESTRECEIVED')
  {
       var IntRecSec = new interestReceived();
          $("#"+id+"_Container").html('');
          IntRecSec.pre();
          IntRecSec.request();
          return;    
  }
  
  var noCardPresentState = noCardPresent(currentBox,totalBoxes);

  if(onlyViewAllCardPresent(currentBox,totalBoxes,id,numberOfProfiles) || noCardPresentState)
  {
    if(!isFirstBox(currentBox)){
          $("#prv-"+id+"_List").click();
          if(noCardPresentState && id != 'INTERESTRECEIVED' && id != 'FILTEREDINTEREST' && id != 'EXPIRINGINTEREST')
          $("#nxt-"+id+"_List").hide();  
        }
    else
      {
      $("#"+id+"_Container").css('height',$("#"+id+"_Container").height());
      if(value ==0 && id != 'INTERESTRECEIVED' && id != 'FILTEREDINTEREST' && id != 'EXPIRINGINTEREST')
      $("#"+id+"_Container").css('height','');

        if(id == 'DAILYMATCHES')
        {
          //$("#DAILYMATCHES_Container").html('')
          var dailyMatchObj =new dailyMatches();
          dailyMatchObj.pre();
          dailyMatchObj.request();
        }
        if(id == 'JUSTJOINED')
        {
          //$("#JUSTJOINED_Container").remove()
          var justJoinedMatchObj =new justJoinedMatches();
          justJoinedMatchObj.pre();
          justJoinedMatchObj.request();
        }
        if(id == 'LASTSEARCH')
        {  
          if(value == 0)
          {
          $("#LASTSEARCH_Container").remove();
          }
          else
          {
          var lastSearch =new lastSearchMatches();
          lastSearch.pre();
          lastSearch.request();
          }
        }
        if(id == 'VERIFIEDMATCHES')
        {
          //$("#VERIFIEDMATCHES_Container").remove()
          var verifiedMatchObj =new verifiedMatches();
          verifiedMatchObj.pre();
          verifiedMatchObj.request();
        }
        if(id == 'DESIREDPARTNERMATCHES')
       {  
         // $("#DESIREDPARTNERMATCHES_Container").remove()
          var desiredMatchObj =new desiredPartnerMatches();
          desiredMatchObj.pre();
          desiredMatchObj.request();
        }
         if(id == 'FILTEREDINTEREST')
       { 
        var filterSec = new filteredInterest();
        $("#"+id+"_Container").html('');
          filterSec.pre();
          filterSec.request();
        }
        if(id == 'EXPIRINGINTEREST')
       { 
        var expSec = new expiringInterest();
        $("#"+id+"_Container").html('');
          expSec.pre();
          expSec.request();
        }
         if(id == 'INTERESTRECEIVED')
     {
       var IntRecSec = new interestReceived();
          $("#"+id+"_Container").html('');
          IntRecSec.pre();
          IntRecSec.request(); 
     }
       
      }        
  }
  if(viewCardInList(currentBox,totalBoxes,id,numberOfProfiles))
  {
     $('#nxt-'+id+'_List').hide();
  }

  

}

    function getCurrentBox(id)
    {   
              return sliderNav[id+'_List'];
    }


    function getTotalBoxes(id)
    {       
            if(id == "MESSAGES" || id =="ACCEPTANCE")
            return Math.ceil(getNumberOfProfiles(id)/2);
            return Math.ceil(getNumberOfProfiles(id)/4);

    }

    function isFirstBox(boxNumber)
    {
      if(boxNumber == 1)
        return 1;
      else
        return 0;
    }

    function noCardPresent(currentBox,totalBoxes)
    {
        if(currentBox > totalBoxes)
        {
          
            return 1;
        }

        return 0;

    }

    function getNumberOfProfiles(id)
    {
      var count = 0;
    $("#js-"+id+"_List > li").each(function( index ) {
  count++;
      });
    return count;
    }

    function onlyViewAllCardPresent(currentBox ,totalBoxes,id,numberOfProfiles)
    { 
      if(currentBox == totalBoxes && numberOfProfiles%4 == 1)
      { 
        if($('ul#js-'+id+'_List li:nth-last-child(1)').find('#idForViewAllCard').text() == "View All")
        { 
          return 1;
        }
      }
      return 0;
    }


  function viewCardInList(currentBox ,totalBoxes,id,numberOfProfiles)
    {

      if(currentBox == totalBoxes && numberOfProfiles % 4 == 0)
      {
          return 1;
      }
      return 0;
    }

    function shortBigCard(id)
    { 
      var toBeReplaced = $('ul#js-'+id+'_List li:nth-last-child(1)').find('#infoCardDouble');
       
        if(toBeReplaced.length == 1)
        { 
          var toBeReplacedWith = $('#infoCardSingle').clone().css('display','block');
          toBeReplaced.parent().css('width','');
          toBeReplaced.replaceWith(toBeReplacedWith); 
        }

    }

    function lastCardIsShortedOne(id)
    { 
      var lastCard = $('ul#js-'+id+'_List li:nth-last-child(1)').find('#infoCardSingle');
       
        if(lastCard.length == 1)
        { 
         return 1;
        }
        return 0;
    }

    function lastCardIsDoubleOne(id)
    { 
      var lastCard = $('ul#js-'+id+'_List li:nth-last-child(1)').find('#infoCardDouble');
       
        if(lastCard.length == 1)
        { 
         return 1;
        }
        return 0;
    }


