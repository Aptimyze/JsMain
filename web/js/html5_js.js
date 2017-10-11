document.createElement('header');
document.createElement('hgroup');
document.createElement('nav');
document.createElement('menu');
document.createElement('section');
document.createElement('article');
document.createElement('aside');
document.createElement('footer');
/*
$(document).ready(function(){
	$.each($("select"),function(key,sel){
		UpdateSelectDropDown(sel);
	});
	
});
function UpdateSelectDropDown(sel)
	{
		var select =$(sel);
		//alert(select.text());
		if(!select.text())
			return;
			
		if(select.parent().attr("class")=="selectParent")
		{
				
				
				if(select.parent().children(":first-child").attr("class")=="selectText")
					select.parent().children(":first-child").remove();
					select.unwrap();
		}	
		var div="<div class='selectParent'></div>";
		var sideDiv="<div class='selectText'>Please select</div>";
		select.wrap(div);
			select.before(sideDiv);
			select.css("opacity",0);
			
		select.bind("change",function(){
			DefaultSelect($(this));
			
			});
		DefaultSelect(select);
			
		
		
	}
	function DefaultSelect(ele)
	{
		var id=ele.attr("id");
		var not100=ele.attr("not100");
		
			var value=$("#"+id+" :selected").text();
			
			ele.parent().children(":first-child").html(value);
			
			if(typeof(not100)!="undefined")
			{
				var width=parseInt(100/not100-2)+"%";
				ele.parent().width(width);
				ele.parent().css("margin-right","2px");
				ele.width(ele.parent().width());
			}	
	}
*/
