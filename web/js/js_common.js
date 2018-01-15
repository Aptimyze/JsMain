$(function(){
	
	   var vwid = $( window ).width();
	   var vhgt = $( window ).height();
	   
	  
		    var hgt = $( window ).height();
			hgt = hgt+"px";
			var wid = $( window ).width();
			wid = wid+"px";
			console.log(vwid+"="+vhgt+"="+hgt+"="+wid);
			$('div.grad_cp, div.outerdiv').css( "height", hgt );
			$('.imgset1').css( "height", hgt );
			$('.imgset1').css( "width", wid );
			
	
	 
	  
});