(function() {
  var CheckAbbr;

  CheckAbbr = (function() {

    function CheckAbbr() {
      this.defaultAbbr={'COUNTRY_RES':{'USA':'United States',"US":"United States","UK":"United Kingdom","UAE":"United Arab Emirates"},'CITY_RES':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"},'CASTE':{"AGRAW":"Aggarwal"},'STATE':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"}};
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
function ShowHamSearch(type,tapid)
{
	var searchid=$("#search_ham_"+tapid);
	if(searchid.length)
		searchid.val("");
	if($.inArray(type,['reg_city_jspc','state_res','city_res','country_res','caste','p_caste','p_country','p_city','occupation','mtongue','reg_mtongue'])==-1 && type.indexOf('reg_caste_') ==-1)
	{   
		searchid.parent().parent().addClass('dn');
			return false;
	}
	searchid.parent().parent().removeClass('dn');
	return true;
}
function searchHamburger(type,option,tapid)
{
	var checkAbbr=new CheckAbbr;
	type=type.toLowerCase();
	var searchid=$("#search_ham_"+tapid);
        
	if(!ShowHamSearch(type,tapid))
		return false;
    var k=0;
    var valjson={};
    $(option).children().each(function(key,val)
    {   
        if($(val).val()=="-1")
                var str="hideDivMore";
        else
		var str=$(val).children().first().text();
		var outerHtml="";
		if(ISBrowser("UC"))
			outerHtml=$(val)[0].outerHTML;
        valjson[key]={id:val,str:str,html:outerHtml};
    });
    var regexAnchor="^";
    searchid.val("");
    var prev="";
    searchid.unbind("input");
    
	searchid.bind("input",function()
	{
		$("#noresf").remove();
		var findArr={};
		if(prev==($(this).val().trim()))
			return;
		var searchResultClass= 'matchResult';
		prev=$(this).val();
		prev=checkAbbr.checkNode(type,prev);
		var output=[];
		if(ISBrowser("UC"))
			$(option).html("");
                if(!prev)
                {
                    $.each(valjson,function(j,v)
                    {
						output=SearchOption(v,"dn",searchResultClass,output);
                        //$(v.id).removeClass('dn').addClass(searchResultClass);
                    });
                    UpdateSearchOption(option,output);
                    return;
                }
         
		//console.log(prev);
		var l= $(this).val().length;
		var flag2=0;
		
		
		$.each(valjson,function(j,v)
		{
			var flag=0;
                        if(v.str=="hideDivMore")
                        {
							output=SearchOption(v,searchResultClass,"dn",output);
                            //$(v.id).removeClass(searchResultClass).addClass("dn");
                            return true;
                        }
                        var realStr=v.str.trim().replace("("," ").replace(")","");
			var strArr=realStr.split(/[\ \/,]/);

			var regex = new RegExp(regexAnchor + prev.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
                        var findinArr=0;
                        for(var i=0;i<=strArr.length;i++)
                        {
                            if(strArr.length==i)
                                str=v.str.trim();
                            else    
                                str=strArr[i];
                            
                            if(regex.test(str))
                            {
                                    if(!findArr[realStr])
                                    {
											output=SearchOption(v,"dn",searchResultClass,output);
                                            //$(v.id).removeClass("dn").addClass(searchResultClass);
                                            flag2=1;
                                            findArr[realStr]=1;
                                    }
                                    else
                                    {
										output=SearchOption(v,searchResultClass,"dn",output);
										//$(v.id).removeClass(searchResultClass).addClass("dn");
									}
                                   findinArr=1;
                                   break;
                            }
                            else
                            {
								output=SearchOption(v,searchResultClass,"dn",output);
								//$(v.id).removeClass(searchResultClass).addClass("dn");
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
						
						y=levenshtein_distance_ham(z,searchid.val())
						
						if(y<=2)
						{
							
							if(!findArr[str2])
							{
								findArr[str2]=1;
								//$(v.id).removeClass("dn").addClass(searchResultClass);   
								output=SearchOption(v,"dn",searchResultClass,output);
							}
							else
							{
								output=SearchOption(v,searchResultClass,"dn",output);
								//$(v.id).addClass("dn").removeClass(searchResultClass);
							}
						}
						else
						{
							output=SearchOption(v,searchResultClass,"dn",output);
							//$(v.id).addClass("dn").removeClass(searchResultClass);
						}
					}
				}
			});
	}
	UpdateSearchOption(option,output);
            if(Object.keys(findArr).length<=0)
            {
                if(!$("#noresf").length)
                {
                    arr = $(option).find('input[id="ham_dummy"]');
                    if(arr.length)
                    {
                        $.each(arr,function(key,value){
                            $(value).parent().addClass('dn');
                        });
                    }
                    
                    $(option).append("<li value=-1 id='noresf' class='hpad5 color14 f14' >No results found</li>");
                }
            }
        });
        
}
function UpdateSearchOption(option,output)
{
		if(ISBrowser("UC"))
		{
			var st=output.join("");
			if(st)
					$(option).append(st);
		}
}
function SearchOption(v,remove,add,output)
{
	if(!ISBrowser("UC"))
	{
		$(v.id).removeClass(remove).addClass(add);
	}
	else
	{
		var regEx=new RegExp("(class=)\"(.*)"+remove+"(.*)\"[0,1]","i");
		if(remove=="dn")
		{
			
			var htm=v.html;
			htm=htm.replace(regEx,"$1\"$2 $3 "+add+"\"");
			output.push(htm);
			//htm=htm.replace("class=","class='"+searchResultClass+" hpad5' class2=");
			
		}
		return output;
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
