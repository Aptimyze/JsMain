/**
 * This file is used to have search dropdown functionality 
 */

/**
 * This function binds all the dropdowns of qsb
 */
 
(jQuery), $(document).on("click focus keypress",function(a) {
	
	 if(previousOpen != null)
		previousOpen.inpTextElm.attr("placeholder",previousOpen.inpTextElm.attr("placeholderpre"));
	$(a.target).parents(".singleDD").length || $(".singleDD .sDrop").slideUp(200);
	  
});
var manageKeyScroll = {
  scrollHandler: function(a, b, c, d) {
			if (d.length) {
      var e, f = b.height(),
        g = b.scrollTop(),
        h = f + g,
        i = d.position().top + b.scrollTop(),
        j = i + d.outerHeight();
       
      return j >= h ? (e = j - f > 0 ? j - f : 0, b.scrollTop(e)) : i < b.scrollTop() && (e = i, b.scrollTop(e)), e;
    }
  }
};

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
var previousOpen = null;
(function() {
	var DropDown=(function(){
		function DropDown(element,attr){
			this.calledElement=element;
			this.id = $(element).attr("id");
			
				/**
			 * This is used to assign variables to dropdown 
			 */
			
	     this.barSize= "0";
	     this.anchorScrollSpeed= 10;
	     this.pageScrollSpeed = 20;
	     this.clientX = 0;
	     this.clientY =0;
	     this.verticalConfig= {
	          cSize: "clientHeight",
	          func: "height",
	          css1: "top",
	          css2: "height",
	          sSize: "scrollHeight",
	          sStart: "scrollTop"
	        };
	      this.horizontalConfig= {
	          cSize: "clientWidth",
	          func: "width",
	          css1: "left",
	          css2: "width",
	          sSize: "scrollWidth",
	          sStart: "scrollLeft"
	        };
	      
			var ele = this;
		
			ele.customScrollInit();
		
		
		};
		
    /**
     * This function is use to initialize custon scroll on the dropdown
     */
		DropDown.prototype.customScrollInit=function(){
			var ele = this.calledElement;
			
			$(ele).css({
        overflow: "visible"
      });
      /**
       * This is for creating custom scroll
       */
     
			this.createCustomScroll();
      this.attachEvents();
      this.attachCsb(ele,this);
      $(ele).removeClass("nScroll").addClass("nScrollable");
      var b = this;
	   
			$(document).on("mouseup", function() {
				b.drag = null;
	      b.isDragging = false;
	    });
	     
			$(document).on("mousemove", function(a) {
				
	      if (b.isDragging==true){
				if(	b.drag){
					
						b.clientX = a.clientX ;
						b.clientY = a.clientY;
	        var c = b.drag.prev_clientX?(b.clientX - b.drag.prev_clientX):0,
	          d =  b.drag.prev_clientY?(b.clientY - b.drag.prev_clientY):0,
	          e = b.drag.obj,
	          f = b.drag.dir,
	          g = ("vertical" == f ? d : c) / e[f].RATIO;
	        e.scrollToward(b.drag.dir, g);
	        b.drag.prev_clientX = b.clientX;
	        b.drag.prev_clientY = b.clientY;
	      }
			}
	    });
			
		};
		/**
		 * function to get property from array 
		 */
		DropDown.prototype.getProperty =function(a, c) {
			if(a=="vertical" || a=="horizontal")
				a = a + "Config";
          return c ? this[a][c] : this[a];
    };
    
    /**
     * This function is used to create custom scroll structure
     */ 
		DropDown.prototype.createCustomScroll=function(){
			var ele = this.calledElement;
			function getStructure(a) {
						this[a] = {
              scroll: $("<div>").addClass("csb matchParent " + a), //  scroll div definition
              head: $("<div>").addClass("matchParent anchor head"),//  scroll head div definition
              foot: $("<div>").addClass("matchParent anchor foot"),//  scroll foot div definition
              bar: $("<div>").addClass("bar") // adding bar
            };
            this[a].scroll.append(this[a].head).append(this[a].bar).append(this[a].foot); //appending all the required div
            this.cover.append(this[a].scroll);		// appending cover with scroll divs
            this.anchorSize = 2; // LATER this.anchorsize 
            this[a].bar[0].style[this.vertical.css1] = this.anchorSize + "px";
          }
					/**
					 * Following codes is used to create document nodes for the lis
					 */
          for (var b = document.createDocumentFragment(), c = ele.childNodes; c.length;) b.appendChild(c[0]);
          // Adding cover div to the content
          this.cover = $("<div>").addClass("cover"), this.content = $("<div>").addClass("matchParent sDDcontent"), this.content.append($(b));
          var d = {
            minWidth: $(ele).css("min-width"),
            maxWidth: $(ele).css("max-width"),
            minHeight: $(ele).css("min-height"),
            maxHeight: $(ele).css("max-height")
          };
          // Adding cover attributes to the content and calling above getStructure function
          this.content.css(d);
          this.cover[0].setAttribute("onscroll", "this.scrollLeft=this.scrollTop=0");
          this.cover.append(this.content);
          $(ele).append(this.cover);
          getStructure.call(this, "vertical");
          getStructure.call(this, "horizontal");
    };
    
    DropDown.prototype.attachEvents = function() {
          var a = this;
          this.mouseEventFunctionse("vertical");
         
					this.content.on("scroll" , function() {
						a.onScrollChange();
					});
          this.cover.mouseenter(function() {
            a.show();
          });
          this.cover.mouseleave(function() {
						a.drag ;
          });
      };
      
       
     
     DropDown.prototype.attachCsb = function(a, b) {
			/**
			 *  This function is used to attach csb (b) with sindleDD (a)
			 */
			b.calledElement=a;
			b.id = $(a).attr("id");
        a.csb = {
          reset: function() {
						
						b.onScrollChange();
          },
          scrollToHead: function() {},
          scrollToHead: function() {},
          scrollTo: function() {},
          remove: function() {}
        };
      };
       
   
			    DropDown.prototype.mouseEventFunctionsa = function(a, c, e, f) {
						
						var b = e;
            var g = e.scrollToward,
              h = 0,
              i = e[a].bar[0];
             
            if (c.hasClass("anchor")) {
              h = b.anchorScrollSpeed;
              var j = c.hasClass("head") ? -h : h
            } else {
              var k = e[a].bar[0].getBoundingClientRect(),
                l = ("vertical" == a ? f.clientY < k.top : f.clientX < k.left) ? "head" : "foot";
              h = b.pageScrollSpeed;
              var j = "head" == l ? -h : h
            }
						return void e.scrollToward(a, j);
					 
          };
           DropDown.prototype.mouseEventFunctionsc = function(a, c) {
						 var b = c;
					
						 b.drag = {
              dir: a,
              obj: c,
              prev_clientX: b.clientX,
              prev_clientY: b.clientY
            }
        
          };
         DropDown.prototype.mouseEventFunctionse = function(b) {
            var d = this;
            this[b].scroll.mousedown(function(b, e) {
							d.isDragging = true;
              var f = $(b.target);
              e = f.hasClass("bar") || f.hasClass("anchor") ? f.parent().attr("class").match(/vertical/) : f.attr("class").match(/vertical/);
              f.hasClass("bar") ? d.mouseEventFunctionsc(e, d) : d.mouseEventFunctionsa(e, f, d, b);
              b.preventDefault();
            }).click(function(a) {
							a.stopPropagation();
            })
          };
        
		
		
		DropDown.prototype.scrollTo = function(a, b) {
			var c = this.getProperty(a, "sStart");
			this.content[0][c] = b;
		};
		DropDown.prototype.scrollToward= function(a, b) {
			var c = this.getProperty(a, "sStart");
				this.content[0][c];
			
			this.content[0][c] += (b > 0 ? Math.ceil : Math.floor)(b);
			
		};
		DropDown.prototype.getPaneSize= function(a) {
			/**
			 * This function is used to get properties of the bar as per the variable and vertical obj
			 */
			var b = this.getProperty(a, "func"),
				c = this[a].head[b](),
				d = this[a].scroll[b]() - 2 * c;
				
			return {
				aSize: c,
				pSize: d > -1 ? d : 0
			}
		};
     DropDown.prototype.setRatio= function(a) {
			var b = (this.getProperty(a, "css1"), this.getProperty(a, "css2")),
			c = this.getProperty(a, "sSize"),
			d = this.getProperty(a, "sStart");
			e = this.getPaneSize(a),
			f = Math.max(this.barSize, (Math.pow(e.pSize, 2) / this.content[0][c])*20);
			f = Math.min(f,50);
			this[a].bar[0].style[b] = f + "px";
			var g = this.content[0][d];
			this.content[0][d] = this.content[0][c];
			this[a].maxsSize = this.content[0][d];
			this.content[0][d] = g;
			
			this[a].RATIO =  0 != this[a].maxsSize ? (e.pSize - f) / this[a].maxsSize : 0;
			
		};
		DropDown.prototype.isScrollable=function(a) {
			var b = this.getProperty(a, "sSize");
			b = this.content[0][b];
			var c = this.getProperty(a, "cSize");
			return c = this.content[0][c], b > c;
		};
		DropDown.prototype.show = function(a) {
			this.isScrollable("vertical") ? (this["vertical"].scroll.stop(!0, !0), this["vertical"].scroll.fadeIn("slow")) : this.hide("vertical");
		};
		DropDown.prototype.hide =  function(a) {
			this["vertical"].scroll.stop(!0, !0);
			this["vertical"].scroll.fadeOut("slow");
		};
		DropDown.prototype.onScrollSizeUpdate = function(a) {
			this.setRatio(a);
			this.show();
		};
		DropDown.prototype.setBarStart = function(a, b) {
			{
				
				var c = this.getProperty(a, "css1");
				this.getProperty(a, "func");
			}
			b = b * this[a].RATIO + this.anchorSize;
			this[a].bar[0].style[c] = b + "px";
		};
		DropDown.prototype.onScrollChange = function() {
			
			if (!this.synching) {
				
				
				this.synching = !0;
						var b = "vertical",
						c = this[b]._sSize || 0,
						d = this[b]._sStart || 0,
						e = this.content[0][this.getProperty(b, "sSize")];
					
					c != e && (this.onScrollSizeUpdate(b), this[b]._sSize = e);
					var f = this.content[0][this.getProperty(b, "sStart")];
					
					d != f && (this.setBarStart(b, f), this[b]._sStart = f);
				
				this.synching = !1;
			}
		};
		
		this.DropDown=DropDown;
		
	}).call(this);
})();







(function() {
	var SingleDD=(function(){
		function SingleDD(element){
			this.calledElement=element;
			this.id = $(element).attr("id");
			this.maxHeight= 265;
      this.data= {};
      this.defaultIndex= !0;
      this.customScroll= !0;
      this.placeholderColor = "#a9a9a9";
      this.selectColor = "#333";
      this.animationSpeed = 200;
      this.autoSelect = !1;
      this.callBack = $(element).attr("hasDependant");
      var ele = this;
			this.init($(this.calledElement));
			
		};
		
		 SingleDD.prototype.init = function(elem) {
      var ele = this;
			ele._this = ele;
      ele.elm = elem;
			var inpId = elem.attr("id"),
        inpElem = ele.elm.find(".sdTxt"),
        inpName = inpElem.attr("name"),
        inpVal = elem.attr("data"),
        inpPlace = inpElem.attr("placeholder");
      ele.inpTextElm = inpElem.attr({
        tabIndex: "-1"
      }); 
			
			
      ele.inpWrap = ele.elm.find(".dWrap").attr({
        tabIndex: "0"
      });
      ele.valjson={};
       var i = this.customScroll ? 'class="nScroll"' : "";
      ele.hidElm = jQuery("<input>").attr({
        type: "hidden",
        id: inpId+ "Hid",
        name: inpName,
        value: inpVal,
        autocomplete:'off'
      });
      if(inpId.indexOf("age")>-1)
				ele.dropCont = $('<div class="sDrop pos-abs z5 sreposa srebg1 fullwid" style="display: none;"></div>');
      else
				ele.dropCont = $('<div class="sDrop pos-abs z5 srepos2 srebg1 fullwid" style="display: none;"></div>');
      ele.innerDropLayer = $("<div " + i + "><ul></ul></div>").css({
        maxHeight: this.maxHeight
      });
      ele.dropCont.append(ele.innerDropLayer);
      id = this.id; 
      ulList = $("#"+id).find("ul").html();
      ele.totalCount = $("#"+id).find("ul").children().length;
      if(ele.totalCount==0)
      {
					$("#"+id).find(".sdTxt").attr("disabled",true);
					$("#"+id).find(".smArw").addClass("disp-none");
				
			}
			if(typeof inpPlace==="undefined"){
					$("#"+id).find(".sdTxt").attr("placeholder",$("#"+id).find("li").first().attr("data")+" yrs");
				
			}
       $("#"+id).find("ul").remove();
       
       $("#"+id).find("ul").addClass("disp-none");
      ele.dropCont.find("ul").html(ulList);
      ele.currentActive = !1; 
      ele.inpWrap.append(ele.hidElm); 
      ele.elm.append(ele.dropCont);
      
      ele.elm.on("mouseenter", function() {
        ele.currentActive = !0
      });
      
      ele.elm.on("mouseleave", function() {
        ele.currentActive = !1
      }); 
      this.slideFunctions(ele); 
      
      ele.dropCont.on("click", "li", function() {
				var b = $(this).text(),
          c = $(this).attr("id");
        this.defaultIndex && 0 === $(this).index() ? ele.setVal_inHiddenField("", "", !0) : ele.setVal_inHiddenField(b, c);
        ele.inpWrap[0].focus();
        ele.onblur(ele, $(this));
         
      });
      
      ele.dropCont.on("mouseover", "li", function() {
        $(this).addClass("sAct")
      });
      ele.dropCont.on("mouseout", "li", function() {
        $(this).removeClass("sAct")
      });
    };
    
       
        SingleDD.prototype.setVal_inHiddenField= function(a, b, c) {
					
          var d, e = this.remDelimiter(b);
          d = this.selectColor;
         this.inpTextElm.val(a);
         this.inpTextElm.val(a).css({
            color: d
          });
          this.hidElm.val(e);
          
          (e || c) && (this.callBack ? this.callBackFn(e,this.id) : "")
        };
         SingleDD.prototype.disable = function() {

          this.inpWrap.off("click keydown focus blur")
        };
         SingleDD.prototype.enable =  function() {
          c.call(this._this)
        };
         SingleDD.prototype.onblur = function(a, b) {
          var c = this;
          var ele=b;
          
          this.dropCont.slideUp(this.animationSpeed, function() {
						
						if (! c.inpTextElm.is('[readonly]') )
						{
							c.inpTextElm.attr("placeholder",c.inpTextElm.attr("placeholderpre"));
						}
						
	           ele.onClose ? ele.onClose() : "";
          });
          
				
        };
        SingleDD.prototype.setValue = function(a) {
          var b = a.attr("id");
         
          this.defaultIndex && -1 == a.index() ? this.setVal_inHiddenField.call(this, "", "") : a && a.length && this.setVal_inHiddenField.call(this, a.text(), b);
        };
         
         SingleDD.prototype.keyCode =  function(a) {
          return a.keyCode || a.which
        };
         SingleDD.prototype.disb_Scroll_handler =  function(a) {
					 var ele = this;
					 var b = a.keyCode || a.which;
					//return 40 === b || 38 === b || 32 === b ? (a.preventDefault(), !1) : void 0
          return 40 === b || 38 === b ? (a.preventDefault(), !1) : void 0
        };
         SingleDD.prototype.disableScroll =  function() {
					 var ele = this;
          $(window).on("keydown", ele.disb_Scroll_handler)
        };
         SingleDD.prototype.enableScroll = function() {
					 var ele = this;
						$(window).off("keydown", ele.disb_Scroll_handler)
        };
         SingleDD.prototype.nextSelection = function(a) {
          
          if(typeof a==="undefined")
          {
						this.inpWrap.focus();
						a = this.currActiveItem;
					}
									
					var b = a.next();
					
         return b.length ? (a.removeClass("sAct"), this.currActiveItem = b, b.addClass("sAct")) : b = a, b;
         
        };
         SingleDD.prototype.prevSelection = function(a) {
					 
          if(typeof a==="undefined")
          {
						
						this.inpWrap.focus();
						a = this.currActiveItem;
					}
          var b = a.prev();
          
          return b.length && (a.removeClass("sAct"), this.currActiveItem = b, b.addClass("sAct")), b
        };
         SingleDD.prototype.remDelimiter =  function(a) {
          return a.toString();
        };
      
			SingleDD.prototype.callBackFn = function(selected,id){
				/**
				 * Jeevasathi business logic for different selections
				 */
				 
				 var newLabel ="";
				 var newHidValue ="";
				if(id=="search_religion")
				{
					var selectedField = $("#"+selected).attr("data");
					if(selectedField == '2' || selectedField == '3')
						newLabel = "Select Sect";
					else
						newLabel = "Select Caste";
					this.changeData("religion","caste",selectedField,"search_caste",newLabel,newHidValue);
					
				}
				else if (id =="search_gender")
				{
					var selectedField = $("#"+selected).attr("data");
					selectedField == 'M'? (newLabel="21 yrs",newHidValue="21",minvalue=21):(newLabel="18 yrs",newHidValue="18",minvalue=18);
										
					this.changeData("gender","lage",minvalue,"search_lage",newLabel,newHidValue);
					var curlageValueId = $("#search_lageHid").val();
					minvalue = $("#"+curlageValueId).attr("data");
					this.changeData("lage","hage",minvalue,"search_hage",minvalue+" yrs",minvalue);
					
				}
				else if (id =="search_lage")
				{
					var selectedField = $("#"+selected).attr("data");
					this.changeData("lage","hage",selectedField,"search_hage",selectedField+" yrs",selectedField);
				}
				else if( id == "search_mtongue" || id == "search_location")
				{
					fieldName = id.replace(/search_/g,"");
					this.changeData("",fieldName,"",id,"","");
				}
				
				
			};
			
			SingleDD.prototype.getLiStructure = function(liStructure,fieldName,data,extraAttribute,extraAttributeValue)
			{
					var temp  = liStructure;
					var myClass = ""; //LATER
					if(data["GROUP"]){
						if(data["IN_GROUP"])
							myClass+=" js-inGroup";
						if(data["IS_GROUP_HEADING"])
							myClass+=" js-isGroupheading";
						if(data["ISGROUP"])
							myClass+=" js-isGroup";
					}
					else
						myClass = "js-noGroup";
					var label = data["LABEL"];
					if(fieldName == "lage" || fieldName =="hage")
						label +=" yrs";
						
					temp=temp.replace(/{field}/g,fieldName);
					temp=temp.replace(/{data}/g,data["VALUE"]);
					temp=temp.replace(/{value}/g,data["VALUE"]);
					temp=temp.replace(/{myClass}/g,myClass);
					temp=temp.replace(/{group}/g,data["GROUP"]?data["GROUP"]:"");
					temp=temp.replace(/{label}/g,label);
					if(extraAttribute!="")
					{
						temp=temp.replace(/{extraattribute}/g,extraAttribute);
						temp=temp.replace(/{extraattributevalue}/g,extraAttributeValue);
					}
					else
						temp=temp.replace(/{extraattribute}="{extraattributevalue}"/g,"");
					
					return temp;
				
			};
			SingleDD.prototype.changeData = function(currentField,fieldName,fieldVal,ddId,newLabel,newHidValue)
			{
				var newList = "";
				var liStructure = $("#sf_field_structure").html();
				staticData =getStaticDataResponse();
				var finalarr= [];
				
				if(fieldName=="caste")
				{
					$("#"+ddId).find(".sdTxt").removeAttr("disabled");
					$("#"+ddId).find(".smArw").removeClass("disp-none");
					var extraAttribute = 'inreligion';
					if(typeof fieldVal ==="undefined" || fieldVal == "DONT_MATTER" || fieldVal ==""){
						for (var key in staticData[fieldName])
						{
							var data = staticData[fieldName][key];
							
							for(var i=0;i<data.length;i++)
							{
								finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],extraAttribute,key));
								
							}
						}
					}
					else {
						
						if(staticData[fieldName].hasOwnProperty([fieldVal]) != false)
						{
							var data = staticData[fieldName][fieldVal];
							for(var i=0;i<data.length;i++)
							{
								finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],extraAttribute,fieldVal));
								
							}
							$("#"+ddId).find(".sdTxt").attr("placeholder",newLabel);
							$("#"+ddId).find(".sdTxt").val("");
							$("#"+ddId+"Hid").val(newHidValue);
						}
						else
						{
							
							$("#"+ddId).find(".sdTxt").attr("disabled",true);
							$("#"+ddId).find(".smArw").addClass("disp-none");
							$("#"+ddId).find(".sdTxt").val("");
							$("#"+ddId+"Hid").val("");
						}
					}
					
					
				}
				else if(fieldName=="lage" || fieldName=="hage") {
					var data = staticData["age"];
					var firstData = "";
					for(var i=0;i<data.length;i++)
					{
						if(parseInt(data[i]["VALUE"])>=fieldVal)
						{
							firstData =firstData?firstData:data[i]["LABEL"]+" yrs";
							finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],"",""));
							
						}
					}
					var curValueId = $("#"+ddId+"Hid").val();
					var curValue = $("#"+curValueId).attr("data");
					if(curValueId=="" || curValue < newHidValue){
						$("#"+ddId).find(".sdTxt").val(newLabel);
						newHidValue = "sf_"+fieldName+"_"+newHidValue;
						$("#"+ddId+"Hid").val(newHidValue);
						
					}
					$("#"+ddId).find(".sdTxt").attr("placeholder",firstData);
				}
				else if(fieldName == "mtongue" || fieldName == "location")
				{
					var data = staticData[fieldName];
					for(var i=0;i<data.length;i++)
					{
						 if(data[i]["ISGROUP"]!="Y")
							finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],"",""));
					}
				}
				this.totalCount = finalarr.length;
				$("#"+ddId).find("ul").html(finalarr.join(""));
				
								
			};
		 SingleDD.prototype.performSearch = function(){
			var searchText = this.inpTextElm.val();
			
			var newList = "";
			var liStructure = $("#sf_field_structure").html();
			staticData = getStaticDataResponse();
			var finalarr= [];
			var fieldName = this.id.replace("search_","");
			
			var requiredLis = {};
			var checkAbbr=new CheckAbbr;
	    var regexAnchor="^";
	    var lastValue ="";
	    var prev="";
	    var type = fieldName.toUpperCase();
	    var ddId = this.id;
			var l= searchText.length;
			var flag2=0;
			var findArr={};
			prev=searchText.replace(/ /g, "");
			prev=checkAbbr.checkNode(type,prev);
				if(!prev)
		    {
					this.hidElm.val("");
					
					$.each(this.valjson,function(j,v)
						{
							 requiredLis[v.id] =v.id;
						});
						
						
				}
				else
				{
					$.each(this.valjson,function(j,v)
						{
							var flag=0;
							var realStr=v.str.replace("("," ").replace(")","");
							var strArr=realStr.split(/[\ \/,]/);
							
							
							var regex = new RegExp(regexAnchor + prev.replace(/ /g, "").replace(/[-[\]{}()*+?.,\\^$|#]/g, "\\$&"), 'i');
							 var findinArr=0;
	             for(var i=0;i<=strArr.length;i++)
	             {
	                 if(strArr.length==i)
                      var str=v.str.trim();
	                 else    
                      str=strArr[i];
                    str = str.replace(/ /g, "");
										if(regex.test(str) && !$(v.id).hasClass("isGroupheading"))
										{
											if(!findArr[realStr])
											{
												requiredLis[v.id] =v.id;
												 
												
												flag2=1;
												findArr[realStr]=1;
											}
												findinArr=1;
												break;
										}
							}
						});
						
						if(flag2==0)
						{
							$.each(this.valjson,function(j,v)
							{    
								str2=v.str;
								
								wordstr2=str2.split(/\ |\//);
								var wordstr=prev;
								
								for(var i=0;i<wordstr2.length;i++)
								{   
									var z=wordstr2[i];
									if(l>3)
									{
										
										y=levenshtein_distance_ham(z,searchText)
										
										if(y<=2)
										{
											if(!findArr[str2])
											{
												findArr[str2]=1;
												requiredLis[v.id] =v.id;
												
												
											}
											
										}
										
									}
								}
							});
						}
						
					}
				if(fieldName=="caste")
				{
					var fieldValId = $("#search_religionHid").val();
					var fieldVal = $("#"+fieldValId).attr("data");
					if(typeof fieldValId ==="undefined" || fieldValId =="" || fieldVal == "DONT_MATTER"){
						for (var key in staticData[fieldName])
						{
							var data = staticData[fieldName][key];
							var extraAttribute = 'inreligion';
							for(var i=0;i<data.length;i++)
							{
								if(requiredLis.hasOwnProperty(data[i]["VALUE"]) != false )					
								{
									delete requiredLis[data[i]["VALUE"]];
									finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],extraAttribute,key));
								}
								
							}
						}
					}
					else {
						var data = staticData[fieldName][fieldVal];
						for(var i=0;i<data.length;i++)
						{
							if(requiredLis.hasOwnProperty(data[i]["VALUE"]) != false )					
							{
								delete requiredLis[data[i]["VALUE"]];
								finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],extraAttribute,fieldVal));
								
							}
							
						}
					}
				}
				else
				{
						var data = staticData[fieldName];
						for(var i=0;i<data.length;i++)
						{
							if(requiredLis.hasOwnProperty(data[i]["VALUE"]) != false )					
							{
								if((fieldName!="location" && fieldName!="mtongue") || data[i]["ISGROUP"]!="Y")
								{
									
									delete requiredLis[data[i]["VALUE"]];
									finalarr.push(this.getLiStructure(liStructure,fieldName,data[i],"",""));
								}
							}
							
						}
					}
				$("#"+ddId).find("ul").html(finalarr.join(""));
				this.currActiveItem = this.dropCont.find("li:first-child");
				this.hidElm.val() ? "" : this.currActiveItem.addClass("sAct");
				this.innerDropLayer[0].csb.reset();
				
			
			};
    
    
		
		 SingleDD.prototype.slideFunctions = function() {
      var element = this;
      
      element.elm.on("click", ".dWrap, .sdTxt, .smArw, .arw", function(ev) {
				ev.stopPropagation();
				
				if(element.inpTextElm.attr("disabled"))
				{
					
					return;
				}
				
				element.inpWrap.focus();
				
				if ("block" == element.dropCont.css("display"))
        { 
					element.onblur(ev, $(this));
					
				}
        else if (element.currentActive) {
					if (!element.inpTextElm.is('[readonly]') ) {
						if(element.hidElm.val()=="" && element.inpTextElm.attr("placeholder")!="Select or Type" ){
							element.inpTextElm.attr("placeholderpre",element.inpTextElm.attr("placeholder"));
							element.inpTextElm.attr("placeholder","Select or Type");
						}
						element.inpTextElm.focus();
						
						
						if(element.dropCont.find("ul").children().length <element.totalCount)
						{
								
							if(element.id == "search_caste")
							{
								element.callBackFn($("#search_religionHid").val(),"search_religion");
							}
							else
								element.callBackFn("",element.id);
							
								
						}
					}
						
					
					var k=0;
					element.valjson={};
		      element.dropCont.find("ul").children().each(function(key,val)
					{
						var str=$(val).text();
						var val = $(val).attr("data");
						element.valjson[k]={key:key,id:val,str:str};
						k = k+1;
					 });
			
          element.dropCont.css({
              width: element.width
            }).slideDown(element.animationSpeed, function() {
              element.innerDropLayer[0].csb && (element.innerDropLayer[0].csb.reset(), element.dropCont.find(".sDDcontent").animate({
                scrollTop: "0px"
                
              }, 500)), element.onOpen ? element.onOpen() : "";
              
            });
           
          
          if(previousOpen != null && previousOpen != element)
          {
							previousOpen.onblur(ev, previousOpen);
					}
          previousOpen = element;
            
          }
          
      });
      element.inpTextElm.on("keydown",function(e){
        if($(this).val().trim() == '' && element.keyCode(e) == 32){
          e.preventDefault();
          return false;
        }
      });
      element.inpTextElm.on("keyup",function(c){
				var noSearchKeysArr = [27,37,38,39,40,13,9];
					
				if (e.which !== 0 && $.inArray(element.keyCode(c),noSearchKeysArr) == -1)
				{ 
					
					if (!element.inpTextElm.is('[readonly]') )
					{
							//element.inpTextElm.attr("placeholder",element.inpTextElm.attr("placeholderpre"));
							element.performSearch();
						
					}
					
				}
				
			});
      element.inpWrap.on("keydown", function(c) {
				
					var d, e = element.keyCode(c),
          f = element.dropCont.find(":first-child"),
          h = element.dropCont.find("ul"),
          i = (h.parents(".sDrop"), h.parent());
          
          if(39 == e || 40 == e )
          {
						
						 d = element.nextSelection.call(element, element.currActiveItem);
						
						 element.setValue.call(element, d);
						 manageKeyScroll.scrollHandler(i, h.parent(), f, d);
						 c.preventDefault();
					}
					else if(37 == e || 38 == e)
					{
						d = element.prevSelection.call(element, element.currActiveItem);
						element.autoSelect;
						element.setValue.call(element, d);
						manageKeyScroll.scrollHandler(i, h.parent(), f, d);
						c.preventDefault();
					}
					else if (9 == e || 13 == e || 27 == e)
					{
						if(e ==13)
						{
							element.setValue.call(element, element.currActiveItem);
						}
						element.currentActive = !1;
						element.onblur(c, $(this)); element.currentActive = !0;
						c.preventDefault();
					}
					
					
      });
      
      element.inpWrap.on("focus", function(a) {
				
				var b = this;
				element.onFocus && element.onFocus();
        element.disableScroll(a);
        
       
        element.currActiveItem = element.dropCont.find("li:first-child");
        
        element.hidElm.val() ? "" : element.currActiveItem.addClass("sAct");
        
        
      });
     
      element.inpWrap.mousedown(function() {
        return !1
      })
    };
    this.SingleDD=SingleDD;
	}).call(this);
})();

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
	
