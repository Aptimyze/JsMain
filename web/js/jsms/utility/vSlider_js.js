(function($){
	$.fn.VSlider = function(options){
		// first get the original window dimens (thanks alot IE)
		var windowWidth = options.width;
		var windowHeight = options.height;
		var sliderHeight=options.sliderHeight;
		var fakebottom=options.fakeb;
		var faketop=options.faket;
		var el=$(this);
		var selectedSliderIndex=-1;
		
		var childElement=el.children();
		var thresholdWidth=5;
		var stTime;
		var slider={"threshold":10,"working":false,"x_threshold":2,"movement":true,"transform":0,"index":0,"maxindex":0};
		
		var init=function(){
			CssFix();
			WrapParent();
			AddCssToSelf();
			AlterChildrenCss();
			//AddSwipeEvents();
			initTouch();
			$(document).bind(
'touchmove',
function(e) {
e.preventDefault();
}
);
			//$.each(,function(index,element){
				//AlterChildrenCss(index,element);
			//});
		}
		var CssFix=function()
		{
			// create our test div element
				var div = document.createElement('div');
				// css transition properties
				var props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
				// test for each property
				for(var i in props){
					if(div.style[props[i]] !== undefined){
						slider.cssPrefix = props[i].replace('Perspective', '').toLowerCase();
						slider.animProp = '-' + slider.cssPrefix + '-transform';
						return true;
					}
				}
		};
		var WrapParent=function()
		{
			el.wrap("<div class='wrap-box' id='wrapbox'></div>");
			slider.parent=$(el);
			//$(".wrap-box").css("height",windowHeight);
			//$("#wrapbox").wrap("<div class='swrapper' id='swrapper'></div>");
			//slider.parent.parent=$("#swrapper");
			
		}
		var AddCssToSelf=function()
		{
			var width=window.Width;
			var height=childElement.length*sliderHeight+thresholdWidth;
			el.css("width",width+"px");
			el.css("height",height+"%");
			
		}
		var AlterChildrenCss=function()
		{
			slider.maxindex=childElement.length-1-fakebottom-faketop;
			$.each(childElement,function(index,element){
				if($(element).hasClass('checked'))
					selectedSliderIndex=index-faketop;
				$(element).removeClass("checked");
				$(element).css('width',windowWidth);
				//$(element).css('height',windowHeight);
				$(element).attr("index",index);
				//AlterChildrenCss(index,element);
			});
		}
		var WrapChidren=function(ele)
		{
			//console.log(childElement);
		}
		var initTouch=function()
		{
			setPositionProperty(slider.index);
			slider.touch = {
				start: {x: 0, y: 0},
				end: {x: 0, y: 0}
			}
			slider.parent.bind('touchstart', onTouchStart);
    
            if(ISBrowser("AndroidNative"))
                slider.parent.bind('click', function(ev){stopPropagation(ev);});
			
            if(selectedSliderIndex!=-1)
				el.gotoSlide(selectedSliderIndex);
		
		}
		var onTouchStart=function(e)
		{
			
			{
				
				// record the original position when touch starts
				slider.touch.originalPos = el.position();
				var orig = e.originalEvent;
				// record the starting touch x, y coordinates
				slider.touch.start.x = orig.changedTouches[0].pageX;
				slider.touch.start.y = orig.changedTouches[0].pageY;
				// bind a "touchmove" event to the viewport
				slider.parent.bind('touchmove', onTouchMove);
				// bind a "touchend" event to the viewport
				slider.parent.bind('touchend', onTouchEnd);
				
				stTime=now = new Date().getTime();
			}
            stopPropagation(e);
		}
		var onTouchMove=function(e)
		{
			//console.log('move');
			var orig = e.originalEvent;
			
			var xMovement = Math.abs(orig.changedTouches[0].pageX - slider.touch.start.x);
			var yMovement = Math.abs(orig.changedTouches[0].pageY - slider.touch.start.y);
			var change = orig.changedTouches[0].pageY - slider.touch.start.y;
			
			if(xMovement)
				xMovement=1;
				//console.log(xMovement+" "+yMovement);
			if(slider.movement && yMovement>xMovement && yMovement>4)
			{
				//slider.touch.
				change = slider.touch.originalPos.top+change;
				//console.log(slider.touch.originalPos.left);
				setPositionProperty(change);
			}
			
			
		}
		var setPositionProperty=function(value)
		{
			var propValue = 'translate3d(0,' + value + 'px, 0)';
			//console.log("translate3d("+change+"px,0,0)");
			
			el.css('-' + slider.cssPrefix + '-transition-duration', 0 + 's');
			el.css(slider.animProp, propValue);
		}
		var onTouchEnd=function(e)
		{
			stopPropagation(e);
			slider.parent.unbind('touchmove', onTouchMove);
			var orig = e.originalEvent;
			var value = 0;
			// record end x, y positions
			slider.touch.end.x = orig.changedTouches[0].pageX;
			slider.touch.end.y = orig.changedTouches[0].pageY;
			var distance = 0;
			distance = slider.touch.end.y - slider.touch.start.y;
			value = slider.touch.originalPos.top;
			valueTop=$(el).position().top;
			var goto=Math.ceil(Math.abs(valueTop/sliderHeight));
			
			//console.log(distance);
			if(distance>0)
				goto=goto-1;
			if(distance==0)
				{
					var clickedDiv=$(e.target);
					if($(e.target).is("div"))
						clickedDiv=$(e.target).parent();
					//if($(e.target).is("ul"))
					//	console.log("no where to go "+faketop);
						
					goto=$(clickedDiv).attr("index")-faketop;
					
					//console.log(goto+" "+value+" "+slider.touch.start.y+" ");
				}	
			//console.log(goto+" -->"+valueTop+" ===>"+sliderHeight+" --->"+distance);
			//el.gotoSlide(goto);
			//checkOtherValue();	
			slider.parent.unbind('touchend', onTouchEnd);
			
			//Sliding on speed basics.
			 var speed=((new Date().getTime())-stTime)/100;
			 //console.log(distance+" "+speed);
			if(speed<2 && Math.abs(distance)>10)
			{
				if(distance<0)
					goto=goto+4;
				else
					goto=goto-4;
			}	
		
			//code appended for DPP suggestion
			var type = slider.parent.find("input").attr("name"),typeDataArray = [];
			if(type == "p_lage" || type == "p_hage" ) {
				setTimeout(function(){
					typeDataArray = [$("#HAM_OPTION_1 li input:checked").val(),$("#HAM_OPTION_2 li input:checked").val()];
					changeSuggestion("AGE", typeDataArray);
				},50);	
			} else if (type == "p_lrs" || type == "p_hrs") {
				setTimeout(function(){
					typeDataArray = [$("#HAM_OPTION_1 li input:checked").prev().html(),$("#HAM_OPTION_2 li input:checked").prev().html(),"No Income","and above"];
					changeSuggestion("INCOME",typeDataArray);
				},50);	
			} else if(type == "p_lds" || type == "p_hds") {
				setTimeout(function(){
					typeDataArray = ["No Income","and above",$("#HAM_OPTION_1 li input:checked").prev().html(),$("#HAM_OPTION_2 li input:checked").prev().html()];
					changeSuggestion("INCOME",typeDataArray);
				},50);	
			}
			el.gotoSlide(goto);
			checkOtherValue();
		}
		el.NextSlide=function()
		{
			
			var index=slider.index+1;
			if(index>slider.maxindex)
				index=slider.maxindex;
				
			var transformx=sliderHeight*(index);
			el.css('-' + slider.cssPrefix + '-transition-duration', .5 + 's');
			var propValue = 'translate3d(0.-' + transformx + 'px, 0)';
			el.css(slider.animProp, propValue);
			slider.index=index;
			
			FixHeight();
		}
		el.PrevSlide=function()
		{
			
			var index=slider.index-1;
			if(index<0)
				index=0;
			var transformx=sliderHeight*(index);
			el.css('-' + slider.cssPrefix + '-transition-duration',.5 + 's');
			
			var propValue = 'translate3d(0,-' + transformx + 'px, 0)';
			el.css(slider.animProp, propValue);
			slider.index=index;
			
			FixHeight();
		}
		el.gotoSlide=function(index,notop)
		{
			if(notop)
				index=index-faketop;
			
			if(index<0 || index>slider.maxindex)
			{
				slider.index=slider.maxindex;
					if(index<0)
						index=0;
					if(index>slider.maxindex)
						index=slider.maxindex;
					el.gotoSlide(index);	
					return;
			}
				
			var transformx=sliderHeight*(index);
			el.css('-' + slider.cssPrefix + '-transition-duration', .5 + 's');
			var propValue = 'translate3d(0,-' + transformx + 'px, 0)';
			el.css(slider.animProp, propValue);
			slider.index=index;
			$(el).children('[index="'+(index+faketop)+'"]').children('[type="radio"]').prop("checked",true);
							
			//setTimeout(function(){FixHeight();},500);
		}
		$(el).bind("gotoSlide",function(ev,index,notop){
			el.gotoSlide(index,notop);
		});
		var checkOtherValue=function()
		{
			
			if(options.type=="p_income_rs" || options.type=="p_income_dol" || options.type=="p_age" || options.type=="p_height")
			{
				var val=parseInt($("#HAM_OPTION_1").find("input:checked").val());
				var oval=parseInt($("#HAM_OPTION_2").find("input:checked").val());
				var prevVal=val;
				
						if(val<oval)
							return;
				
				if(options.who)
					prevVal=oval;
					
				//~ var data=(JSON.parse(staticTables.getData(options.type)))[0];
				//~ var incomeArr={};
				//~ var prevVal=-1;
				//~ var pass=0;
				//~ $.each(data,function(key,value){
					//~ $.each(value[0],function(k,v){
						//~ 
						//~ if(options.who && pass)
						//~ {
								//~ prevVal=k;
								//~ pass=0;
						//~ }		
						//~ if(k==val)
							//~ pass=1;
						//~ if(!options.who && pass)
						//~ {
							//~ prevVal=k;
							//~ pass=0;
						//~ }
						//~ if(pass!=1)
							//~ prevVal=k;
					//~ });
				//~ });
				if(prevVal!=-1)
				{	
					
					var hamId=$("#HAM_OPTION_1");
					if(!options.who)
						hamId=$("#HAM_OPTION_2");
						
						var z=parseInt($(hamId).find("input[value=\""+prevVal+"\"]").parent().attr("index"));
						if(!(options.type=="p_age" || options.type=="p_height"))	
						{
							if(options.who)
								z=z-1;
							else
								z=z+1;
						}
					hamId.trigger("gotoSlide",[z,1]);
				}
				
			}
		}
		var FixHeight=function()
		{
			
		}
		init();
		return el;
	}
	})(jQuery);








////////////////////////ONLY VErtical Slider///////////////////////////////////////






(function($){
	$.fn.OnlyVertical = function(options){return;
		return el;
	}
	})(jQuery);
