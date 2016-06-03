try{
var userAgent=navigator.userAgent;
if(userAgent.indexOf("Opera Mini")!=-1)
{
	$(document).ready(function(){
		$("body").prepend("<link href=\"/css/opera_css_1.css\" styletype=\"text\/css\" rel=\"stylesheet\">");
	})	
	//$("#opera_css").prop("href","/css/opera_css_1.css");
	
}
}
catch(ex)
{

}
