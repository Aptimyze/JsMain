var myUrl = window.location.href;

/**
* This function will return the value in the parameter of the url
* @param name {string}  param-name
* @param intPart {string} set if we need only integer part
* @retur value
*/
$.urlParam = function(name,intPart){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(myUrl);
        if (results==null)
                return null;
        else
        {
                var str = results[1] || 0;
                if(intPart)
                        return str.replace(/[^-\d\.]/g, '');
                return str;
        }
} 

/**
* This function will update the url.
* @param title : suggested that update url have a title
* @param page : page value
*/
$.urlUpdateHistory = function(title,page,addMoreParams){
        var randomnumber=$.now();
        var value = myUrl.substring(myUrl.lastIndexOf('?'));
        var param = '?page='+page+'&random'+randomnumber+"&"+addMoreParams;
        var stateObj = {};
        history.replaceState(stateObj,title,param);
}
