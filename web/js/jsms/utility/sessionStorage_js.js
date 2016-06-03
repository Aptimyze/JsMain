(function() {

  var SessionStorage = (function() {

    SessionStorage.prototype.returnData = function(key)
    {
		this.recoverStaticTables();
		if(typeof key !== "undefined"  && typeof key === "string" && this.statictables.hasOwnProperty(key))
		{
			if(typeof this.statictables[key] === "string")
				return this.statictables[key];
			
			if(typeof this.statictables[key] === "object")
			{
				return JSON.stringify(this.statictables[key]);
			}
		}
		
		return false;	
	};
	SessionStorage.prototype.filterKeys = function(key)
	{
		this.recoverStaticTables();
		if(key.search(',') != -1)
		{
			var arrKey = key.split(',');
			var newKey = "";
			for(var i=0;i<arrKey.length;i++)
			{
				if(!this.statictables.hasOwnProperty(arrKey[i]))
					newKey = (newKey.length)?newKey+','+arrKey[i]:arrKey[i];
			}
			key = newKey;
		}
		return key;	
	}
    SessionStorage.prototype.getData=function(key,callBack,mode){
		try{
			this.recoverStaticTables();
			var ele = this;
			key = key.toLowerCase();
			key = this.filterKeys(key);
            var res = "";
			if(!key.length)
				return;
            if(typeof callBack == "function")
                mode = "A";
			if(!this.statictables.hasOwnProperty(key) || this.debugMode)
			{
				var myScope = this;
				this.fetch(key,mode).success(function(data,textStatus,jqXHR){
					if(jqXHR.status	== "200")
					{
						myScope.store(data,key);
                        if(typeof callBack == "function")
                            callBack(jqXHR.responseText);
						res = jqXHR.responseText;
                        return res;
					}
					else  
					{
						res = -1;/*Something Went Wrong*/
                         if(typeof callBack == "function")
                            callBack(res);
                        return res;
					}
				}).error(function(jqXHR,textStatus,errorThrown){
					//console.log(errorThrown);
					res =  -1;/*Something Went Wrong*/
                    if(typeof callBack == "function")
                            callBack(res);
                    return res
				});
			}
            else if(this.statictables.hasOwnProperty(key))
			{	
                if(typeof callBack == "function")
                    callBack(this.returnData(key));
                    
				return this.returnData(key);
			}
            if(res != "")
                return res;
		}catch(e){
			//console.log(e.stack);
		}
    };
    SessionStorage.prototype.parseJson = function(data)
    {
		try{
			data = JSON.parse(data);
			return data;
		}catch(e)
		{
			return false;
		};
	}
    SessionStorage.prototype.store=function(value,key)
    {
		var ele=this;
		var bIsArrayOfData = false;
		var arrData = {};
		this.recoverStaticTables();
		if(typeof value === "string" && value)
		{
			value=this.parseJson(value)
		}
		
		if(value === false)
			return;
			
		if(key.indexOf(',') === -1)
			arrData[key.toString()] =value; 
		else
			arrData = value;
			
		var staticData = this.parseJson(localStorage.getItem(this.stname));
		if(staticData === false)
			return;
		for (var key in arrData) 
		{
			if(typeof arrData[key] !== "object")
				continue;
				
			this.statictables[key] = arrData[key];
			if(typeof(Storage)!='undefined' && !this.debugMode)
			{
				if(staticData)
				{
					staticData[key] = arrData[key];
				}
				else
				{
					staticData = {};
					staticData[key]= arrData[key];
				}
			}
		}
		if(typeof(Storage)!='undefined' && !this.debugMode)
		{
			value = JSON.stringify(staticData);
			localStorage.setItem(this.stname,value);
		}
		
		return value;
    };
    SessionStorage.prototype.fetch=function(key,mode)
    {
		var res = this.returnData(key);

		if(typeof res !== "boolean" && !this.debugMode)
			return ;
		
		if(typeof(Storage)!='undefined' || !this.debugMode)
		{
			var ele=this;
      var staticUrl = "/static/getFieldData";
			var paramType = "?k=";
			var ajaxMode = (mode && mode.toUpperCase() === "A")?true:false;
			
			if(typeof key !== "undefined")
			{
				if(key.search(',')!=-1)
				{
					paramType='?l=';
					//ajaxMode = true;
				}
				staticUrl =  staticUrl + paramType + key;
			}
			
			return $.ajax({
				url : staticUrl,
				data : ({dataType:"json"}),
				async:ajaxMode,
				timeout:30000,
			});
		}
    };	
	
	SessionStorage.prototype.storeUserData= function(key,value)
	{
        if(typeof(Storage)!='undefined' && !this.debugMode)
            localStorage.setItem(key,value);
	};
	SessionStorage.prototype.getUserData = function(key)
	{
        if(typeof(Storage)!='undefined'&& !this.debugMode)
            return localStorage.getItem(key);
	};
	/*
	 *this funciton will get data from statictable and search for value 
	 */
	
	SessionStorage.prototype.searchValue = function(data,searchKey)
	{
		var ele= this;
		if(typeof data != 'object' && typeof data != 'array')
				return false;
		var res = false;
		$.each(data,function(value,label)
		{
			var bVal = ele.searchValue(label,searchKey); 
			if(bVal === false)
			{
				if(value === searchKey && typeof label == "string")
				{
					res = label;
				}
			}
			else 
			{
				res = bVal;
			}
		});
		return res;
	} 
	SessionStorage.prototype.getCorrespondingLabel = function(key,searchKey)
	{
		var data = this.returnData(key);
		data = this.parseJson(data);
		if(data === false)
			return false;
		var res = false;
		if(data && typeof data != "undefined")
			res = this.searchValue(data,searchKey);
			
		return res;	
	};
	SessionStorage.prototype.removeUserData = function(key)
	{
        if(typeof(Storage)!='undefined' && !this.debugMode)
            localStorage.removeItem(key);
	}
	SessionStorage.prototype.recoverStaticTables =function()
	{
        
        if(typeof(Storage)!='undefined' && !this.debugMode)
            this[this.stname] = JSON.parse(localStorage.getItem(this.stname));
		if(this[this.stname]===null)
		{
			this[this.stname] = {};
		}
	}
    SessionStorage.prototype.isDebugMode = function()
    {
        return this.debugMode;
    }
    SessionStorage.prototype.isStorageExist = function()
    {
        var bVal = true;
        if(typeof(Storage)=='undefined')
            bVal = false;
        
        try{
            localStorage.setItem('testLS',"true");
            localStorage.getItem('testLS');
            localStorage.removeItem('testLS');
        }catch(e)
        {
            bVal = false;
        }
        return bVal;
    }
    function SessionStorage(key) {
		this.sessionResults={};
		this.stname='statictables';
		this.debugMode = false;
        if(!this.debugMode && this.isStorageExist() == false)
            this.debugMode = true;
        if(this.debugMode)
            this[this.stname] = {};
		this.recoverStaticTables();
		var ele =this;
		if(typeof this[this.stname] != "undefined")
		{
			if(key && key.length)
			{	
				key = key.toLowerCase();
				key = this.filterKeys(key);
				if(!key.length)
					return;
				var myScope = this;
				this.fetch(key).success(function(data,textStatus,jqXHR){
					myScope.store(data,key);
				});
			}
		}
    };
	this.SessionStorage = SessionStorage;

}).call(this);

})();
