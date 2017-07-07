var hamHtml="",slider1,slider2;

(function() {
	var eHamburger=(function(){
		function eHamburger(element,arr){
			
			this.optionHeight=100;
			this.json={};
			this.json=changingEditData[arr[0]][arr[1]];
			
			this.output={};
			this.ham_htm=this.UpdateHAMHtml($("#ehamburger").html());
			this.ulOption="#HAM_OPTION_1";
			this.ulOption_real=this.ulOption;
			this.ulOption_second="#HAM_OPTION_2_1";
			this.tapid=1;
			this.hamid="#ehamburger";
			this.hamoverid="#hamoverlay";
			this.persid="#perspective";
			this.pcontid="#pcontainer";
			this.saveButton="#SAVE_DONE";
			
			
			this.calledElement=element;
			this.formation=$(element).attr("dmove")=="right"?"r":"l";
			this.whenHide=$(element).attr("dhide");
			this.inputtype=$(element).attr("dselect");
			
			this.callBack=eval($(element).attr("dcallback"));
			this.dependant=$(element).attr("dependant");
			this.indexPos=$(element).attr("dindexpos");
			this.selectedValue=-1;
			if(hamHtml)
			{
					this.originalHtml=hamHtml;
					
			}		
			else
			{
					this.originalHtml=hamHtml=$("#HAM_OPTION_4").html();
					
			}		
			
			this.alreadyUpdated=false;
			var ele=this;
			
			if(this.whenHide=="multiple")
				this.optionHeight=220;
			
			$(element).bind("click",function(){	
			//$(element).click({longTapThreshold:longTapThreshold,longTap:function(){
				
				ele.output={};
				ele.selectedValue=-1;
				ele.type=$(element).attr('dshow').toLowerCase();
				
				ele.ulOption="#HAM_OPTION_1";
				ele.ulOption_real=ele.ulOption;
				ele.ulOption_second="#HAM_OPTION_2_1";
				ele.tapid=1;
				stopTouchEvents(1);
				$(ele.hamid).removeClass("dn");
				(function(elem)
				{
					setTimeout(function(){
						elem.ShowHamburger();
					},animationShow);
				})(ele);
				
				//}
				});
		
		
		};
		
		eHamburger.prototype.UpdateHAMHtml=function(html){
			html=html.replace(/HAM_OPTION_1/g,"HAM_OPTION_2_1");
			html=html.replace(/HAM_FORM_1/g,"HAM_FORM_2");
			html=html.replace(/HAM_1/g,"HAM_2");
			html=html.replace(/arw_1/g,"arw_2");
			html=html.replace(/search_ham_1/g,"search_ham_2");
			html=html.replace(/TAPNAME_1/g,"TAPNAME_2");
			
			return html;
		};
		eHamburger.prototype.ShowHamburger=function(){
			
			//showing loader
			var topY=$(window).height()/2;
			//$("#HAM_TOP").after("<div id='load' class='loaderimg' style='right:1px;width:50%;top:"+topY+"px'></div>");
			//ending loader
			$(this.ulOption).html("");
			if(this.whenHide=="multiple")
			{
				this.EnableSaveButton();
			}
			ShowHamSearch(this.type,this.tapid);
                        this.AppendLoader();
			
                        
			$(this.hamid).addClass(this.formation+"ham");
			
			$(this.persid).addClass("showpers");
			$(this.pcontid).addClass("hamb").addClass("ham"+this.formation);
			if(!this.isMultiOption())
				$(this.pcontid).addClass("tcenter"+this.formation);
                            
                        if(!Modernizr.csstransforms3d || ISBrowser("UC") || ISBrowser("AndroidNative"))
                        {
                           $(this.pcontid).addClass("twodview"+this.formation);
                           setTimeout(function(){$("#2dView").removeClass("dn");
                           
                           },animationtimer);
                        }    
			$(this.pcontid).prepend("<div class='wrapper' id='wrapper'></div>");
			var ele=this;
			
			$("#wrapper,#ehamburger,#hamoverlay,#arw_1,#arw_2,#2dView").unbind("click");
			
			this.SetNoClose();
			$("#wrapper,#ehamburger,#hamoverlay,#arw_1,#arw_2,#2dView").bind("click",function(ev){
				popBrowserStack();
				return false;});
			$(this.ulOption).addClass("animateText");
			
			historyStoreObj.push(function(){return editHideHamburger(ele)},"#ehamburger");
			//HistoryStore["dropdown"]=this;
			try
			{
				if(this.isMultiOption())
					this.MultiHamburger();
				else
				{
						setTimeout(function(){ele.UpdateHamburgerHTML();startScrolling();},animationtimer);
						
				}
			}
			catch(e)
			{
				//console.log(e);
				this.CloseAndShowError();
				
			}
			//Bind touch events on HAM_OPTION div.
			
			
            startTouchEvents(animationtimer);
			
		};
		eHamburger.prototype.isMultiOption=function()
		{
			
			var html=this.originalHtml;
			if($.inArray(this.type,["time_to_call_start","p_age","p_height","p_income_dol","p_income_rs"])==-1)
			{
				$(this.ulOption).parent().parent().css("width","100%");
				html=html.replace(/\{\{txtc\}\}/,"");
				
				
				this.originalHtml=html;
				return false;
			}
			html=html.replace(/\{\{txtc\}\}/,"txtc");
			if(this.type=="time_to_call_end")
			{
				
			}	
			return true;
		};
		eHamburger.prototype.hideHamburger=function()
		{
                        $("#2dView").addClass("dn");
                        $("#clearBtn").remove();
                        $("#suggestBox").remove();
			$(this.pcontid).removeClass("ham"+this.formation).removeClass("tcenter"+this.formation).removeClass("twodview"+this.formation);
			var ele=this;
					
			for(i=1;i<=4;i++)
			{
				$("#HAM_OPTION_"+i).swipe("destroy");
				$("#HAM_OPTION_"+i).unbind("click");
				$("#HAM_OPTION_"+i).unbind("touchstart");
			}
			ele.CloseMulti(0);	
			if(ISBrowser("UC"))
				$(this.ulOption).html("");
			$("#HAM_LABEL").html("").addClass('dn');
			$(this.saveButton).addClass("dn");	
			$(this.ulOption).removeClass("animateText");
			$(this.hamid).removeClass(this.formation+"ham");
			$(this.hamoverid).removeClass(this.formation+"ham");
			setTimeout(function(){
				$(ele.hamid).addClass('dn');
				$(ele.hamoverid).html("");
				
				$(ele.ulOption_real).html("");
				$(ele.ulOption).html("");
				$("#HAM_OPTION_1").html("");
				$("#HAM_OPTION_2").html("");
				$("#HAM_OPTION_3").html("");
				$(ele.hamoverid).removeClass(ele.formation+"ham").removeClass("show").addClass("ltransform");
				
				
				$(ele.persid).removeClass("showpers");
				$(ele.pcontid).removeClass("hamb");
				$("#wrapper").remove();
				
				$("#TAPNAME_1").html("");
				startTouchEvents(10);
				
				},animationtimer);
				stopScrolling();
				startScrolling();
				
		};
		eHamburger.prototype.updateHamOption=function(id,data,center,which)
		{
			var html=$("#HAM_OPTION_4").html();
			var ele=this;
			var finalarr=new Array();
			html=UpdateHtml(html,{"txtc":center});
			//selected value
			var selectedVal;
			var curJson=this.json.OnClick[this.indexPos];
			var keyArr=this.getMultiKeys(this.type);
			//console.log(curJson);
			if(curJson.value && curJson.value!="DM,DM")
			{
				var tempVal=curJson.value+"";
				var tempArr=tempVal.split(",");
				selectedVal=tempArr[which];
			}
			else
			{
				if(ele.type=="time_to_call_start")
					if(which)
						selectedVal="6 PM";
					else
					{
						keyName="time_to_call_end";
							selectedVal="9 AM";
					}
				if(ele.type=="p_income_rs" || ele.type=="p_income_dol")
					if(which)
						selectedVal="19";
					else
						selectedVal="0";
			}
			
			$.each(data,function(st,json)
			{
				//var json=data[st];
				//console.log(json);
				$.each(json,function(k,v)
				{
					$.each(v,function(value,label)
					{
						
						
						var hamclass="checked";
						var hamclass_noselect="";
						var isarr="";
						if(value==-1 || value=="-1")
							hamclass_noselect="noselect";
						
						var temp=html;
						var type=keyArr[which];
						var labelText=label;
						if(ele.type=='p_age')
							labelText=label+" years";
						temp=temp.replace(/HAM_VALUE/g,value);
						temp=temp.replace(/HAM_LABEL/g,labelText);
						temp=temp.replace(/HAM_TYPE/g,ele.inputtype);
						temp=temp.replace(/HAM_NAME/g,type+isarr);
						
						
						//console.log(value+" "+selectedVal);
						if(selectedVal)
						{
								if((value+"").replace(":00","").toUpperCase()==selectedVal.toUpperCase() || (value+"").toUpperCase()==selectedVal.toUpperCase())
								{
										temp=temp.replace(/HAM_CLASS/gi,hamclass);
										keyName=keyArr[which];
										//console.log("Key"+keyName);
										 ele.OutputUpdate(keyName,label,value);
								}
						}		
						temp=temp.replace(/HAM_CLASS/gi,hamclass_noselect);
							
							
						
						
						finalarr[i]=temp;
						i++;
					});
				});
			});
			
			$(id).html("");
			$(id).html(finalarr.join(""));
			
			$(id).parent().removeClass("dn");
			
			
		};
		eHamburger.prototype.MultiHamburger=function()
		{   
			$("#search_ham_"+this.tapid).parent().parent().addClass('dn');
			var ele=this;
			var txtc="txtc fullwid";
			var type=this.type;
			var data=this.FilterData(JSON.parse(this.CheckResponse(staticTables.getData(this.type))));
			$("#TAPNAME_"+this.tapid).html(this.TapName());	
			var finalarr=new Array();
			var curJson=this.json.OnClick[this.indexPos];
			var html=$("#HAM_OPTION_4").html();
			var cnt=1;
			
			this.updateHamLabel();
			//if(this.type=="time_to_call_start" || this.type=="p_height" || this.type=="p_age" )
			if(1)
			{
				
				cnt=2;
				
			}
			else
			{}
			var height=this.getDivHeight();
			//var height=370;
			
			var ids=[];
			for(var ham_i=1;ham_i<=cnt;ham_i++)
			{
				ids[ham_i-1]="#HAM_OPTION_"+ham_i-1;
				
			}
			$("#ham_load").remove();
			for(var ham_i=1;ham_i<=cnt;ham_i++)
			{
				
				//Dollar left and right valeus are differcnt[so have to update data from json.
				var rsCheck={"p_income_rs":{1:0,2:1},"p_income_dol":{1:0,2:1}};
				var tempData;
				if(rsCheck[type])
					tempData=data[rsCheck[type][ham_i]];
				else
					tempData=data;
				(function(i,type)
				{
					var id="#HAM_OPTION_"+i;
					$(id).removeClass('dn');
					ele.updateHamOption(id,tempData,txtc,i-1);
				$(id).parent().css("width",100/cnt+"%").css("float","left").css("height",height).css("overflow","auto").css("position","relative");
					var indh=$(id).children().first().outerHeight();
			if(indh<=0)
				indh=40;
			var hgt=$(id).children().first().height();
			var width=$(id).children().first().width();
			var showP=Math.abs(Math.ceil(height/indh));
			var up,down;
			up=down=Math.floor(showP/2);
			
			if(showP%2==0)
			{
				up=Math.floor(showP/2);
				down=Math.ceil(showP/2);
			}
			for(var itemp=0;itemp<up;itemp++)
				$(id).prepend("<li class='hpad5' fake=1><div class='fl f16 color17 txtc fontlig' style='color:#2c3137;height:"+hgt+"px'>0</div><div class='clr'></div></li>");
			for(var itemp=0;itemp<down;itemp++)
				$(id).append("<li class='hpad5' fake=1><div class='fl f16 color17 txtc fontlig' style='color:#2c3137;height:"+hgt+"px'>0</div><div class='clr'></div></li>");	
			//$("#HAM_OPTION_1").parent().scrollTop(indh*up);	
			var topPos=$($(id).children()[up]).position().top;
			this.topPos=topPos;
			this.topPos=topPos=indh*up;
			var di="<div style='position:absolute;background:#34495e;top:"+topPos+"px;height:"+indh+"px;width:100%;opacity:.4;padding:10px'></div>";
			$(id).parent().prepend(di);
			if($(id).attr("id").split("_")[2] == 1) {
				slider1 = $(id).VSlider({"width":"100%","height":hgt,"sliderHeight":indh,"fakeb":down,"faket":up,"ids":ids,"type":type,"who":i-1});
			} else {
				slider2 = $(id).VSlider({"width":"100%","height":hgt,"sliderHeight":indh,"fakeb":down,"faket":up,"ids":ids,"type":type,"who":i-1});
			}
				})(ham_i,type);
			}
			if(type == "p_age") {
				setTimeout(function(){
					typeDataArray = [$("#HAM_OPTION_1 li input:checked").val(),$("#HAM_OPTION_2 li input:checked").val()];
					changeSuggestion("AGE", typeDataArray);
				},100);	
			} else if (type == "p_income_rs") {
				setTimeout(function(){
					typeDataArray = [$("#HAM_OPTION_1 li input:checked").prev().html(),$("#HAM_OPTION_2 li input:checked").prev().html(),"No Income","and above"];
					changeSuggestion("INCOME",typeDataArray);
				},100);	
			} else if(type == "p_income_dol") {
				setTimeout(function(){
					typeDataArray = ["No Income","and above",$("#HAM_OPTION_1 li input:checked").prev().html(),$("#HAM_OPTION_2 li input:checked").prev().html()];
					changeSuggestion("INCOME",typeDataArray);
				},100);	
			}
			BindHamWindow(this);
			
				
			
		};
		eHamburger.prototype.ResizeVSlider=function(ev)
		{
			var checkedRadio=$("input[type='radio']:checked");
			this.CloseMulti(1);
			for(var i=0;i<checkedRadio.length;i++)
			{
				var id="#HAM_OPTION_"+(i+1)+" input[value='"+$(checkedRadio[i]).attr("value")+"']";
				var index=parseInt($(id).parent().attr("index"));
				
				$("#HAM_OPTION_"+(i+1)).trigger("gotoSlide",[index,1]);
			}
			
		}
		eHamburger.prototype.MultiHamClick=function(ele,i)
		{
			
			
		};
		eHamburger.prototype.removePreviousClick=function(element)
		{
			var ele=this;
			$.each($(element).parent().parent().children(),function(key,val){
				
				if($(val).hasClass("checked"))
				{
					var id=$(val).children().first();
					
					ele.UpdateOutput(id);
						
				}
			});
		}
		eHamburger.prototype.UpdateHamburgerHTML=function()
		{
			this.SetNoClose();
			var ele=this;
			var html=this.originalHtml;
			

			//State Living in
			$("#TAPNAME_"+this.tapid).html(this.TapName());	
			
			
			
					
			//selected value
			var selArr=new Array();
			var curJson=this.json.OnClick[this.indexPos];
			
		if(curJson.callBack == "updateLifestyle" || curJson.key.indexOf("P_") != -1) {
			setTimeout(function(){
				$('<span id="clearBtn" class="white fontthin f17 pt4 fr pr9 vAlignSub">Clear</span>').insertAfter("#TAPNAME_1");
					$("#clearBtn").off("click").on("click",function(){
						$("#HAM_OPTION_1 li input:checked").each(function(){
							$(this).parent().click()
					});
				});	
			},1000);
					
		}
		
		//	console.log(curJson);
			if(curJson.value)
			{
				var tempVal=curJson.value+"";
				var tempArr=tempVal.split(",");
				if(this.dependant)
				{
						if(this.selectedValue==-1)
							selArr[0]=tempArr[0];
						else
							selArr[0]=tempArr[1];
				}
				else
					selArr=tempArr;
			}
			var ele=this;
			var i=0;
			
			
			//Updating the UL option starts here.
			this.FetchUlOption(selArr);
			$("#arw_2").unbind("click");
			$("#arw_2").bind("click",function(ev){

                                popBrowserStack();return false;});	
            //Ending the updation ends here
            
            
			
			//$(this.ulOption).OnlyVertical
			
		};
		eHamburger.prototype.updateHamLabel=function()
		{
			if(this.isMultiOption() && $("#HAM_LABEL").hasClass('dn'))
			{
				var tempStr1,tempStr2;
				tempStr1=tempStr2=$("#HAM_MULTI").html();
				var json={"FROM_TO":"From","WIDTH":"wid49p fl","txtc":"txtc"};
				
				tempStr1=UpdateHtml(tempStr1,json);
				json={"FROM_TO":"To","WIDTH":"wid49p fl","txtc":"txtc"};
				tempStr2=UpdateHtml(tempStr2,json);
				$("#HAM_LABEL").append(tempStr1).append(tempStr2).removeClass("dn");
				this.EnableSaveButton()
				
			}
				
			
		};
		eHamburger.prototype.HamElementClick=function(ev,ele)
		{
			ev.stopPropagation();
			if(ele.hasClass("suggestTitle") == false && ele.attr("id") != "suggestBox") {
				var value=this.UpdateOutput(ele);
		        if(this.type == "native_state" && value== "NI"){
			        this.type="native_country";
			        this.UpdateHamburgerHTML();
			        return;
		        }
      
		        if(this.type == "native_country" && value== "FI"){
			        this.type="native_country";
			        this.type="native_state";
			        this.UpdateHamburgerHTML();
			        return;
		        }
      
				var thisObj=this;
				if(value===false && (this.whenHide=="multiple" && this.type==this.dependant))
					return;
				
				if(this.whenHide=="multiple")
				{
					
				}
				else if(this.dependant && this.type!=this.dependant && this.AnySpecialCheck(value)&& !(this.type == "native_state" && value==0) && !(this.type == "native_country" && value==0))
				{
					stopTouchEvents(1);
					this.type=this.dependant;
					$(this.hamoverid).html(this.ham_htm);
					this.ulOption=this.ulOption_second;
					this.tapid=2;
					$("#search_ham_"+this.tapid).parent().parent().addClass('dn');
					var height=$(window).height();
	                                this.AppendLoader();
					$("#hamoverlay").removeClass("ltransform").addClass("rham").addClass("show");
					
					setTimeout(function(){thisObj.UpdateHamburgerHTML();startTouchEvents(animationtimer);},animationtimer);
					
					
					//this.type=dependant;
					
						
				}
				else
				{
					stopTouchEvents(1);
					popBrowserStack();
					this.callBack.call(this.calledElement,this.output,this.json,this.indexPos);
					//console.log(this.output);
				}
			}
			
			
		};
		eHamburger.prototype.UpdateOutput=function(target,add,remove)
		{
			
			var label=$(target).text();
			
			var value=$(target).next().val();
			var remove=0;
			this.selectedValue=value;
			if(this.whenHide!='multiple')
			{
				//console.log($(target).siblings('[type="radio"]'));
				//console.log($(target).siblings('[type="radio"]').prop("checked"));
				
				if($(target).siblings('[type="radio"]').prop("checked"))
				{
					this.OutputUpdate(this.type,label,value);
					if(this.whenHide=="decide")
						return value;
						
					return false;
				}		
				else
				{
					var el=$("[name=\""+this.type+"\"]:checked");
					//console.log(el);
					if(el.length)
					{
						$(el[0]).prop("checked",false);
						$(el[0]).parent().removeClass("checked").removeClass("multiple");
					}
				}	
			}
			
			//console.log('getting here');
			if($(target).next().prop("checked"))
			{
					$(target).next().removeAttr("checked");
					$(target).next().prop("checked",false);
					remove=1;
					$(target).parent().removeClass("checked").removeClass("multiple");
			}
			else {	
				if($(target).hasClass("suggestTitle") == false && $(target).hasClass("suggestBox") == false) {	
					$(target).next().prop("checked","checked");
					$(target).next().attr("checked","checked");
					$(target).parent().addClass("checked");
					if(this.whenHide=="multiple")
						$(target).parent().addClass("multiple");
				}
			}		
			
			if(remove)
			{
				if(this.output[this.type])
					delete this.output[this.type][label];
			}
			else
			{
				this.OutputUpdate(this.type,label,value);	
			}
			if($("#suggestBox").length == 0 || $("#suggestBox").attr("suggest-click") == 1) {
				$("#suggestBox").removeAttr("suggest-click");
				var typeDataArray = [],type = "";
				if($(target).next().attr("name").indexOf("p_") != -1) {
					type = $(target).next().attr("name").split("p_")[1].split("[]")[0].toUpperCase();
				}
				
				if(type == "RELIGION" || type == "CITY" || type == "CASTE" || type == "MTONGUE" || type == "EDUCATION") {
					$("#HAM_OPTION_1 li input:checked").each(function(){
						typeDataArray.push($(this).val());
					});
					changeSuggestion(type,typeDataArray);
				}
			} else {
				if(remove == 0) {
					$(".suggestOption[value='"+value+"']").addClass("bg7");	
				} else {
					$(".suggestOption[value='"+value+"']").removeClass("bg7");	
				}
			}

			return value;
		};
		eHamburger.prototype.FilterData=function(json)
		{
			
			if(this.selectedValue!=-1)
			{
				//console.log(this.type);
				
				//~ console.log(this.selectedValue);
				//~ console.log(json);	 
				//~ console.log(json[this.selectedValue]);
				if(json[this.selectedValue])
					return json[this.selectedValue];
				else if(this.type=="TIME_TO_CALL_END")
					return json;
					
				//return false;	
			}
			if(this.type=="caste" || this.type=='sect')
			{
				var dependant=getDependantValue('caste');
				return json[dependant];
			}
			if(this.type=="income" || this.type=="family_income")
			{
				var dependant=getDependantValue("income");
				return json[dependant];
			}
			//if dependant filed
			if(this.type=="p_city")
			{
				var findia=0;
				var output={};
				var dependant=getDependantValue('p_city');
				if(dependant.replace(51,"")!=dependant)
				{	
					output=json["51"];
					
					findia=1;
				}
				if(dependant.replace(128,"")!=dependant)
				{
					if(findia)
						output=ExtendData({},json["128"],output);
					else
						output=json["128"];
				}
				
				return output;
				
			}
			if(this.type=="city_res")
			{
				var dependant1 = getDependantValue('city_res_country');
				if(dependant1=="51")
				{
					var dependant2 = getDependantValue('city_res_state');
					if(dependant2)
					{
						return json[dependant1][dependant2];
					}
				}
				else if(dependant1=="128")
				{
					return json[dependant1];
				}
			}
			if(this.type=="p_caste" || this.type=="p_sect")
			{
				var dependant=getDependantValue('p_caste');
				var findia=0;
				var output={};
				var arr=dependant.split(",");
				for(var i=arr.length-1;i>=0;i--)
				{
					if(typeof(json[arr[i]])!="undefined")
					output=removeOthersFieldValue(output,json[arr[i]][0]);
					
					
						
				}
				var ret={0:output};
				return ret;
			}
			else
			return json;
		};
		eHamburger.prototype.AnySpecialCheck=function(value)
		{
			//Sibling check
			
			if(this.type=="T_BROTHER" || this.type=="T_SISTER")
			{
				if(value==0)
				{
						if(this.type=="T_BROTHER")
							this.OutputUpdate("M_BROTHER",0,0);
						else
							this.OutputUpdate("M_SISTER",0,0);	
						return false;
				}
				return true;
			}
			
			if(this.type=="country_res" && value!=51 && value!=128)
			{
				
					return false;
			}
			
			if((this.type=="t_brother" || this.type=="t_sister") && value==0)
				return false;
			
      if(this.type=="native_country"){
        return false;
      }
			return true;
		}
		eHamburger.prototype.TapName=function()
		{
			if(this.type=="M_BROTHER" || this.type=="M_SISTER")
				return "of which married";
			if(this.type.toLowerCase()=="city_res")
				return "City";
			else
			return this.json[jsonEntry][this.indexPos]["label"];
		};
		
		eHamburger.prototype.SendCloseHam=function(){			
      popBrowserStack();
		};
		eHamburger.prototype.EnableSaveButton=function(){
			var ele=this;
			$(this.saveButton).removeClass("dn");
				//$(this.saveButton).swipe({tap:function(ev,target){
				$(this.saveButton).unbind();
					$(this.saveButton).bind("click",function(){
					//checkMulti
					ele.SendCloseHam();
				});
				//}});
		};
		eHamburger.prototype.getMultiKeys=function(type)
		{
			var keyName=new Array();
			if(type=="time_to_call_start")
			{
				keyName[0]="time_to_call_start";
				keyName[1]="time_to_call_end";
			}
			if(type=="p_height")
			{
				keyName[0]="p_lheight";
				keyName[1]="p_hheight";
			}
			if(type=="p_age")
			{
				keyName[0]="p_lage";
				keyName[1]="p_hage";
			}
			if(type=="p_income_rs")
			{
				keyName[0]="p_lrs";
				keyName[1]="p_hrs";
			}
			if(type=="p_income_dol")
			{
				keyName[0]="p_lds";
				keyName[1]="p_hds";
			}
			return keyName;
		}
		eHamburger.prototype.CloseMulti=function(fromResize)
		{
			var cnt=0;
			
			var keyName=this.getMultiKeys(this.type);
			
			if(this.isMultiOption())
			{
				var cnt=keyName.length;
	
			}
			for(var i=1;i<=cnt;i++)
			{
				
				var id="#HAM_OPTION_"+i;
				var rid=$(id).children().children('[type="radio"]:checked');
				var label=($(rid).prev().html())?($(rid).prev().html()):"";
				
					
				label=label.replace(" years","");
				var value=$(rid).val();
				
				if(!fromResize)
					this.OutputUpdate(keyName[i-1],label,value);
				
				
				$(id).unwrap();
				$(id).removeAttr("style");
				$(id).parent().children().first().remove();
				$(id).parent().removeAttr("style");
				$(id).parent().addClass("dn");
				$(id).swipe("destroy");
				$(id).unbind("touchstart");
			}
			//Again setting Multi option
			if(fromResize)
			{
					this.MultiHamburger();
			}		
			else
				UnBindHamWindow();
			
		}
		eHamburger.prototype.OutputUpdate=function(key,label,value)
		{
			
			
			if(!key)
			{
				//console.log("key blank coming");
				return;
			}
			key=key.toLowerCase();
			
			
			//console.log(key+" "+label+" "+value);
			if(!this.output[key] || this.whenHide!='multiple')
				this.output[key]={};
			
			
			this.output[key][label]=value;
			//console.log(this.output);
			
		};
		eHamburger.prototype.getDivHeight=function(){
			var threshold=8;
			var height=$(window).height()-threshold;
			var ham_top=height-$("#HAM_TOP").outerHeight();
			if($($("[ham_search]")[0]).css("display")!='none')
				ham_top-=$($("[HAM_SEARCH]")[0]).outerHeight();
			else if($($("[ham_search]")[1]).css("display")!='none')
				ham_top-=$($("[HAM_SEARCH]")[1]).outerHeight();	
			if(!$(this.saveButton).hasClass('dn'))	
				ham_top-=$(this.saveButton).outerHeight();
			
			return ham_top;
				
			
		};
		eHamburger.prototype.SetNoClose=function()
		{
			$("#HAM_1,#HAM_2,#search_ham_1,#search_ham_2").unbind("click");
			$("#HAM_1,#HAM_2,#search_ham_1,#search_ham_2").bind("click",function(ev){
				stopPropagation(ev);
			});
		}
		eHamburger.prototype.CheckResponse=function(val)
		{
			
			if(parseInt(val)==-1)
			{
				startTouchEvents(10);
				throw new Exception("Not Json");
			}
			return val;
		}
		eHamburger.prototype.CloseAndShowError=function()
		{
			popBrowserStack();
			
	
	$("#validation_error").text("something went wrong");
			setTimeout(function(){
				showErrorOverLayer("fromSubmit");
				setTimeout(function(){RemoveOverLayer();},animationtimer);
				},
				animationtimer+300);
			return true;
			
		}
		eHamburger.prototype.FetchUlOption=function(selArr)
		{
			var ele=this;
			
      var keyName = this.type;
      var arrKeyMap = ['native_state','native_country']
      if(arrKeyMap.indexOf(keyName) != -1){
        keyName = keyName + '_jsms';
      }
	if(keyName=="city_res")
		keyName="reg_city_jspc";
	if(keyName=="p_caste")
		keyName="p_caste_jsms";
	if(keyName=="p_sect")
		keyName="p_sect_jsms";
			staticTables.getData(keyName,function(data){ele.UpdateUlOption(selArr,data)});
			
			
			
		}
		eHamburger.prototype.UpdateUlOption=function(selArr,response)
		{
			
			
			data=this.FilterData(JSON.parse(this.CheckResponse(response)))
			
			
			//Search Bar visible or not.

			//Close hamburger if data not found
			if(!data)
			this.SendCloseHam();
			
			//set dependant data as well
			if(this.dependant && this.type!=this.dependant)
				var nouse=staticTables.getData(this.dependant,"","A");
			$(this.ulOption).unbind("click");
			$(this.ulOption).bind("click",function(ev)
			{
				stopPropagation(ev);
				var target=ev.target;
				
				if($(target).is("ul"))
					return;
				if($(target).hasClass("w400"))
					target=$(target).parent();
				else if($(target).is("li"))
				{
					if($(target).attr("value")==-1)
						return;
					target=$(target).children().first();
				}	
				else if($(target).is("div") && $(target)!=$(target).parent().children().first())
					target=$(target).parent().children().first();
				if($(target).is("i"))
					target=$(target).parent().parent().children().first();
				if($(target).parent().attr("value")!=-1 && $(target).attr("value")!=-1)
					ele.HamElementClick(event,target);
				//}
				});	
			var ele=this;
			ele.finalArr=Array();
			var resultArr=[];
			var i=0;
			var alreadySelectedValue=-1;
			$.each(data,function(st,json)
			{
				//var json=data[st];
				
				$.each(json,function(value1,label1)
				{
					$.each(label1,function(value,label)
					{
						resultArr[i]={"value":value,"label":label};
						i++;
						//setTimeout(function(){ele.AddElement(value,label,selArr)},5);
					});
				});
			});
			var chunkValue=50;
			if(ISBrowser("UC"))
				chunkValue=50;
			setTimeout(function(){ele.AddElement(0,resultArr,selArr,alreadySelectedValue,chunkValue)},1);
		}
		eHamburger.prototype.AddElement=function(index,resultArr,selArr,alreadySelectedValue,chunkValue)
		{
						
						//~ if(resultArr.length<=index)
						//~ {
								//~ this.AllElementLoaded();
								//~ return;
						//~ }
			var ele=this;				
			var isarr="";

			if(!(this.inputtype=="radio"))
				isarr="[]";
			var html=this.originalHtml;	
			var appendHtmlArr=[];
			var endArray=false;
			var actualIndex=index;
			for(var i=0;i<chunkValue;i++)
			{
				
				try{
					var value=resultArr[index]["value"];	
					var label=resultArr[index]["label"];
				}
				
				catch(e)
				{
					endArray=true;
					break;
					//console.log(e);
					 
				}
				index=index+1;
				
				
				var hamclass="checked";
				if(ele.whenHide=="multiple")
					hamclass="checked multiple";
				var hamclass_noselect="";
				if(value==-1 || value=="-1")
					hamclass_noselect="noselect";

				var temp=html;
				var ham_circle=$("#ham_circle").html();
				if(ele.whenHide!="multiple" || hamclass_noselect=="noselect")
					ham_circle="";
				temp=UpdateHtml(temp,{"HAM_CIRCLE":ham_circle});
				temp=temp.replace(/HAM_VALUE/g,value);
				temp=temp.replace(/HAM_LABEL/g,label);
				temp=temp.replace(/HAM_TYPE/g,ele.inputtype);
				temp=temp.replace(/HAM_NAME/g,ele.type+isarr);

				if(!ele.alreadyUpdated)
				{
					for(var j=0;j<selArr.length;j++)
					{
						
						if(value==selArr[j] && alreadySelectedValue!=value)
						{
							//console.log(temp);
							alreadySelectedValue=value;
							temp=temp.replace(/HAM_CLASS/gi,hamclass);
								//console.log(temp);
								//if(ele.whenHide=="multiple")
							ele.OutputUpdate(ele.type,label,value);
							break;
						}
					}
					temp=temp.replace(/HAM_CLASS/gi,hamclass_noselect);
					
					
				}
				appendHtmlArr[i]=temp;
			}	
			//if(data[i]["value"]==
			if(actualIndex==0)
					$(this.ulOption).html("");
			if(appendHtmlArr.length>0)
				$(this.ulOption).append(appendHtmlArr.join(""));
			//this.finalArr[this.finalArr.length]=temp;
			if(endArray)
				 this.AllElementLoaded();
			else	 
				setTimeout(function(){ele.AddElement(index,resultArr,selArr,alreadySelectedValue,chunkValue);},40);
			
		}
		eHamburger.prototype.AllElementLoaded=function()
		{
			
			$("#ham_load").remove();
                        $(this.ulOption).removeClass('dn');
			var ele=this;
			//$(this.ulOption).html("");
			
			//$(this.ulOption).html(finalarr.join(""));
			$(this.ulOption).scrollTop(0);
			
			
			//Scrolling to particular div
			$(this.ulOption).parent().removeClass('dn');
			var height=this.getDivHeight();
			
			$(this.ulOption).css({"height":height,"overflow":"auto"});
				
			
			
			
			if(this.whenHide!="multiple")
			{
				var selectedTab=$(this.ulOption).children("li.checked");
				
				
				if($(selectedTab).length)
				{
					
					var topY=Math.abs($(selectedTab).position().top)-$(selectedTab).outerHeight()-$(window).height()/2+80;
					
					if(Math.abs($(this.ulOption).scrollTop()-topY)>200)
						$(this.ulOption).animate({ scrollTop: topY }, 600);
				}
			}
			searchHamburger(this.type,this.ulOption,this.tapid);
			if($("#HAM_OPTION_1 li input:checked").length !=0) {
				var typeDataArray = [],type = "";
				if($($("#HAM_OPTION_1").find("input")[0]).attr("name").indexOf("p_") != -1) {
					type = $($("#HAM_OPTION_1").find("input")[0]).attr("name").split("p_")[1].split("[]")[0].toUpperCase();
				}
				if(type == "CITY" || type == "CASTE" || type == "MTONGUE" || type == "EDUCATION" || type == "OCCUPATION") {
					$("#HAM_OPTION_1 li input:checked").each(function(){
						typeDataArray.push($(this).val());
					});
					changeSuggestion(type,typeDataArray);
				} 
			}
		}
                eHamburger.prototype.AppendLoader=function()
                {
                    var hgt=$(window).height();
                    $(this.ulOption).addClass('dn');
                    $(this.ulOption).parent().prepend("<div id='ham_load' style='background:383d42;height:"+hgt+"px'><img src='IMG_URL/images/jsms/commonImg/loader.gif' style='position:relative;margin-left:40%;margin-top:40%'/></div>");
                    
                }
            this.eHamburger=eHamburger;
	}).call(this);
})();
var HamWindowFnc;
function BindHamWindow(ele)
{
	UnBindHamWindow();
	HamWindowFnc=function(){ele.ResizeVSlider()};	
			$(window).bind("resize",HamWindowFnc);
}
function UnBindHamWindow()
{
	if(HamWindowFnc)
		$(window).unbind("resize",HamWindowFnc);
}
function editHideHamburger(ele)
{
	if($(ele.persid).hasClass("showpers"))
	{
		ele.hideHamburger(1);
    ele.callBack.call(ele.calledElement,ele.output,ele.json,ele.indexPos);
		return true;
	}
	return false;
}
function getDependantValue(key)
{
	var data="";
	if(!storeJson["p_city"] && key=="p_city")
	{
		data=fetchEditDetails(key,"P_COUNTRY",changingEditData["Dpp"]["BasicDetails"]);
		storeJson[key]=data;
	}
	if(!storeJson["p_caste"] && key=="p_caste")
	{
		data=fetchEditDetails(key,"P_RELIGION",changingEditData["Dpp"]["Religion"]);
		storeJson[key]=data;
	}
	if(!storeJson["caste"] && key=="caste")
	{
		data=fetchEditDetails(key,"RELIGION",changingEditData["Details"]["Ethnicity"]);
		storeJson[key]=data;
	}
	if(!storeJson["income"] && key=="income")
	{
		data=fetchEditDetails(key,"COUNTRY_RES",changingEditData["Details"]["basic"]);
		var arr=data.split(",");
		if(parseInt(arr[0])!=51)
			arr[0]=128;
		storeJson[key]=parseInt(arr[0]);
	}
	if( key=="city_res_country")
	{
		data=fetchEditDetails(key,"COUNTRY_RES",changingEditData["Details"]["basic"]);
		storeJson[key]=data;
	}
	if( key=="city_res_state")
	{
		data=fetchEditDetails(key,"STATE_RES",changingEditData["Details"]["basic"]);
		storeJson[key]=data;
	}

	
	
	//console.log(storeJson);
	return 	storeJson[key];
}
function ExtendData(empty,json1,json2)
{
    var result={};
    var i=0;
    if(typeof(json2)!="undefined")
    $.each(json2,function(key,value)
    {
        result[i]=value;
        i++;
    });
    if(typeof(json1)!="undefined")
    $.each(json1,function(key,value)
    {
        result[i]=value;
        i++;
    });
    return result;
}
function changeSuggestion(type, param1) {
	var obj = {
                "type": type,
                "data": param1      
             },response, str = JSON.stringify(obj).split('"').join('%22'), url = "/api/v1/profile/dppSuggestions?Param=["+str+"]";
    //alert("url: "+url);
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        timeout: 5000,
        success: function(result) {
            if(result && result != "" && JSON.parse(result)[0] && JSON.parse(result).responseMessage == "Successful") {
                response = JSON.parse(JSON.parse(result)[0]);
                appendSuggestionList(response);
            } else {
                ShowTopDownError(["Something went wrong. Please try again after some time."]);
            }             
        },
        error:function(result){
            ShowTopDownError(["Something went wrong. Please try again after some time."]);
        }
     });
}
function appendSuggestionList(response) {
	var obj = response[0];
    if(obj.type == "AGE") {
      	$("#suggestBox").remove();
      	if(obj.data) {
			$("<div class='pad10p0p brdr13' id='suggestBox'><div class='suggestTitle color14 f14 fontlig'>Suggestions</div></div>").insertBefore($("#HAM_LABEL"));
			$("#suggestBox").append("<div id='suggest_"+obj.data.LAGE+"_"+obj.data.HAGE+"' class='pad5 f14 color14 brdr_new fontlig mar10p10p0p dispibl'>"+$($("#HAM_OPTION_1 li[value='"+obj.data.LAGE+"'] div")[0]).html()+"&nbsp;-&nbsp;"+$($("#HAM_OPTION_2 li[value='"+obj.data.HAGE+"'] div")[0]).html()+"</div>"); 			
	      	$("#suggest_"+obj.data.LAGE+"_"+obj.data.HAGE).off("click").on("click",function(){
	      		var lage = $(this).attr("id").split("suggest_")[1].split("_")[0],hage = $(this).attr("id").split("_")[2],valLage = $("#HAM_OPTION_1 li[value='"+lage+"']").attr("index"),valHage = $("#HAM_OPTION_2 li[value='"+hage+"']").attr("index"),nTop = $("#HAM_OPTION_1 li[fake=1]").length/2;
				slider1.gotoSlide(valLage,nTop);
				slider2.gotoSlide(valHage,nTop);
				$("#suggestBox").remove();
      		});	
      	}
		
    } else if(obj.type == "INCOME") {
    	var lVal = 0,hVal = 0;
    	$("#suggestBox").remove();
    	if(obj.data) {
			$("<div class='pad10p0p brdr13' id='suggestBox'><div class='suggestTitle color14 f14 fontlig'>Suggestions</div></div>").insertBefore($("#HAM_LABEL"));
			if ($("#TAPNAME_1").html() == "Income Rs") {
				
				$(".hpad5").each(function(){
					if($($(this).children()[0]).html() == obj.data.LRS) {
						lVal = $(this).attr("value");
					} else if($($(this).children()[0]).html() == obj.data.HRS) {
						hVal = $(this).attr("value");
					}
				});
				$("#suggestBox").append("<div id='suggest_"+lVal+"_"+hVal+"' class='pad5 f14 color14 brdr_new fontlig mar10p10p0p dispibl'>"+obj.data.LRS+"&nbsp;-&nbsp;"+obj.data.HRS+"</div>"); 			
			} else if ($("#TAPNAME_1").html() == "Income $") {
				$(".hpad5").each(function(){
					if($($(this).children()[0]).html() == obj.data.LDS) {
						lVal = $(this).attr("value");
					} else if($($(this).children()[0]).html() == obj.data.HDS) {
						hVal = $(this).attr("value");
					}
				});
				$("#suggestBox").append("<div id='suggest_"+lVal+"_"+hVal+"' class='pad5 f14 color14 brdr_new fontlig mar10p10p0p dispibl'>"+obj.data.LDS+"&nbsp;-&nbsp;"+obj.data.HDS+"</div>"); 			
			}
			$("#suggest_"+lVal+"_"+hVal).off("click").on("click",function(){
				var lValue = $(this).attr("id").split("suggest_")[1].split("_")[0],hValue = $(this).attr("id").split("_")[2],indexLVal = $("#HAM_OPTION_1 li[value='"+lValue+"']").attr("index"),indexHVal = $("#HAM_OPTION_2 li[value='"+hValue+"']").attr("index"),nTop = $("#HAM_OPTION_1 li[fake=1]").length/2;
				slider1.gotoSlide(indexLVal,nTop);
				slider2.gotoSlide(indexHVal,nTop);
				$("#suggestBox").remove();
			});
		}
    } else{
    	if($(".suggestOption").length == 0)  {
    		$("#suggestBox").remove();
    	}
		var dataPresent,clickVal;
		if(obj.data && Object.keys(obj.data).length != 0) {
			$.each(Object.keys(obj.data), function(index, elem) {
			 	dataPresent = false;
			 	if($("#HAM_OPTION_1 li[value='"+elem+"']")){
			 		if($("#HAM_OPTION_1 li[value='"+elem+"'] input").is(":checked")) {
			 			dataPresent = true;
			 		}
			 		$(".suggestOption").each(function(){
			 			if($(this).attr("value") == elem) {
			 				dataPresent = true;
			 			}
			 		});
			 		if(dataPresent == false) {
			 			if($("#suggestBox").length == 0)
			 				$("<div class='pad10p0p brdr13 suggestBox dispnone' id='suggestBox'><div class='suggestTitle color14 f14 fontlig'>Suggestions</div></div>").insertBefore($(".hpad5")[0]);
			 			if($("#HAM_OPTION_1 li[value='"+elem+"']").length != 0) {
				 			$("#suggestBox").append("<div style='width: 100px;overflow: hidden;height: 27px;text-overflow: ellipsis;position: relative;white-space: nowrap;' value='"+elem+"' class='suggestOption pad5 f14 color14 brdr_new fontlig mar10p10p0p dispibl'>"+$($("#HAM_OPTION_1 li[value='"+elem+"'] div")[0]).html()+"</div>");
				 			if($("#suggestBox").hasClass("dispnone")) {
				 				$("#suggestBox").removeClass("dispnone");
				 			}
				 			$(".suggestOption[value='"+elem+"']").off("click").on("click", function(){
				 				if($(this).hasClass("bg7")) {
				 					var count = 0;
				 					$(".suggestOption").each(function(){
				 						if($(this).hasClass("bg7") == false) {
				 							count++;
				 						}
				 					});
				 					if(count < 7) {
				 						$("#suggestBox").attr("suggest-click",1);
				 					}
				 				} else {
				 					$("#suggestBox").attr("suggest-click",1);
				 				}
				 				clickVal = $(this).attr("value");
				 				$(this).toggleClass("bg7");
				 				$(".hpad5[value='"+clickVal+"']").click();
				 			});
			 			}
			 		}
			 	}
			 });
		}
	}
}
