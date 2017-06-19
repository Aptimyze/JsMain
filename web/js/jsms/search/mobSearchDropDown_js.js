(function() {
	var DropDown=(function(){
		function DropDown(element,attr){
			
			this.ulOption="#DD_OPTION";
			this.ulOption_second="#DD_OPTION_2";
			this.output=new Array;
			var ele=this;
			ele.output=[];
			this.dropdownHtml = this.UpdateHtml($("#dropdown").html());
			this.calledElement=element;
			this.isDependant = false;
			this.selectedValue = $(element).find('div[data]').attr("data");
			this.dependantValue = $(element).find('span[data]');
			this.formation=$(element).attr("dmove")=="right"?"r":"l";
			this.whenHide=$(element).attr("dhide");
			this.inputtype=$(element).attr("dselect");
			this.callBack=eval($(element).attr("dcallback"));
			this.dependant=$(element).attr("dependant");
			this.originalHtml=$("#DD_OPTION").html();
			this.ddType = "searchform";
			this.haveSearch = $(element).attr("haveSearch");
			this.saveButton="#searchform_submit";
			this.clearButton="#searchform_clear";
			this.ddid="#dropdown";
			this.ddoverid="#dropdownoverlay";
			this.persid="#perspective";
			this.pcontid="#pcontainer";
			this.myOptionData="";
                        if($(element).attr("id") == "search_LOCATION_CITIES"){
                                updateLocationCities("location");
                        }
			$(element).bind("click",function(){	
				ele.GetStaticData();
				ele.type=$(element).attr('dshow').toLowerCase();
				ele.ddHeading = getHeading(ele.type,'');
				var subType = ele.type.substring(1);
				var typeArray = ["height","age","income"];
				if($.inArray(subType,typeArray)!= -1)
					ele.type=subType;
				ele.realType = $(element).attr('dshow').toLowerCase();
				ele.fillData=ele.staticData[ele.type];
				stopTouchEvents(1);
				
				ele.ShowDropDown();
						
				});
		};
		
		DropDown.prototype.UpdateHtml=function(html){
			html=html.replace(/DD_OPTION/g,"DD_OPTION_2");
			html=html.replace(/DD_FORM/g,"DD_FORM_2");
			html=html.replace(/headDiv/g,"headDiv_2");
			html=html.replace(/searchform_ddlabel/g,"searchform_ddlabel_2");
			html=html.replace(/SearchDiv/g,"SearchDiv_2");
			html=html.replace(/searchform_submit/g,"searchform_submit_2");
			html=html.replace(/searchform_clear/g,"searchform_clear_2");
			html=html.replace(/search_ham/g,"search_ham_2");
			html=html.replace(/searchform_dependant_back/g,"searchform_dependant_back_2")
			return html;
		};
		
		DropDown.prototype.ShowDropDown=function(){
			
			stopTouchEvents();
			var ele=this;
			
			ele.PreUpdateDropDownHTML();
			ele.ShowDD(ele.formation);
			var timer=getTimer(ele.type,"");
			$('#searchform_ddlabel').html(ele.ddHeading);
			if(ele.inputtype=="radio")
				$(ele.clearButton).addClass("dn");
			else
				$(ele.clearButton).removeClass("dn");
			$("#searchform_dependant_back").addClass("dn");	
			setTimeout(function(){
				ele.UpdateDropDownHTML();
				ele.ScrollSelected();
				startTouchEvents(DropDown);
			},timer);
			historyStoreObj.push(function(){
				if($("#dropdown").hasClass("rham"))
				{
					$("#dropdown").trigger("click");
					return true;	
				}
				return false;
			},"#searchHam");
			
		};
		DropDown.prototype.ScrollSelected=function()
		{
			var selectedTab=$(this.ulOption).children("li.selected").first();
			if($(selectedTab).length)
			{
				$(this.ulOption).scrollTo($(selectedTab));
			}
		}
		DropDown.prototype.ShowDD=function(type)
		{
				var ele=this;
				this.DDClass=type;
				
				if(!Modernizr.csstransforms3d || ISBrowser("UC") || ISBrowser("AndroidNative"))
	                        {
	                           $(this.pcontid).addClass("twodview"+this.formation);
	                           setTimeout(function(){
					   $("#2dView").removeClass("dn");
	                           
	                           },50);
	                        }   
				$("#pcontainer").prepend("<div class='wrapper' id='wrapper'></div>");
				setTimeout(function(){
					$('#dropdown').removeClass("dn");
					$("#perspective").addClass("showpers");
					$("#pcontainer").addClass("hamb").addClass("ham"+ele.DDClass);
					$("#dropdown").addClass(ele.DDClass+"ham");
				})
				$("#dropdown").addClass("h_animation");
				$("#wrapper,#dropdown,#dropdownoverlay,#2dView").unbind("click");
				$("#wrapper,#dropdown,#dropdownoverlay,#2dView").bind("click",function(ev){
					ele.StopDD();
				});
				$("#headDiv,#headDiv_2").unbind("click");
				$("#headDiv,#headDiv_2").bind("click",function(ev){
					stopPropagation(ev);
				});
				
				
		};	
		
		DropDown.prototype.StopDD=function()
		{
			var ele = this;
			stopTouchEvents();
			$(this.DD_OPTION).unbind(clickEventType);
			ele.hideDropDown();
		};
		DropDown.prototype.GetStaticData=function()
		{
			if(isStorageExist())
				this.staticData = JSON.parse(localStorage.getItem("searchFormStaticData"));
			else
				this.staticData = staticData;
			if(!this.staticData || this.staticData=="")
			{
				setStaticData();
				if(isStorageExist())
					this.staticData = JSON.parse(localStorage.getItem("searchFormStaticData"));
				else
					this.staticData = staticData;
			}
			
				
		};
		
		DropDown.prototype.UpdateDropDownHTML=function()	
		{
			
			var dType= this.ddType;
			
			$('#dType_ddlabel').html(this.type);
			var key = this.type;
			$('.dType').each(function(){
				  if(this.id){
				    this.id = this.id+key;
				  }
				});
			
			var ele = this;
			
					
			$(ele.ulOption).html(ele.myOptionData);
			
			$(ele.ulOption).children(".isGroup.selected").each(function(i, obj) {
				var myGroup = $(this).attr("group");
				$(ele.ulOption).children('li.inGroup[group="'+myGroup+'"]').each(function(i, obj) {
					$(this).addClass('selected');
				
				});
				
			});
			if(key=="mtongue")
			{
				var gHElement = $(ele.ulOption).children('li.isGroupheading');
				if($(gHElement).find("i").hasClass("srfrm_arwdwn"))
					ele.HandleGroupHeading(gHElement);
				
			}
			if(key=="age")
			{

				$(ele.ulOption).children('li.selected').children("div").first().append(" Years");
				
			}
			$(ele.ulOption).children(".selected").each(function(i, obj) {
				if($(this).find("i.srfrm_circle"))
				{
					$(this).find("i.srfrm_circle").addClass("srfrm_checked").removeClass("srfrm_circle");
				}
				var groupHeading = $(this).attr("group");
				var gHElement = $(ele.ulOption).children('li.isGroupheading[group="'+groupHeading+'"]');
				if($(gHElement).find("i").hasClass("srfrm_arwdwn"))
					ele.HandleGroupHeading(gHElement);
			});
				
			var typeArray = ["height","age","income"];
			if(this.realType=="lincome")
				$(this.ulOption).children('li[value="19"]').addClass("dn");
			
				
			
			$("#searchform_dependant_back_2").bind("click",function(ev){
				stopPropagation(ev);
				ele.RemoveDependant();
				$(ele.ddid).removeClass("dn");
				$(ele.ulOption).children(".tapped").removeClass("tapped");
				$(ele).unbind();
						
			});
			
			$(this.saveButton).bind("click",function(ev){
				stopPropagation(ev);
				$(ele).unbind();
				ele.SendCloseDD();
				
			});
			
			$(this.clearButton).bind("click",function(ev){
				stopPropagation(ev);
				ele.ClearDD();
				$(ele).unbind();
			});
			$(this.ulOption).bind("click",function(ev){
				stopPropagation(ev);
				var target=ev.target;
				ele.DDElementClick(target);
			});
			
			searchHamburger(this.ulOption,key);
		};
		DropDown.prototype.PreUpdateDropDownHTML=function()
		{
			var ele = this;
			
			$(this.ulOption).html("<div id='loaderDiv' class='txtc'><img src='IMG_URL/images/jsms/commonImg/loader.gif'/></div>");
			$(this.ulOption).addClass("animateText");
			for(var i=2;i<100;i++)
			{
				setTimeout(function(){
					$(ele.ulOption).find("div#loaderDiv").html("<img src='IMG_URL/images/jsms/commonImg/loader.gif?"+i+"'/>");
					
				},1*i);
			}	
			
			var preValue="";
			if(this.isDependant==true)
				preValue = this.selectedValue;
			else 
				preValue = $(this.calledElement).find('div[data]').attr("data");
			var gender = $("#search_gender").attr("data");
			
			if(gender=="M" && this.realType=="lage")
			{
				var minValue = 21; // min age for male is 21
			}
			
			var typeArray = ["hheight","hage","hincome"];
			
			
			if($.inArray(this.realType,typeArray)!= -1)
			{
				var minType = "#search_L"+this.type.toUpperCase();
				var minValue = $(minType).find('div[data]').attr("data");
				if(gender=="M" && this.type=="age" && minValue<21)
					minValue=21;
			}
			var html=this.originalHtml;
			var data=this.fillData;
			var isarr="";
			var dType= this.ddType;
			
			
			$('#dType_ddlabel').html(this.type);
			var key = this.type;
			$('.dType').each(function(){
				  if(this.id){
				    this.id = this.id+key;
				  }
				});
			var finalarr=new Array();
			if(!(this.inputtype=="radio"))
					isarr="[]";
			
			for(var i=0;i<data.length;i++)
			{
				if(typeof(minValue)=="undefined" || ((ele.realType!="hincome" && data[i]["VALUE"]>=minValue) || (ele.realType=="hincome" && (data[i]["VALUE"]==19 || data[i]["VALUE"]>minValue))))
				{
				
					var setDependant = false;
					var temp=html;
					var liClass ="";
					var divClass="";
					var iClass="";
					if(data[i]["IS_GROUP_HEADING"]=="Y")
					{
						divClass=divClass+"color17 fontlig ";
						
						if(data[i]["ISGROUP"]=="")
						{
							liClass = liClass + " isGroupheading";
							iClass= iClass+" srfrm_arwdwn";
							temp=temp.replace(/DD_VALUE/g,"groupdata"+data[i]["VALUE"]);
						}
						else if(data[i]["HAS_DEPENDENT"]=="Y")
						{
							liClass = liClass + " hasDependent";
							iClass=iClass+" srfrm_arwright";
							
							if(checkIfExists(data[i]["VALUE"],preValue))
								liClass = liClass + " selected";
							
							temp=temp.replace(/DD_VALUE/g,data[i]["VALUE"]);
							temp = temp.replace(/DEPENDANT_CLASS/g,"color1 fontlig f14 padr35");
							if(ele.dependantValue && $(ele.dependantValue).attr("data")!="")
							{
								var depValue = $.parseJSON($(ele.dependantValue).attr("data"))[data[i]["VALUE"]];
								if(depValue)
								{
									setDependant = true;
									temp = temp.replace(/DD_DEP_VALUE/g,depValue);
									temp = temp.replace(/VALUE_DEP/g,depValue.split(",").length);
								}
								
							}
						}
						else
						{
							iClass=iClass+" srfrm_circle";
							temp=temp.replace(/DD_VALUE/g,data[i]["VALUE"]);
						}
					}
					else
					{
						iClass=iClass+" srfrm_circle";
						temp=temp.replace(/DD_VALUE/g,data[i]["VALUE"]);
					}
					temp=temp.replace(/DD_LABEL/g,data[i]["LABEL"]);
					if(data[i]["IN_GROUP"]=="Y" || (data[i]["ISGROUP"]=="Y" && data[i]["IS_GROUP_HEADING"]==""))
					{
						divClass=divClass+" white fontthin padl20";
						if(key!="caste")
							liClass = liClass + " hide";
					}
					else
						divClass=divClass+" color17 fontlig";
					if(data[i]["ISGROUP"]=="Y")
					{
							liClass = liClass +" isGroup ";
							if(checkIfExists(data[i]["VALUE"],preValue))
								liClass = liClass + " selected";
							
					}
					else if(data[i]["IS_GROUP_HEADING"]!="Y")
					{
						
						if(data[i]["IN_GROUP"]=="Y")
							liClass = liClass + " inGroup";
						else
							liClass = liClass + " noGroup";
							
						if(checkIfExists(data[i]["VALUE"],preValue))
						{
							liClass = liClass + " selected";
							if(ele.inputtype=="radio")
							{
								liClass = liClass + " bg7";
							}
						}
						
						
					}
                                        
                                        if(data[i]["IS_LIST_HEADING"]=="Y")
					{
						liClass = "noselect hpad5";
                                                temp = temp.replace(/mrr10/g,'mrr10 dn');
                                                divClass = divClass.replace(/color17/g,'color14');
					}
					if(!setDependant)
					{
						temp = temp.replace(/DD_VALUE_DEP/g,data[i]["VALUE"]);
						temp = temp.replace(/DEPENDANT_CLASS/g,"");
						temp = temp.replace(/VALUE_DEP/g,"");
					}
					var typeArray = ["height","age","income"];
			
					if($.inArray(key,typeArray)== -1)
						iClass = "icons1 "+ iClass;
					temp=temp.replace(/DD_LI_CLASS/g,liClass);
					temp=temp.replace(/DD_DIV_CLASS/g,divClass);
					temp=temp.replace(/DD_I_CLASS/g,iClass);
					temp=temp.replace(/DD_NAME/g,ele.type+isarr);
					temp=temp.replace(/DD_TYPE/g,ele.inputtype);
					temp=temp.replace(/DD_GROUP/g,data[i]["GROUP"]);
					
					finalarr[i]=temp;
				}
				
			}
						
			this.myOptionData = finalarr.join("");
			
			var subHeight=10;
			if(this.isDependant==true)
				 subHeight = subHeight + $('#headDiv_2').outerHeight()+10;
			else
				subHeight = subHeight + $('#headDiv').outerHeight() +50;
							
			if(this.isDependant)
			{
				if(data.length <20)
					$('#SearchDiv_2').addClass("dn");
				else
					subHeight = subHeight + $('#SearchDiv_2').outerHeight();
			
			}
			else if(this.haveSearch == "0") 
				$('#SearchDiv').addClass("dn");
			else
			{
				subHeight = subHeight + $('#SearchDiv').outerHeight() +43;
				$('#SearchDiv').removeClass("dn");
			}	
			var typeArray = ["height","age","income"];
			
				
			if($.inArray(key,typeArray)!= -1)
			{
				$("#dropdown").find(".searchsubmit").addClass("ltransform");
				
			}
			else
			{
				subHeight = subHeight + 55;
				$("#dropdown").find(".searchsubmit").removeClass("ltransform");
			}
			
			height = $(window).height() - subHeight;
			
			var loaderHeight = height/3;
			$(this.ulOption).css({"height":height,"overflow":"auto"});
			$("#loaderDiv").css({"margin-top": loaderHeight});
						
		};
		
		DropDown.prototype.DDElementClick=function(ele)
		{
						
			var myLi = $(ele).closest('li');
			var inputv=$(myLi).attr("value");
			if(this.inputtype =="radio")
			{
				$("li.selected").removeClass("selected").removeClass("bg7");
				$(myLi).addClass("selected").addClass("bg7");
				this.SendCloseDD();
				$(ele).unbind();
			}
			else
			{
				if($(myLi).hasClass("hasDependent"))
				{
					this.SetDependant(myLi,inputv);
					
				}
				else if($(myLi).hasClass("isGroupheading"))
				{
					this.HandleGroupHeading(myLi);
					
				}
				else
				{
                                        var unselect = 0;
					if($(myLi).hasClass('selected')){
						$(myLi).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
                                                unselect = 1;
                                        }else{
						$(myLi).find("i.srfrm_circle").addClass("srfrm_checked").removeClass("srfrm_circle");
                                        }
					$(myLi).toggleClass("selected");
					if(this.type=="caste")
					{
						if((inputv =="14" || inputv=="DONT_MATTER")) // Caste all handling
						{
							if($(myLi).hasClass('selected'))
							{
								$(this.ulOption).children('li.selected').each(function(i, obj) {
									if($(this).attr("value")!=inputv)
									{
										$(this).removeClass("selected");
										$(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
									}
								});
							}
						}
						else if($(this.ulOption).children('li').first().hasClass("selected"))
						{
							$(this.ulOption).children('li').first().removeClass("selected");
							$(this.ulOption).children('li').first().find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
						}
					}
					
					if(this.type=="location")
					{
						if((inputv =="51")) // India any city handling
						{
							if($(myLi).hasClass('selected'))
							{
								$(this.ulOption).children('li.selected').not(".noGroup").each(function(i, obj) {
									$(this).removeClass("selected");
									$(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
									
								});
							}
						}
						else if($(myLi).hasClass('selected') && !($(myLi).hasClass("noGroup")))
						{
							$('li#searchform_51').removeClass("selected");
							$('li#searchform_51').find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
						}
						
					} 
					if(this.type=="location_cities" || this.type=="location")
					{
                                             $(this.ulOption).children('li').each(function(i, obj) {
							if($(this).attr("value") == inputv)
							{		
                                                                if($(this).hasClass("selected") && unselect == 1)
								{
                                                                        $(this).removeClass('selected');
                                                                        $(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
                                                                }
							}
						});   
                                        }
					if($(myLi).hasClass("isGroup"))
					{
						var myGroup = $(myLi).attr("group");
						var allLiIds="";
						$(this.ulOption).children('li[group="'+myGroup+'"]').each(function(i, obj) {
							if(!$(this).hasClass("isGroupheading"))
							{
								allLiIds=allLiIds + ","+$(this).attr("value");
							}
						});
						
						$(this.ulOption).children('li').each(function(i, obj) {
							if(checkIfExists($(this).attr("value"),allLiIds))
							{
								if($(myLi).hasClass("selected"))
								{
									if(!$(this).hasClass("selected"))
									{
										$(this).addClass('selected');
										$(this).find("i.srfrm_circle").addClass("srfrm_checked").removeClass("srfrm_circle");
									}
								}
								else
								{
									$(this).removeClass('selected');
									$(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
								}
							}
							
						});
						
						
						
					}
					else if($(myLi).hasClass("inGroup"))
					{
						
						var myId = $(myLi).attr("value");
						var ele = this;
						$(this.ulOption).children('li.inGroup').each(function(i, obj) 
						{
							if(checkIfExists(myId,$(this).attr("value"),"Y") || checkIfExists($(this).attr("value"),myId))
							{
								var myGroup = $(this).attr("group");
								if(!$(myLi).hasClass("selected"))
								{
									if(myLi!= this)
									{
										$(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
										$(this).removeClass("selected");
									}
									$(ele.ulOption).children('li.isGroup[group="'+myGroup+'"]').removeClass("selected");
									$(ele.ulOption).children('li.isGroup[group="'+myGroup+'"]').find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
								}
								else
								{
									if(myLi != this)
									{
										$(this).find("i.srfrm_circle").addClass("srfrm_checked").removeClass("srfrm_circle");
										$(this).addClass("selected");
									}
									var allGroup= true;
									$(ele.ulOption).children('li.inGroup[group="'+myGroup+'"]').each(function(i, obj) {
										if(!$(this).hasClass('selected'))
											allGroup = false;
										
									});
									if(allGroup && myId!="DE00,UP25,HA03,HA02,UP12,UP47,UP48")
									{
										$(ele.ulOption).children('li.isGroup[group="'+myGroup+'"]').addClass("selected");
										$(ele.ulOption).children('li.isGroup[group="'+myGroup+'"]').find("i.srfrm_circle").addClass("srfrm_checked").removeClass("srfrm_circle");
									}
								}
							}
						});
					}
				}
				
			}
			
			
		};
		
		DropDown.prototype.HandleGroupHeading = function(myLi)
		{
			$(myLi).find('i').toggleClass('srfrm_arwdwn');
			$(myLi).find('i').toggleClass('srfrm_arwup');
			var myGroup = $(myLi).attr("group");
			$(this.ulOption).children('li[group="'+myGroup+'"]').each(function(i, obj) {
				if(!$(this).hasClass("isGroupheading"))
				{
					if($(myLi).find('i').hasClass("srfrm_arwdwn"))
						$(this).addClass('hide');
					else
						$(this).removeClass('hide');
				}
			});
		}
		
		DropDown.prototype.SetDependant = function(myLi,inputv)
		{
			stopTouchEvents(1);
			$(myLi).addClass("tapped");
			this.type=this.dependant;
			$(this.ddoverid).html(this.dropdownHtml);
			this.ulOption=this.ulOption_second;
			this.isDependant = true;
			this.fillData=this.staticData[this.type][inputv];
			this.saveButton="#searchform_submit_2";
			this.clearButton="#searchform_clear_2";
			this.selectedValue = $(myLi).find('i').attr("value");
			this.callBack=eval("UpdateDependantSection");
			this.ddHeading = getHeading(this.type,inputv);
			this.PreUpdateDropDownHTML();
			$("#headDiv_2").unbind("click");
			$("#headDiv_2").bind("click",function(ev){
				stopPropagation(ev);
			});
			
			$('#searchform_ddlabel_2').html(this.ddHeading);
			$("#dropdownoverlay").addClass("rham").addClass("show");
			$("#searchform_dependant_back_2").removeClass("dn");
			$("#dropdown").removeClass('h_animation').addClass("dn");
			var ele= this;
			var timer = getTimer(this.type,inputv);
			setTimeout(function(){
				ele.UpdateDropDownHTML();
				ele.ScrollSelected();
				startTouchEvents(1000);
			},timer);
			historyStoreObj.push(function(){
				if($("#dropdownoverlay").hasClass("show"))
				{
					$("#searchform_dependant_back_2").trigger("click");
					return true;	
				}
				else
					return false;
			},"#searchHam");
		}
		
		DropDown.prototype.RemoveDependant=function(){
			this.Dependant = false;
			var ele=this.calledElement;
			this.type=$(ele).attr('dshow').toLowerCase();
			this.ulOption="#DD_OPTION";
			this.selectedValue = $(ele).find('div[data]').attr("data");
			$(this.ddoverid).removeClass("rham");
			$(this.ddid).removeClass("dn").addClass("h_animation");
			this.isDependant = false;
			this.fillData=this.staticData[this.type];
			this.saveButton="#searchform_submit";
			this.clearButton="#searchform_clear";
			this.callBack=eval($(ele).attr("dcallback"));
			searchHamburger(this.ulOption);
			$(this.ddoverid).removeClass("show");
			$(this.ddoverid).html("");
			$("#search_ham_2").val("");
			
			
		};
		DropDown.prototype.SendCloseDD=function(){
			this.GetOutput();
			var callBackFn = this.callBack;
			if(this.isDependant == true)
			{
				this.RemoveDependant();
				$(this.ddid).removeClass("dn");
				
			}
			else
				//this.hideDropDown();
				history.back();
			
			callBackFn.call(this.calledElement,this.output);
		};
		DropDown.prototype.ClearDD=function(){
			var ele = this;
			$(ele.ulOption).children(".selected").each(function(i, obj) {
				$(this).removeClass("selected");
				$(this).find("i.srfrm_checked").removeClass("srfrm_checked").addClass("srfrm_circle");
				if($(this).hasClass("hasDependent"))
				{
					$(this).find("i").attr("value","");
					$(this).find("i").find("span").html("");
					$(this).removeClass("tapped");
				}
			});
			
		};
			
		DropDown.prototype.GetOutput=function(){
			var ele = this;
			var tempOutput ="";
			ele.output=[];
			
			$(ele.ulOption).children(".hasDependent.selected").each(function(i, obj) {
				var temp={};
				var tempChild = {};
				if(!checkIfExists($(this).attr("value"),tempOutput))
				{
					tempChild[$(this).attr("value")]=$(this).find("i").attr("value");
					temp[$(this).attr("data")]=tempChild;
					ele.output.push(temp);
					
					
				}
				
			});
			$(ele.ulOption).children(".isGroup.selected").each(function(i, obj) {
					var temp={};
					temp[$(this).attr("data")]=$(this).attr("value");
					ele.output.push(temp);
					tempOutput = tempOutput +","+ $(this).attr("value");
				
				var myGroup = $(this).attr("group");
				$(ele.ulOption).children('li.inGroup[group="'+myGroup+'"]').each(function(i, obj) {
					$(this).removeClass('selected');
					tempOutput = tempOutput +","+ $(this).attr("value");
				
				});
				
			});
			var tvalArr = {};
			$(ele.ulOption).children(".noGroup.selected").each(function(i, obj) {
				var temp={};
				var myData =  $(this).attr("data").replace(/"/g, "\""); 
                                if(myData in tvalArr == false){
                                        temp[myData]=$(this).attr("value");
                                        tvalArr[myData]=$(this).attr("value");
                                        if(!checkIfExists($(this).attr("value"),tempOutput))
                                        {
                                                tempOutput = tempOutput +","+ $(this).attr("value");
                                                temp[$(this).attr("data")]=$(this).attr("value");
                                                ele.output.push(temp);
                                        }
                                }
				if(ele.realType=="lincome")
				{
					var temp={};
					myData =  $(this).next().attr("data"); 
					temp[myData]=$(this).next().attr("value");
					ele.output.push(temp);
					
				}
			});
			$(ele.ulOption).children(".inGroup.selected").each(function(i, obj) {
				var temp={};
				
				if(!checkIfExists($(this).attr("value"),tempOutput))
				{
					tempOutput = tempOutput +","+ $(this).attr("value");
					temp[$(this).attr("data")]=$(this).attr("value");
					ele.output.push(temp);
				}
				
			});
			ele.output = JSON.stringify(ele.output);
			
		};
		
		
		DropDown.prototype.hideDropDown=function()
		{
			if(this.isDependant == true)
			{
				this.Dependant = false;
				var ele=this.calledElement;
				this.type=$(ele).attr('dshow').toLowerCase();
				this.ulOption="#DD_OPTION";
				this.selectedValue = $(ele).find('div[data]').attr("data");
				this.isDependant = false;
				this.fillData=this.staticData[this.type];
				this.saveButton="#searchform_submit";
				this.clearButton="#searchform_clear";
				$(this.ulOption).removeClass("animateText");
				this.callBack=eval($(ele).attr("dcallback"));
				searchHamburger(this.ulOption);
				
			}
			$("#2dView").addClass("dn");
			
			ele=this;
			
			$("#DD_OPTION").unbind();
			$("#DD_OPTION_2").unbind();
			$("#searchform_submit").unbind();
			$("#searchform_submit_2").unbind();
			 $("#search_ham").unbind("input");
			 $("#searchform_clear").unbind();
			$("#searchform_clear_2").unbind();
			$("#search_ham").val("");
			$("#search_ham_2").val("");
			var timer=300;
			if(!Modernizr.csstransforms3d || ISBrowser("UC"))
	                {
				timer=1;
	                }   
			$(this.pcontid).removeClass("ham"+this.formation).removeClass("twodview"+this.formation);
			$(ele.ulOption).removeClass("animateText");
			$(ele.ddid).removeClass(ele.formation+"ham");
			$(ele.ddoverid).removeClass(ele.formation+"ham");
			setTimeout(function(){
				$(ele.ddid).addClass("dn");
				$(ele.ddid).removeClass("h_animation");
				$(ele.ulOption).html("");
				$(ele.ddoverid).html("");
				$(ele.ulOption).html($(ele.originalHtml));
				$(ele.ddoverid).removeClass("show").addClass("ltransform");
				$(ele.persid).removeClass("showpers");
				$(ele.pcontid).removeClass("hamb");
				startTouchEvents(10);
				$("#wrapper").remove();
			},timer);
			stopScrolling();
			startScrolling();
			
		};
		
		this.DropDown=DropDown;
		
	}).call(this);
})();
function UpdateSection(output)
{
	var output = jQuery.parseJSON(output);
	var labelArr=new Array();
	var valueArr=new Array();
	var nextLabel="";
	var nextValue = "";
	$i=0;
	var type = $(this).attr("dshow");
	$.each(output,function(key,obj){
		$.each(obj,function(key,value){
			if(type == "lincome" && $i==1)
			{
				nextLabel = key;
				nextValue = value;
			}
			else
			{
				valueArr[$i]=value;
				if(type.substring(1)=="height" && value!=37)
				key = key + "\"";
				labelArr[$i]=key;
				$i++;
			}
		});	
	});
	
	var element = $(this).find("div[data]");
	
	if(valueArr.length==0)
	{
			var mylabel="";
			if(type =="mtongue")
				mylabel="Any Mother Tongue";
			else if(type == "location")
				mylabel = "Any Country";
			else if(type == "location_cities")
				mylabel = "Any State/City";
                        else if(type == "manglik" || type == "occupation" || type == "education")
				mylabel = "Doesn't Matter";
			$(element).find("span[data]").html("");
			$(element).find("span.label").html(mylabel);	
	}
	else if(type=="location" || type=="location_cities" || type=="mtongue" || type=="occupation" || type=="education" || type=="manglik")
	{
		
		if(valueArr.length>1)
		{
			var trimLabel = labelArr[0];
			if(trimLabel.length >25)
				trimLabel = trimLabel.substring(0,22) + "...";
			$(element).find("span.label").html(trimLabel);
			$(element).find("span[data]").html("+"+(labelArr.length-1)+" more");
		}
		else
		{
			$(element).find("span.label").html(labelArr.join(", "));
			$(element).find("span[data]").html("");
		}
	}
	else
		$(element).find("span.label").html(labelArr.join(", "));
	$(element).attr("value",valueArr.join(","));
	$(element).attr("data",valueArr.join(","));
        if(type=="location"){
                updateLocationCities(type);   
           }
	var typeArray = ["lheight","lage","lincome"];
			
	if($.inArray(type,typeArray)!= -1)
	{
		var maxType = "#search_H"+type.substring(1).toUpperCase();
		var hdata = $(maxType).find('div[data]').attr("data");
		if(type!="lincome" && parseInt(hdata)<parseInt($(element).attr("data")))
		{
			$(maxType).find('div[data]').attr("value",valueArr.join(","));
			$(maxType).find('div[data]').attr("data",valueArr.join(","));
			$(maxType).find('div[data]').find("span.label").html(labelArr.join(", "));
		}
		else if(type=="lincome" && hdata!="19" && parseInt(hdata)<=parseInt($(element).attr("data")))
		{
			$(maxType).find('div[data]').attr("value",nextValue);
			$(maxType).find('div[data]').attr("data",nextValue);
			$(maxType).find('div[data]').find("span.label").html(nextLabel);
			
		}
	}
		
}
function updateLocationCities(type){
        var ele = $("#search_"+type.toUpperCase()).find("div[data]");
        var LocationData = $(ele).attr("data");
        LocationData = LocationData.split(",");
        if(jQuery.inArray("51",LocationData) === -1){
                $("#search_LOCATION_CITIES").addClass("dn");
                var element = $("#search_LOCATION_CITIES").find("div[data]");
                $(element).find("span.label").html("Any State/City");
                $(element).find("span[data]").html("");
                $(element).attr("value","");
                $(element).attr("data","");
        }else{
                $("#search_LOCATION_CITIES").removeClass("dn");
        }
}
function UpdateSectionWithDependant(output)
{
	var output = jQuery.parseJSON(output);
	var labelArr=new Array();
	var valueArr=new Array();
	var depArr = new Array();
	var depValue = new Array();
	var casteReligion ="";
	$i=0;
	$j=0;
	
	$.each(output,function(key,obj){
		$.each(obj,function(key,value){
			if($.type(value)==="string")
				valueArr[$i]=value;
			else
			{
				
				$.each(value,function(k,v){
					valueArr[$i]=k;
					if(v!="DONT_MATTER" && v!="14")
					{
						depArr[$j] = v;
						if(casteReligion=="")
							casteReligion = k;
					}
					depValue[$j]= '"'+k+'":"'+v+'"';
					$j++;
					
				});
				
			}
			
			labelArr[$i]=key;
			$i++;
		});	
	});
	if(casteReligion=="")
		casteReligion = valueArr[0];
	var element = $(this).find("div[data]");
	if(labelArr.length==0)
		$(element).find("span.label").html("Any Religion");
	else
		$(element).find("span.label").html(labelArr.join(", "));
	$(element).attr("value",valueArr.join(","));
	$(element).attr("data",valueArr.join(","));
	if(depArr.length>0 || depValue.length>0)
	{
		var dummy1 = depArr.join(",");
		var dummy2 = dummy1.replace(/[, ]+/g," ").trim();
		var depString = dummy2.split(" ");
		$(element).find("span[data]").attr("data","{"+depValue.join(",")+"}");
		if(depArr.length>0)
		{
			var getlabel = getHeading("caste",casteReligion);
			if(depString.length>1)
				getlabel = getlabel+"s";
			$(element).find("span[data]").html(depString.length + " "+getlabel);
		}
		else
			$(element).find("span[data]").html("");
		
		
	}
	else
	{
		$(element).find("span[data]").attr("data","");
		$(element).find("span[data]").html("");
	}
	
}

function UpdateDependantSection(output)
{
	var output = jQuery.parseJSON(output);
	var labelArr=new Array();
	var valueArr=new Array();
	$i=0;
	$.each(output,function(key,obj){
		$.each(obj,function(key,value){
			valueArr[$i]=value;
			labelArr[$i]=key;
			$i++;
		});	
	});
	
	var ele=$('li.tapped');
	if(ele)
	{
		$(ele).removeClass("tapped");
		if($i>0)
		{
			$(ele).addClass("selected");
			$(ele).find("i").attr("value",valueArr.join(","));
			$(ele).find("i").find("span").html($i);
		}
		else
		{
			$(ele).removeClass("selected");
			$(ele).find("i").attr("value","");
			$(ele).find("i").find("span").html("");
		}
	}
		
}
function getTimer(type,caste)
{
	switch(type){
		case 'age' : return 70;
		case 'height' : return 70;
		case 'income' : return 70;
		case 'mtongue' : return 200;
		case 'location' : return 470;
		case 'religion' : return 150;
		case 'caste':
			if(caste==1)
				return 430;
			else if(caste==3)
				return 350;
			else
				return 70;
	}
}


function getHeading(type,caste)
{
	switch(type){
		case 'lage' : return "Minimum Age";
		case 'hage' : return "Maximum Age";
		case 'lheight' : return "Minimum Height";
		case 'hheight' : return "Maximum Height";
		case 'lincome' : return "Minimum Income";
		case 'hincome' : return "Maximum Income";
		case 'mtongue' : return "Mother Tongue";
		case 'location' : return "Country";
		case 'location_cities' : return "State/City";
		case 'religion' : return "Religion";
		case 'education' : return "Education";
		case 'occupation' : return "Occupation";
		case 'manglik' : return "Manglik";
		case 'caste':
			if(caste==2 || caste==3)
				return "Sect";
			else
				return "Caste";
				
			
	}
}

function checkIfExists(value,preValue,isComplete)
{
	
	if(preValue=="")
		return false;
	if(isComplete)
	{
		if(value==preValue)
			return true;
		else
			return false;
	}
	else if(typeof(value)=="string" && value.indexOf(",")>-1)
	{
		if(preValue.indexOf(value)>-1)
			return true;
		else
			return false;
	}
	else
	{
		if($.inArray(value.toString(),preValue.split(","))!=-1)
			return true;
		else
			return false;
	}
}

(function() {
  var CheckAbbr;

  CheckAbbr = (function() {

    function CheckAbbr() {
      this.defaultAbbr={'LOCATION':{'USA':'United States',"US":"United States","UK":"United Kingdom","UAE":"United Arab Emirates",'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"},'CASTE':{"AGRAW":"Aggarwal"}};
    }

    CheckAbbr.prototype.checkNode = function(type,value) {
			temp=type.toUpperCase();
			value=value.toUpperCase();
			if(this.defaultAbbr.hasOwnProperty(temp))
      if(this.defaultAbbr[temp].hasOwnProperty(value))
				return this.defaultAbbr[temp][value];
				
			return value;
    };
    return CheckAbbr;

  })();
  this.CheckAbbr = CheckAbbr;

}).call(this);
function searchHamburger(option,type)
{
    var k=0;
    var valjson={};
    var checkAbbr=new CheckAbbr;
    var regexAnchor="^";
    var lastValue ="";
    var prev="";
   
    $(option).children().each(function(key,val)
    {
	var str=$(val).children().first().text();
	var state="0";
	valjson[k]={key:key,id:val,str:str,state:state};
	k = k+1;
	
	
    });
    
    $("#search_ham").val("");
    $("#search_ham_2").val("");
    $("#search_ham").one("click",function(ev)
    {
	    stopPropagation(ev);
	captureState();
	
    });
     $("#search_ham_2").bind("click",function(ev)
    {
	    stopPropagation(ev);
    });
    	
    $("#search_ham").bind("input",function(ev)
    {
	    stopPropagation(ev);
	    searchValue($(this));
    });
    $("#search_ham_2").bind("input",function(ev)
    {
	    stopPropagation(ev);
	  searchValue($(this));
     });
    
	function captureState()
	{
		$.each(valjson,function(j,v)
		{
			if(!$(v.id).hasClass("isGroupheading"))
			{
				if($(v.id).hasClass("hide"))
					v.state = "1";
				else
					v.state = "0";
			}
			else
				v.state = "0";
		});
	}
	    
	    
	function searchValue(ele)
	{
		$("#noresf").remove();
		
		if($(ele).val().trim()=="")
		{
			prev="";
			$.each(valjson,function(j,v)
			{
				if(v.state=="0")
					$(v.id).removeClass("hide");
				else
					$(v.id).addClass("hide");
			});
			if($(ele).attr("id")=="search_ham")
			{
				$("#search_ham").unbind("click");
				$("#search_ham").one("click",function(ev)
				{
					stopPropagation(ev);
					captureState();
				});
			}
			
		}
		else
		{
				$(ele).unbind("click");
				$(ele).bind("click",function(ev)
				{
					stopPropagation(ev);
					
				});
		var findArr={};
			if(prev==($(ele).val().trim()))
			return;
		var searchResultClass= 'matchResult';
		prev=$(ele).val();
		prev=checkAbbr.checkNode(type,prev);
		if(!prev)
                {
                    $.each(valjson,function(j,v)
                    {
                       $(v.id).removeClass("hide");
                    });
                    return;
                }
		var l= $(ele).val().trim().length;
		var flag2=0;
		$.each(valjson,function(j,v)
		{
			var flag=0;
			if(v.str=="hideDivMore")
            {
             $(v.id).addClass("hide");
              return true;
            }
            var realStr=v.str.trim().replace("("," ").replace(")","");
			var strArr=realStr.split(/[\ \/,]/);
			
			var regex = new RegExp(regexAnchor + prev.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');

			 var findinArr=0;
             for(var i=0;i<=strArr.length;i++)
             {
                 if(strArr.length==i)
                 var str=v.str.trim();
                 else    
                 str=strArr[i];

                var StrTocheck = str.replace(".","");
			if((regex.test(str) || regex.test(StrTocheck) ) && !$(v.id).hasClass("isGroupheading"))
			{
				if(!findArr[realStr])
				{
					$(v.id).removeClass("hide");
					
					flag2=1;
					findArr[realStr]=1;
				}
				else
					$(v.id).addClass("hide");
					findinArr=1;
					break;
			}
			else
			{
				$(v.id).addClass("hide");
			}		
		}
		});
		
		if(flag2==0)
		{
			$.each(valjson,function(j,v)
			{    
				str2=v.str;
				
				wordstr2=str2.split(/\ |\//);
				var wordstr=prev;
				
				for(var i=0;i<wordstr2.length;i++)
				{   
					var z=wordstr2[i];
					if(l>3)
					{
						
						y=levenshtein_distance_ham(z,$(ele).val())
						
						if(y<=2)
						{
							if(!findArr[str2])
							{
								findArr[str2]=1;
								$(v.id).first().removeClass("hide");
								$(v.id).not(":eq(0)").removeClass("hide");
								
							}
							else
							{
								$(v.id).addClass("hide");
							}
						}
						else
							$(v.id).addClass("hide");
					}
				}
			});
		}
		if(Object.keys(findArr).length<=0)
		{
			if(!$("#noresf").length)
			$(option).append("<li value=-1 id='noresf' class='hpad5 color14 f14' >No results found</li>")
		}
		
		return $(ele).val();
		}
		
	}
}


function levenshtein_distance_ham (a, b) {

	a=a.toLowerCase();
	b=b.toLowerCase();
	
      if(a.length == 0) return b.length;
      if(b.length == 0) return a.length;


      var matrix = [];

      // increment along the first column of each row
      var i;
      for(i = 0; i <= b.length; i++){
        matrix[i] = [i];
      }

      // increment each column in the first row
      var j;
      for(j = 0; j <= a.length; j++){
        matrix[0][j] = j;
      }

      // Fill in the rest of the matrix
      for(i = 1; i <= b.length; i++){
        for(j = 1; j <= a.length; j++){
          if(b.charAt(i-1) == a.charAt(j-1)){
            matrix[i][j] = matrix[i-1][j-1];
          } else {
            matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, // substitution
                                    Math.min(matrix[i][j-1] + 1, // insertion
                                             matrix[i-1][j] + 1)); // deletion
          }
        }
      }


      return matrix[b.length][a.length];
    }


