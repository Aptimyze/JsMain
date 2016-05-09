 var legalVal={"fraudalert":0,"disclaimer":20,"thirdparty":40,"privacypolicy":60,"privacyfeatures":80}
 $(document).ready(function(){
 	var valueToFill=legalVal[pageName];
 	$("#slideBar").css("left",valueToFill+'%');
 });