/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
export default class HistoryStore
{
constructor(){
	this.History=[];
	var _this=this;
	window.onpopstate = function(event) {
		if((document.location.href).indexOf("#")==-1) return;
	    if(event.state)
	    {

	        history.replaceState(null,"",document.location.href);
	        if(_this.History.length>0){
	            _this.pop();}
	        else
	           history.back();
	    }
	    else{
			if((document.location.href).indexOf("/viewprofile.php")!=-1 && !ISBrowser("UC"))
			{
				history.back();
			}
		}
	};
}

		push(fnc,hashVal)
		{
      this.func=fnc;
			this.History.push(fnc);
			this.SetLocation(hashVal);
		}
		pop(fnc)
		{
			if(this.History.length>0)
			{

				if(history.replaceState)
            history.replaceState(null,null,document.location.href);
				var pop=this.History.pop();
				var result=pop();
				var e=new Error();
				if(!result)
				{
            history.back();
				}

			}

		}

		SetLocation(hashVal)
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

}
//var locationArr=document.location.href.split("?");
