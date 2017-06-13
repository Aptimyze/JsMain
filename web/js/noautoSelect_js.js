var _noOption={url:'',dependant:'',onlyAjax:'',matchAlgo:'',dependant:false,autofill:true,dependantOption:null,stay_open:false,customFunction:null,preCheckCall:null,type:null,search_contains:false};

var noAutoSelect=function(formele,options){
this.form_field=formele;
this.options = $.extend({},_noOption,options);
var ele=this;
formele.bind("change",function(){ele.ElementChange();});
formele.bind("liszt:updated",function(){ele.ElementChange();});
}
noAutoSelect.prototype.ElementChange=function()
{
	var val=$(this.form_field).val();
	var isAllowed=0;
	if(this.options.customFunction)
	{
		fn=this.options.customFunction;
		fn.apply(this,[val]);
	}
	if(this.options.preCheckCall && val)
		 isAllowed=eval(""+this.options.preCheckCall+"('"+val+"')");
	if(val && isAllowed && this.options.dependantOption)
	{
		var dependantOptions=this.options.dependantOption;
		var depID="#"+dependantOptions.id;
		var ele=this;
		var dval=$(depID).val();

		if(!$(depID).val())
			dval=null;
		if(dval==null && dependantOptions.defaultValue)
				dval=dependantOptions.defaultValue;
		var url=dependantOptions.url+"&l="+val+"&d="+dval;

		if(cacheResulted.getData(url))
		{
				ele.UpdateDepID(depID,cacheResulted.getData(url));
		}
		else
		{
			$.get(url, {dataType: "json"},function (data){
			cacheResulted.addData(url,data);
					ele.UpdateDepID(depID,data);
					});
		}
	}
	else
	{
		$(depID).html("");
	}	
}
noAutoSelect.prototype.getOptions=function(jsons) {
	//return jsons;
	var str="";
	var prev=0;
	var label=0;
	var allowed=1;
	$.each(jsons, function(index, itemData) {
		var selected="";
		if(itemData[1].indexOf("Please")!=-1)
		{
			label++;
			if(label>1)
				allowed=0;
			
		}
		if(allowed)
		{
			if(itemData[2])
					selected="selected";
			if(itemData[3] && prev==1)
						str+="</optgroup>";
			if(itemData[3])
			{
							prev=1;
							str+="<optgroup label='"+itemData[1]+"'>";
			}
			else
						str+="<option value='"+itemData[0]+"' "+selected+">"+itemData[1]+"</option>";
		}
		else
			allowed=1;
	});
	if(prev==1)
		str+="</optgroup>";
	return str;
}
noAutoSelect.prototype.UpdateDepID=function(depID,data,ele){
	if(data)
		$(depID).html(this.getOptions(data));
	else
		$(depID).html("");
	$(depID).trigger("change");

};
