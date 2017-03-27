
		function mouseOver(element){
			document.getElementById(element).style.display='inline';
		}
		function mouseOut(element){
			document.getElementById(element).style.display='none';
		}
		var commonData={};

		$('.checkBoxClicked').bind("click",function(){

		        if(this.checked){
				appendSendingData(this.name,"Y",1);
				PostRequest("Normal");
				
				
			}
			else
			{
				appendSendingData(this.name,"N",1);
				appendSendingData("NOT_UPDATE_HARDSOFT","1",1);
				PostRequest("Normal");
			}
		});

		
		function appendSendingData(name,val,isfilter){
				
				commonData[name]=val;
		}
			
					
function PostRequest(action)
{	
	appendSendingData("selectId","1",0);
	appendSendingData("Submit","1",0);
	if(action=="redirect_uncheck")
	appendSendingData("noFilter","1",0);
       var ce_url=SITE_URL+"/profile/revamp_filter.php";
	
        $.ajax({
					url: ce_url,
					type: "POST",
					data: commonData,
					success: function(data){
					if(data=='You have logged out or Your Session has expired')
					{
							window.location.reload();
					}
					if(action=="redirect_save" ||action=="redirect_uncheck")
					{
					 window.location=SITE_URL+"/profile/mainmenu.php";	
						
					}
					else
                    {
						if(data.charAt(data.length-1)=="_")
						{
							filter_id=data.toLowerCase()+"filter";
							if($("#"+filter_id).is(':checked'))
							 {
								$("#"+data+"text").text('Filter has been set');
								 $("#"+data+"text").addClass("filter-set");
							 }
							else
							{
								$("#"+data+"text").text('Set this as a Filter');
                                                                 $("#"+data+"text").removeClass("filter-set");						
							}
							 
						
							$("#SHOW_LOADER").hide();
							
							$("#confirm").show();
							$("#PRI_SET").show();
							updateCheckboxes();        
							commonData={};
							document.getElementById("Filterid").value=data;
						}
                        else
                        {
							$("#SHOW_LOADER").hide();
							
							$("#confirm").show();
							$("#PRI_SET").show();
							updateCheckboxes();        
							commonData={};
							document.getElementById("Filterid").value=data;
							if(document.getElementById("from_reg").value==1){
								if(document.getElementById("isMobile").value!=1){
									if(action=="skip_to_fto")
										window.location=SITE_URL+"/fto/offer?fromReferer=0&profilechecksum="+document.myform.profilechecksum.value;
										else
								window.location=SITE_URL+"/fto/offer?fromReferer=0&profilechecksum="+document.myform.profilechecksum.value;

								}
								else
									window.location=SITE_URL+"/P/mainmenu.php";
							}
						}
					}

                                        //dID("IndividualProfile").style.zIndex="10";
				}
				});
}





		var page='';
		function updateCheckboxes()
		{
			var checkboxes=$('input[id$="_filter"]');
			for(i=0;i<checkboxes.length;i++)
			{
				if(!checkboxes[i].checked)
				{
				
					var id=(checkboxes[i].id).toUpperCase();
					id=id.replace("FILTER","text");
					$("#"+id).html("Set this as a Filter");
					$("#"+id).removeClass("filter-set");
				}
				
			}
		}
		function obtainvalue(val)
		{
		        var vak;
			var value;
	        	var check = document.getElementsByName(val);
		        for(var i=0;i<check.length;i++)
			{
			   vak = check[i].checked;
			   break;
			}
			if(vak==true){
			   appendSendingData(val,"Y",1);
				return 'N';
			}
			else if(vak==false){
			  appendSendingData(val,"N",1);
				return 'Y';
			}
		}
		function get(action) 
		{
			var checkboxes=$('input[id$="_filter"]');
			for(i=0;i<checkboxes.length;i++)
				appendSendingData(checkboxes[i].name,(checkboxes[i].checked)?"Y":"N",1);
			PostRequest(action);
		}
		function hide_confirmation()
		{
			document.getElementById('confirm').style.display = 'none';
		}
		function validate7(action)
		{		
			var loc_str=document.location.href;
			var regExpr=/#[a-z\_A-Z]*/;
			loc_str=loc_str.replace(regExpr,"");	
			document.location=loc_str+"#top_revamp";
			$("#PRI_SET").hide();
			$("#confirm").hide();
			$("#SHOW_LOADER").show();
			get(action);
			
		}
		function redirect_filter(action)
		{
			PostRequest(action);
		}
	
