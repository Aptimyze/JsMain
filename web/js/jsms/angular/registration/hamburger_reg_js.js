var hamHtml="";
if(typeof animationtimer ==='undefined')
	animationtimer = 400;
animationtimer = animationtimer + 50;
var staticTables = new SessionStorage;
var errorMsg = "Something went wrong!! Please try again later";
(function() {
	var Hamburger=(function(){
		function Hamburger(element){
			
			this.optionHeight=100;
			this.json={};
			
			this.output={};
			this.ham_htm=this.UpdateHAMHtml($("#hamburger").html());
			this.ulOption="#HAM_OPTION_1";
			this.ulOption_real=this.ulOption;
			this.ulOption_second="#HAM_OPTION_2_1";
			this.tapid=1;
			this.hamid="#hamburger";
			this.hamoverid="#hamoverlay";
			this.persid="#perspective";
			this.pcontid="#pcontainer";
			this.saveButton="#SAVE_DONE";
			this.searchId = "#search_ham_1";
			this.calledElement=element;
			this.formation=$(element).attr("dmove")=="right"?"r":"l";
			this.whenHide=$(element).attr("dhide");
			this.inputtype=$(element).attr("dselect");
			
			this.callBack=eval($(element).attr("dcallback"));
			this.dependant=$(element).attr("dependant");
			this.indexPos=$(element).attr("dindexpos");
			this.duserDecision=$(element).attr("duserDecision");
			this.depValue = $(element).attr("depValue");
			this.tapName = $(element).attr("tapName");
			if($(element).attr("tapName")=="Create Profile For" && incompletePageAngular)
				this.tapName = "Posted By";
			this.dependant_tapName = $(element).attr("dependant_tapName");
			this.screenName=$(element).attr("screenName");
			this.selectedValue=-1;
			this.bIndependantCall = false;
			this.bHideStatus = true;
			this.clickInProgress = false;
            
            var ele=this;
			if(hamHtml)
				this.originalHtml=hamHtml;
			else
                this.originalHtml=hamHtml=$("#HAM_OPTION_1").html();            
			
			this.alreadyUpdated=false;

			if(this.whenHide=="multiple")
				this.optionHeight=220;
			$(element).bind("click",function(){	
			//~ $(element).swipe({longTapThreshold:30,longTap:function(){
				
           		ele.duserDecision=$(element).attr("duserDecision");
                ele.depValue = $(element).attr("depValue");
				ele.output={};
				ele.selectedValue=-1;
				ele.type=$(element).attr('dshow').toLowerCase();
				ele.defaultValue=$(element).attr('defaultValue');
				ele.ulOption="#HAM_OPTION_1";
				ele.ulOption_real=ele.ulOption;
				ele.ulOption_second="#HAM_OPTION_2_1";
				ele.tapid=1;
				stopTouchEvents(1);
				$(ele.hamid).removeClass("dn");
				searchHamburger(ele.type,ele.ulOption,ele.tapid);	
				(function(elem)
				{
					setTimeout(function(){
						elem.ShowHamburger();
					},5);
				})(ele);
				
			});
            $(window).resize(function(){ele.Resize()});
		};
		Hamburger.prototype.UpdateHAMHtml=function(html){
			html=html.replace(/HAM_OPTION_1/g,"HAM_OPTION_2_1");
			html=html.replace(/HAM_FORM_1/g,"HAM_FORM_2");
			html=html.replace(/HAM_1/g,"HAM_2");
			html=html.replace(/TAPNAME_1/g,"TAPNAME_2");
			
			return html;
		};
		Hamburger.prototype.ShowHamburger=function(){
			var ele = this;
      this.scrollTopPos = $(window).scrollTop();
			this.bHideStatus = false;
            this.showLoader(true);
			$(this.hamid).removeClass("dn");
			//setTimeout(function(){$(ele.hamid).addClass(ele.formation+"ham");},10)
			$(ele.hamid).addClass(ele.formation+"ham");
			$(this.persid).addClass("showpers");
			$(this.pcontid).addClass("hamb");
			//$(this.pcontid).
            if(this.showTwoDimView())
            {
               $(this.pcontid).addClass("twodview"+this.formation);
               setTimeout(function(){$("#2dView").removeClass("dn");

               },animationtimer);
            }   
           
            $(this.pcontid).addClass("ham"+this.formation);
           
			$(this.pcontid).prepend("<div class='wrapper' id='wrapper'></div>");
			
			$("#wrapper").swipe({tap:function(){
				ele.hideHamburger(1);return false;}});
			$("#wrapper,#hamburger,#hamoverlay,#arw_1,#arw_2").unbind("click");
			$("#wrapper,#hamburger,#hamoverlay,#arw_1,#arw_2").bind("click",function(ev){
				
				if($(ev.target).attr("type"))
				{
					return true;
				}
				if(ele.clickInProgress)
				{
					stopPropagation(ev);
					return true;
				}
				//If Clicked target is loaderImage then return
				if(($(ev.currentTarget).find("img[id='optionLoaderImg']").length==1))
				{
                    ele.clickInProgress = false;
                    return ;
                }
                else if(($(ev.currentTarget).find("img[id='hamRemove']").length==1))//IF clicked on Tab
				{
                    ele.clickInProgress = false;
                    return ;
                }
				ele.hideHamburger(1);return false;
			});
			setTimeout(			
                angular.element('#perspective').scope().$apply(function(){
                    angular.element('#perspective').scope().hamTrigger(true,ele);
                }),
            10);
            setTimeout(function(){
                try{
                    if(ele.isMultiOption())
                        ele.MultiHamburger();
                    else
                    {
                        ele.UpdateHamburgerHTML();
                    }		
                }
                catch(e)
                {
                    //console.log(e);
                    ele.hideHamburger(1);
                    ele.showErrorMsg(errorMsg)
                }
                finally{
                    //Bind touch events on HAM_OPTION div.
                    startTouchEvents(animationtimer);
                }	
            },animationtimer);
			
			
			
		};
        Hamburger.prototype.showTwoDimView=function()
        {
            return (typeof Modernizr != "undefined" && (!Modernizr.csstransforms3d || ISBrowser("UC") || ISBrowser("AndroidNative")));
        }
		Hamburger.prototype.hideHamburger=function()
		{
			var myScope = angular.element('#perspective').scope();
			if(myScope.fields.length>=3 && localStorage.getItem("UD"))
			{
				if(myScope.fields[3].storeKey == "casteNoBar" && JSON.parse(localStorage.getItem("UD")).casteNoBar == "true") 
				{
					myScope.fields[3].value=true;
				}
			}
			if(this.bHideStatus)
				return;
			var ele=this;	
			this.bHideStatus = true;
            
            $(window).resize(function(){});
            if(this.showTwoDimView())
            {
                $(this.pcontid).removeClass("twodview"+this.formation);
            }
			
            $(this.pcontid).removeClass("ham"+ele.formation);
            
			for(i=1;i<=4;i++)
			{
				$("#HAM_OPTION_"+i).swipe("destroy");
				$("#HAM_OPTION_"+i).unbind("click");
				$("#HAM_OPTION_"+i).unbind("touchstart");
			}
			ele.CloseMulti(0);	
			$("#HAM_LABEL").html("").addClass('dn');
			$(this.saveButton).addClass("dn");	
			if(this.showTwoDimView())
            {
                $(ele.ulOption_real).html("");
				$(ele.ulOption).html("");
            }
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
				$(ele.hamoverid).removeClass(ele.formation+"ham").removeClass("show");
				setTimeout(
                    angular.element('#perspective').scope().$apply(function(){
                        angular.element('#perspective').scope().hamTrigger(false,ele);
                    }),
                    10);
				
				$(ele.persid).removeClass("showpers");
				$(ele.pcontid).removeClass("hamb");
				$("#wrapper").remove();
				if(ele.showTwoDimView())
                {
                    $("#2dView").addClass("dn");
                }        
				startTouchEvents(1);
        pos = ele.scrollTopPos;
        window.scrollTo(0,pos);
				$("#TAPNAME_1").html("");
				},animationtimer+250);
			startScrolling();
		
};
		Hamburger.prototype.UpdateHamburgerHTML=function()
		{
			var ele=this;

			$("#TAPNAME_"+this.tapid).html(this.TapName());	
			//selected value
			var selArr=new Array();
			var duserdecision =this.duserDecision;
			if(duserdecision){
					var tempVal=duserdecision+"";
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
			if(ele.type=="native_state_jsms")
			{
				if($.isNumeric(selArr[0]))
					ele.type = "native_country_jsms";
			}

            setTimeout(function(){
                staticTables.getData(ele.type,function(data){ele.parseData(selArr,data)});
            },100);
		};
		
        Hamburger.prototype.parseData=function(selArr,data)
        {
            var data=this.FilterData(JSON.parse(data));
			//Close hamburger if data not found
			if(!data || data === -1)
			{
				this.SendCloseHam('forceClose');
                this.showErrorMsg(errorMsg);
				return;
			}
            //Bind Click Events
        
            $("#hamRemove").swipe({
				tap:function(){
					ele.hideHamburger(1);
					return false;
			}});
            
            $(this.ulOption).unbind("click");
            $(this.ulOption).bind("click",function(ev)
			{
				ele.clickInProgress = true;
				
				stopPropagation(ev);
                var target=ev.target;
                
				if(target === ev.currentTarget || ($(target).find("img[id='optionLoaderImg']").length==1))
				{
                    ele.clickInProgress = false;
                    return ;
                }
				
				//~ $(this.ulOption).swipe({tap:function(ev,target){
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
				{
					$(ele.ulOption).unbind("click");
					stopTouchEvents(1);
					ele.HamElementClick(target);
				}
				ele.clickInProgress = false;
			});
            
            //Add Elements
            var ele=this;
			ele.finalArr=Array();
			var resultArr=[],i=0;
            var alreadySelectedValue=-1;
			//set dependant data as well
			if(this.dependant && this.type!=this.dependant)
				var nouse=staticTables.getData(this.dependant,"","A");
			
            var ele =this;

            $.each(data,function(key1,data1)
            {
                $.each(data1,function(key2,data2)
                {
                    $.each(data2,function(value,label)
                    {
                        resultArr[i]={"value":value,"label":label};
						i++;
                    });
                });
            });
            //Add Elements in DOM
            setTimeout(function(){ele.AddElement(0,resultArr,selArr,alreadySelectedValue,50)},1)
        }
        
        Hamburger.prototype.AddElement=function(index,resultArr,selArr,alreadySelectedValue,chunkValue)
        {
            var ele =this;
            
            var isarr = "";
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
			
				if(ele.whenHide=="multiple")
					hamclass="checked multiple";
				
                var hamclass="checked";
                var hamclass_noselect="";
                if(value==-1 || value=="-1")
                    hamclass_noselect="noselectReg";
            
				var temp=html;
				var ham_circle=$("#ham_circle").html();
				if(ele.whenHide!="multiple" || hamclass_noselect=="noselect")
					ham_circle="";
                
                var temp=ele.originalHtml;
							
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
                            temp=temp.replace(/HAM_CLASS/gi,hamclass);
                            if(ele.whenHide=="multiple")
                                ele.output[label]=value;
                            break;
                        }
                    }
                    temp=temp.replace(/HAM_CLASS/gi,hamclass_noselect);					
                }
				appendHtmlArr[i]=temp;
			}	
            
            if(actualIndex==0)
					$(this.ulOption).html("");
			if(appendHtmlArr.length>0)
				$(this.ulOption).append(appendHtmlArr.join(""));
			
			//this.finalArr[this.finalArr.length]=temp;
			if(endArray)
			{
                this.showLoader(false);
                this.Resize();
                searchHamburger(this.type,this.ulOption,this.tapid);
            }
			else	 
            {
                if(this.clickInProgress == false)
                {
                    if(ISBrowser('safari'))
                    {
                          ele.AddElement(index,resultArr,selArr,alreadySelectedValue,chunkValue);
                    }
                    else
                    {
                        setTimeout(function(){
                            ele.AddElement(index,resultArr,selArr,alreadySelectedValue,chunkValue);
                        },40);
                    }
                }
                    
            }
          
        }
		Hamburger.prototype.HamElementClick=function(ele)
		{
			
			var value=this.UpdateOutput(ele);
			this.SpecialDependantLogic();
				if(this.type=="native_country_jsms" && $.isNumeric(value))
				{
					this.dependant = "native_country_jsms";
				}
                                if(this.type=="native_state_jsms" && value=="NI")
                                {
					this.type= "native_country_jsms";
					this.dependant = "native_country_jsms";
					this.bIndependantCall=true;
					this.UpdateHamburgerHTML();
					startTouchEvents();
					return;
                                }

                                if(this.type == "native_country_jsms" && value== "FI")
                                {
					this.type= "native_state_jsms";
					this.dependant = "native_city_jsms";
					this.bIndependantCall=true;
					this.UpdateHamburgerHTML();
					startTouchEvents();
					return;
                                }
                                if(this.type=="native_state_jsms" && (value=="undefined" ||value==undefined||value=="0"))
                                {
                                        this.dependant='';
                                }
			if(this.whenHide=="multiple")
			{
				
			}
			else if(this.dependant && this.type!=this.dependant)
			{
				var ele  = this;
				stopTouchEvents(1);
                ele.type=ele.dependant;
                ele.tapid=2;
                var html = ele.ham_htm.replace(/search_ham_1/g,"search_ham_"+ele.tapid);
                ele.searchId = "#search_ham_"+ele.tapid;
                $(ele.hamoverid).html(html);
                ele.ulOption=ele.ulOption_second;

                $("#hamoverlay").addClass("rham").addClass("show");
                ele.showLoader(true);
                setTimeout(function(){
                    ele.UpdateHamburgerHTML();
                    startTouchEvents(animationtimer);
                },animationtimer);
			}
			else
			{
				stopTouchEvents(1);
				this.hideHamburger();
                if(Object.keys(this.output).length != 0)
                {
                    var myScope = angular.element('#perspective').scope();
                    var ele = this;
                    setTimeout(
                        myScope.$apply(function(){
                            myScope.myFormSubmit(ele.calledElement,ele.output,ele.json,ele.indexPos);
                        }),
                    10);
                }    
			}
		};
		Hamburger.prototype.SpecialDependantLogic=function()
		{
			var specialType = ['religion','reg_mstatus','t_brother','t_sister',"native_state_jsms"];
			if(specialType.indexOf(this.type)!=-1)
			{
				var o = this.output[this.type];
				this.dependant = '';
				if(this.type == 'religion')
				{	
					this.dependant = '';
					if(o.value == 1 || o.value == 4 || o.value ==9 || o.value == 2 || o.value == 3)
					{
						this.dependant = 'reg_caste_' + this.selectedValue +"_";
						if(o.value == 1)
							this.dependant = this.dependant + this.depValue;
						this.bIndependantCall = true;
						if(this.selectedValue=='2' || this.selectedValue=='3')
							this.dependant_tapName ='Sect';
						else
							this.dependant_tapName ='Caste';
					}
				}
				if(this.type=="native_state_jsms"&& this.selectedValue!="NI")
				{
					this.dependant = '';
					this.dependant = "reg_city_" + this.selectedValue +"_";
				}
				if(this.type == 'reg_mstatus'  && o.value && o.value!='N') 
				{
					this.dependant = 'children';
					this.bIndependantCall = true;
				}
				
                if((this.type == 't_brother' || this.type == 't_sister') && o ){
                    if (o.value==='0')
                        this.dependant = '';
                    else
                        this.dependant = 'm'+this.type.substr(1);
                }
			}
		};
		
		Hamburger.prototype.UpdateOutput=function(target,add,remove)
		{	
			var label=$(target).html();
			var value=$(target).next().val();
			
			this.selectedValue=value;
            $(target).next().prop("checked","checked");
            $(target).next().attr("checked","checked");
            $(target).parent().addClass("checked");
						
			var outputKey = "";

			if(this.ulOption == "#HAM_OPTION_1")
			{
				outputKey = this.type;
			}
			else if(this.ulOption == "#HAM_OPTION_2_1" )
			{
				outputKey = this.dependant;
				if(this.dependant.indexOf("reg_caste")!=-1)
					outputKey = "reg_caste_";
			}
			
			if(this.type == "mtongue")
			{
				staticTables.getData('reg_caste_1_'+value,"","A");
			}	
            
			this.output[outputKey.toString()] = {'label':label,'value':value};
			
			return value;
		};
		Hamburger.prototype.FilterData=function(json)
		{
                        if(this.type=="family_income")
                        {
                            this.depValue = staticTables.getUserData('familyIncomeDep')
                        }
			if(this.type=="income" || this.type=="family_income")
			{
				this.selectedValue = 51;
				if(this.depValue.length && this.depValue != '51')
					this.selectedValue = 128;
			}
			if(this.type == 'reg_mstatus' && this.depValue.length == 0)
			{
				this.depValue = "F";
			}
			if(this.type == 'reg_mstatus' && json)
			{
				this.selectedValue = this.depValue;
			}
			if(this.type=="native_country_jsms" && json)
				return json;
			if(this.type.indexOf("reg_city_")>-1 && this.screenName!="s2")
			{
				var split = this.type.split("_");
				var state = split[2];
				return json[state];
			}
			if(this.type.indexOf("reg_city_jspc")>-1 && this.screenName=="s2")
			{
                            this.countryValue = staticTables.getUserData('familyIncomeDep');
				if(this.countryValue=='51')
				{
					this.stateValue = staticTables.getUserData('stateDep').replace(/\"/g, "");
					return json[this.countryValue][this.stateValue];
				}
				else
				{
					return json[this.countryValue];
				}
			}
			if(this.selectedValue!=-1 && json)
			{
				if(json[this.selectedValue] && !this.bIndependantCall)
					return json[this.selectedValue];
				else if(this.bIndependantCall)
				{
					this.bIndependantCall = false;
					return json;
				}
			}
			else
				return json;
		};
		Hamburger.prototype.TapName=function()
		{
			if(this.dependant && this.tapid==2)
				return this.dependant_tapName;
			else if(this.tapName)
				return this.tapName;
			else
				return;	
		};

		Hamburger.prototype.SendCloseHam=function(forceClose){
            
			this.hideHamburger();
            //Do not return data part if forceClose is specififed
            if(typeof forceClose == 'string' && forceClose.indexOf('forceClose')!=-1)
                   return;
            if(Object.keys(this.output).length != 0)
			{
                var myScope = angular.element('#perspective').scope();
                var ele = this;
                setTimeout(
                    myScope.$apply(function(){
                        myScope.myFormSubmit(ele.calledElement,ele.output,ele.json,ele.indexPos);
                    }),
                10);
            }
			
		};
        
		Hamburger.prototype.EnableSaveButton=function(){
			var ele=this;
			$(this.saveButton).removeClass("dn");
            $(this.saveButton).unbind();
                $(this.saveButton).bind("click",function(ev){
                    stopPropagation(ev);
                ele.SendCloseHam();
            });
		};
		Hamburger.prototype.getDivHeight=function(){
			var threshold=60;
			var height=$(window).height()-threshold;
			var ham_top=height-$("#HAM_TOP").height();
			if($($("[HAM_SEARCH]")[0]).css("display")!='none')
				ham_top-=$($("[HAM_SEARCH]")[0]).height();
			if($($("[HAM_SEARCH]")[1]).css("display")!='none')
				ham_top-=$($("[HAM_SEARCH]")[1]).height();	
				
			if($(this.saveButton).css("display")!='none')	
				ham_top-=$(this.saveButton).height();
			return ham_top;
				
			
		};
		Hamburger.prototype.isMultiOption=function()
		{
			if(typeof this.originalHtml == "undefined")
                return false;
            
			var html=this.originalHtml;
            
			if($.inArray(this.type,["time_to_call_start","dtofbirth"])==-1)
			{
				$(this.ulOption).parent().parent().css("width","100%");
				html=html.replace(/\{\{txtc\}\}/,"");
								
				this.originalHtml=html;
				return false;
			}
			html=html.replace(/\{\{txtc\}\}/,"txtc");
				
			return true;
		};
		Hamburger.prototype.MultiHamburger=function()
		{
			var ele=this;
			var txtc="txtc";
			var type=this.type;
			
			$("#TAPNAME_"+this.tapid).html(this.TapName());	
			var finalarr=new Array();
						
			var html=$("#HAM_OPTION_4").html();
			var cnt=1;
			
			this.updateHamLabel();
			cnt=3;
			var days = {};
			var month = {};
			var monthLabel = ['Jan','Feb','Mar','April','May','June','July','Aug','Sept','Oct','Nov','Dec'];
			var year = {};
			var y_diff = 18;
			var yearDelta = 70;
			var currYear = (Date()).split(" ")[3];
			
			if(this.depValue==="M")
			{
				y_diff = 21;
			}
			
			//days
			for (var i = 1; i <= 31; i++) {
			   days[i] = i;
			}
			//month			
			for (var i = 1; i <= 12; i++) {
			   month[i] = monthLabel[i-1];
			}
			//year
			for (var i = currYear-yearDelta; i <= currYear-y_diff; i++) {
			   year[i] = i;
			}
			
			var height=this.getDivHeight();
			//var height=370;
			for(var ham_i=1;ham_i<=cnt;ham_i++)
			{
				var tempData = [days,month,year];
			
				var data= tempData[ham_i - 1];
				(function(i)
				{
					var id="#HAM_OPTION_"+i;
					ele.updateHamOption(id,data,txtc,i-1);
						$(id).parent().css("width",100/cnt+"%").css("float","left").css("height",height).css("overflow","auto").css("position","relative");
							var indh=$(id).children().first().outerHeight();
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
					for(var i=0;i<up;i++)
						$(id).prepend("<li class='hpad5' fake=1><div class='fl f17 color17 txtc fontlig' style='color:#2c3137'>.</div><div class='clr'></div></li>");
					for(var i=0;i<down;i++)
						$(id).append("<LI class='hpad5' fake=1><div class='fl f17 color17 txtc fontlig' style='color:#2c3137'>.</div><div class='clr'></div></li>");	
					//$("#HAM_OPTION_1").parent().scrollTop(indh*up);	
					var topPos=$($(id).children()[up]).position().top;
					this.topPos=topPos;
					this.topPos=topPos=indh*up;
					var di="<div style='position:absolute;background:#d9475c;top:"+topPos+"px;height:"+indh+"px;width:100%;opacity:.4;padding:10px;border-right-style: solid;border-right-width: 2px;border-color: #2c3137;'></div>";
					$(id).parent().prepend(di);
					
					//~ var vsliderObj = id + '_VsliderObj';
					//~ ele[vsliderObj] =
					$(id).VSlider({"width":"100%","height":hgt,"sliderHeight":indh,"fakeb":down,"faket":up});
				})(ham_i);
			}
			BindHamWindow(this);
            this.showLoader(false);
            this.Resize();
		};
		Hamburger.prototype.updateHamOption=function(id,data,center,which)
		{
			var html=$("#HAM_OPTION_4").html();
			var ele=this;
			var finalarr=new Array();
			html=this.UpdateHtml(html,{"txtc":center});
			//selected value
			var selectedVal;
			if(!this.duserDecision)
			{
				var currYear = (Date()).split(" ")[3];
				this.duserDecision = "15,6,1985";//+(currYear-(29));
			}
			
			var curJson={"value":this.duserDecision};
			var keyArr=this.getMultiKeys(this.type);
			
			var i=0;
			if(curJson.value)
			{
				var tempVal=curJson.value+"";
				var tempArr=tempVal.split(",");
				selectedVal=tempArr[which];
			}
			else
			{
				if(ele.type=="time_to_call_start")
					if(which)
						selectedVal="6:00 PM";
					else
					{
						keyName="time_to_call_end";
							selectedVal="9:00 AM";
					}		
			}
		
			$.each(data,function(value,label)
			{
				if(ele.type=="time_to_call_start")
					value=label;
				var hamclass="checked";
				var hamclass_noselect="";
				var isarr="";
				if(value==-1 || value=="-1")
					hamclass_noselect="noselectReg";
				
				var temp=html;
				var type=keyArr[which];
				
				
				temp=temp.replace(/HAM_VALUE/g,value);
				temp=temp.replace(/HAM_LABEL/g,label);
				temp=temp.replace(/HAM_TYPE/g,ele.inputtype);
				temp=temp.replace(/HAM_NAME/g,type+isarr);
				
				
				//console.log(value+" "+selectedVal);
						if((value+"").replace(":00","")==selectedVal || value==selectedVal)
						{
								temp=temp.replace(/HAM_CLASS/gi,hamclass);
								keyName=keyArr[which];
								//console.log("Key"+keyName);
								 ele.OutputUpdate(keyName,label,value);
						}
				temp=temp.replace(/HAM_CLASS/gi,hamclass_noselect);
				
				finalarr[i]=temp;
				i++;
			});
			
			$(id).html("");
			$(id).html(finalarr.join(""));
			
			$(id).parent().removeClass("dn");
			
			
		};
		Hamburger.prototype.updateHamLabel=function()
		{
			if(this.isMultiOption())
			{
				var tempStr1,tempStr2;
				tempStr1=tempStr2=$("#HAM_MULTI").html();
				var json={"FROM_TO":"from","WIDTH":"wid49p fl"};
				
				tempStr1=this.UpdateHtml(tempStr1,json);
				json={"FROM_TO":"to","WIDTH":"wid49p fl"};
				tempStr2=this.UpdateHtml(tempStr2,json);
				$("#HAM_LABEL").append(tempStr1).append(tempStr2).removeClass("dn");
				this.EnableSaveButton()
				
			}
		};
		Hamburger.prototype.getMultiKeys=function(type)
		{
			var keyName=new Array();
			if(type=="dtofbirth")
			{
				keyName[0]="day";
				keyName[1]="month";
				keyName[2]="year";
			}
			
			return keyName;
		}
		Hamburger.prototype.CloseMulti=function(fromResize)
		{	
			if(this.isMultiOption())
			{
                var mainContentElemenet = $('#HAM_LABEL').parent();
				mainContentElemenet.css('height','');
                
				var keyName=this.getMultiKeys(this.type);
				var cnt=keyName.length;
				for(var i=1;i<=cnt;i++)
				{
					var id="#HAM_OPTION_"+i;
					var rid=$(id).children().children('[type="radio"]:checked');
					var label=$(rid).prev().html();
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
		}
		Hamburger.prototype.OutputUpdate=function(key,label,value)
		{
			if(!key)
			{
				//console.log("key blank coming");
				return;
			}
			key=key.toLowerCase();
			
			if(!this.output[key] || this.whenHide!='multiple')
				this.output[key]={};
			
			var outputKey = this.type;
			this.output[outputKey.toString()+'_'+key.toString()]= {'label':label,'value':value};
		};
		Hamburger.prototype.UpdateHtml =function(str,json)
		{
			
			$.each(json, function(key,val){
				var re = new RegExp("\{\{"+key+"\}\}", "g");
					str=str.replace(re,val);
				});
				str=str.replace(/\{\{\w+\}\}/g,"");
				return str;
		}
		Hamburger.prototype.Resize =function()
		{
            if(this.bHideStatus)
                return;
			if(this.type === 'dtofbirth')
			{
				$(this.saveButton).addClass('posabs').addClass('btm0');
				var height = window.innerHeight - $(this.saveButton).height() - $('#overlayTap').height();
				//Reinitalize Main Content Height
				var mainContentElemenet = $('#HAM_LABEL').parent();
				mainContentElemenet.css('height',height);
				//Reinitalize Height of DOB Content
				for(var i=1;i<=3;i++)   
				{
					var id = '#HAM_OPTION_'+ i;
					var element = $(id).parent().parent();
					element.css('height',height);
				}
				return;
			}
			
            var searchHeight = 0;
            if($(this.searchId).parent().parent().hasClass('dn')==false)
                searchHeight  = $(this.searchId).parent().parent().height();
            
            this.optionHeight = window.innerHeight - (59 + searchHeight);
			$(this.ulOption).css({"height":(this.optionHeight)+'px',"overflow":"auto"});    
			
            if($(this.ulOption).children().length && typeof this.type=== 'string')
			{			
				// If any option is hidden then append some dummy option after that
				var optionHeight = $(this.ulOption).children().height()*$(this.ulOption).children().length;
				optionHeight = $('#overlayTap').height() + optionHeight;
                
                arrDummy =$(this.ulOption).find('input[id="ham_dummy"]');
				if(optionHeight > $(window).innerHeight() && !arrDummy.length)
				{
					var temp = this.originalHtml;
					temp=temp.replace(/HAM_VALUE/g,-1);
					temp=temp.replace(/HAM_LABEL/g,"   ");
					temp=temp.replace(/HAM_TYPE/g,"text");
					temp=temp.replace(/HAM_NAME/g,"dummy");
					temp=temp.replace(/HAM_CLASS/g,"");
					$(this.ulOption).append(temp);
					$(this.ulOption).append(temp);
//					$(this.ulOption).append(temp);
//					$(this.ulOption).append(temp);
//					if(this.type.indexOf('country_res')!=-1 || this.type.indexOf('city_res') || this.type.indexOf('occupation'))
//					{
//						$(this.ulOption).append(temp);
//						$(this.ulOption).append(temp);
//					}
				}
				//Select Tab in middle of view port if any value is selected else to default Value
				var selectedTab=$(this.ulOption).children("li.checked");
				if($(selectedTab).length)
				{
					var posTop = $(selectedTab).position().top;
					var viewPortHeight = $(window).innerHeight();
					if( posTop > viewPortHeight)
					{
						$(this.ulOption).scrollTop(Math.abs(posTop-(viewPortHeight/2)+100));
					}
				}
        else if(this.defaultValue){//scroll to default option if exist
            selectedTab=$(this.ulOption).children("li[value='" + this.defaultValue + "']"); // Set default scroll to 5 ft
            if(selectedTab.length){
               var ele = this;
                setTimeout(function(){
                $(ele.ulOption).scrollTo(selectedTab);   
              },10); 
          }
        }        
        $(this.ulOption).parent().removeClass('dn');
			}
		}
		Hamburger.prototype.ResizeVSlider=function(ev)
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
        Hamburger.prototype.showErrorMsg=function(msg)
        {
            if(msg && msg.length)
            {
                setTimeout(
                    angular.element('#perspective').scope().$apply(function(){
                      angular.element('#perspective').scope().showHamMsg(msg);
                    }),
                10);  
            }
        }
        Hamburger.prototype.showLoader=function(status)
        {
            if(status)
            {
                $(this.ulOption).html("");
                tempHeight = $(window).height() - $('#overlayTap').height() - $(".hamsearch").height();
                var padTopHeight = tempHeight/2.5;
                $(this.ulOption).parent().prepend("<div style='padding-top:" +padTopHeight+"px;text-align:center;height:"+(parseInt(tempHeight)+600) +"px'><img id= 'optionLoaderImg' src='/images/jsms/commonImg/loader.gif' /></div>");
            }
            else
            {
                $('#optionLoaderImg').parent().remove();
            }
            
        }
		this.Hamburger=Hamburger;
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
