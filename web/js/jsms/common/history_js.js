/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function(){
	var HistoryStore=(function(){
		function HistoryStore()
		{
			this.History=[];
		}
		HistoryStore.prototype.push=function(fnc,hashVal)
		{
                        this.func=fnc;
			this.History.push(fnc);
			this.SetLocation(hashVal);
		}
		HistoryStore.prototype.pop=function(fnc)
		{
			toHistoryCall=1;
			if(this.History.length>0)
			{
				
				if(history.replaceState)
                                       history.replaceState(null,null,document.location.href);
				var pop=this.History.pop();
				var result=pop();
				var e=new Error();
				if(!result)
				{
                                    //history.replaceState(null,"",document.location.href);
                                    //this.pop();
                                    history.back();
				}
				
			}
			
		}
		
		HistoryStore.prototype.SetLocation=function(hashVal)
		{
                    if(typeof(hashVal)=="undefined")
                            hashVal="#undef";
                        
                    var dl=document.location.href;
                    var hashPresent=0;
                    if(hashVal.indexOf("#")==0)
                    {
                       dl=document.location.origin+document.location.pathname+document.location.search+hashVal;
                        
                    }
                    else
                        dl=hashVal;
                    var fnc=this.fnc;
			if(history.pushState)				
                        { 
                            history.replaceState({"state":document.location.href},"",document.location.href);
                            history.pushState(null,"",dl);
                        } 
			//document.location.href=url+"#"+hashString+",historyCall";
		}
		this.HistoryStore=HistoryStore;
	}).call(this);
})()


if(typeof(historyStoreObj)=="undefined")
var historyStoreObj=new HistoryStore();
var toHistoryCall=0;
window.onpopstate = function(event) {
    
    if(event.state)
    {
       
        
        history.replaceState(null,"",document.location.href);
        if(historyStoreObj.History.length>0)
            historyStoreObj.pop();
        else
           document.location.reload();
    }   
    else{
		if((document.location.href).indexOf("/viewprofile.php")!=-1 && !ISBrowser("UC"))
		{
			//if(historyStoreObj.History.length>0)
			//	historyStoreObj.pop();
		}
	}
};
//var locationArr=document.location.href.split("?");
