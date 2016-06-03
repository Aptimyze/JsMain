(function() {
  var CheckAbbr;

  CheckAbbr = (function() {

    function CheckAbbr() {
      this.defaultAbbr={'country':{'USA':'United States',"US":"United States","UK":"United Kingdom","UAE":"United Arab Emirates"},'city':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"},'caste':{"AGRAW":"Aggarwal"},'state':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"}};
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
var parentWidth;
var hidden = 0; 
function searchDropdown(listObj,inputBox,type)
{
    var k=0;
    var valjson={};
    var checkAbbr=new CheckAbbr;
    var regexAnchor="^";
    var lastValue ="";
    var prev="";
    var otherArr = ["Others","others","Other","other"];
    var otherLi;
    var me = this;
    if(listObj.parent().parent().parent().hasClass("disp-none"))
        hidden = 1;
    parentWidth = 485;
    listObj.find(".searchUl").each(function(key1,val1)
    {
        $(val1).children().each(function(key,val) { 
            var str=$(val).children().first().text();
            var state="0";
            var wid = $(val).css("width");
            valjson[k]={key:key,id:val,str:str,state:state,width:wid};
            k = k+1;	
            if($.inArray($(val).children().html(),otherArr) != -1){
              otherLi = val;
            }
        });
    });
    this.valJson = valjson;
    inputBox.bind("input",function(ev)
    {   
	    searchValue($(this));
    });
    inputBox.unbind("click");
    //Function to change back the listing to normal one on blur
    inputBox.on("blur",function(event){
      if(parseInt($(valjson[0].id).css("width")) === parentWidth){
        $.each(me.valJson,function(j,v)
        {
          $(v.id).css("width",v.width);
        });
      }
      if(isBrowserIE() === false){
        $(this).trigger("customBlur");
      }
    });
    inputBox.bind("click focus",function(ev)
    {   
      stopEventPropagation(ev,1);
        if(hidden)
	  listObj.parent().parent().parent().hide();
        else 
          listObj.parent().parent().parent().show();
        $(this).val("");
        $("#"+type+"-gridDropdown_set").show();
        $(this).attr("placeholder","Select or type");
        searchValue($(this));
    });
    
    //Bind keyDown event & pass it to multipleUls option
    inputBox.keydown(function(event){
      
      var arrAllowedKeyCode = [13,38,40];
      var blockKeyCode = [191,220];
      
      //Black Listing keycodes
      if(blockKeyCode.indexOf(event.keyCode) != -1){
        stopEventPropagation(event,1);
        event.preventDefault();
        return false;
      }
      //If Arrow keys call handle keybpard function
      if(arrAllowedKeyCode.indexOf(event.keyCode) === -1)
      {
        return;
      }
      
      if(parseInt($(me.valJson[0].id).css("width")) !== parentWidth){
        $.each(me.valJson,function(j,v)
        {
          $(v.id).css("width",parentWidth);

        });
      }
      
      var newEvent = jQuery.Event("keydown");
      newEvent.keyCode = event.keyCode;
      $("#" + type + "-multipleUls").trigger(newEvent);
      
      event.stopPropagation();
      event.stopImmediatePropagation();
      event.preventDefault();
      return ;
      
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
                        if(hidden)
                          listObj.parent().parent().parent().hide();
                        $(".isGroupheading").each(function(){
                          $(this).removeClass("hide");
                        });
			$.each(valjson,function(j,v)
			{
				if(v.state=="0")
					$(v.id).removeClass("hide");
				else
					$(v.id).addClass("hide");
                                $(v.id).css("width",v.width);
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
                        $(".isGroupheading").each(function(){
                          $(this).addClass("hide");
                        });
                          listObj.parent().parent().parent().show();
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
                        $(v.id).css("width",parentWidth);
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
			if(regex.test(str) && !$(v.id).hasClass("isGroupheading"))
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
                $("#" + type + "-multipleUls").find('.activeopt').removeClass("activeopt");
                $("#" + type + "-multipleUls").find('li:visible:first').addClass("activeopt");
		if(Object.keys(findArr).length<=0)
		{
                        if(otherLi) 
                          $(otherLi).removeClass("hide").addClass("activeopt");
			else if(!$("#noresf").length)
			  listObj.first().append("<li value=-1 id='noresf' class='pl15 pt7 pb7 lstNone' >No results found</li>");
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


