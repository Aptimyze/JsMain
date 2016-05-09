	
	var tag = js_array.zedo.tag;
	var zedo = '<iframe  src="http://xp2.zedo.com/jsc/xp2/ff2.html?n=2466;c=SOURCE;s=NETWORK;d=SIZE;w=WIDTH;h=HEIGHT;ct='+js_array.custom+'" frameborder=0 marginheight=0 marginwidth=0 scrolling="no" allowTransparency="true" width=WIDTH height=HEIGHT style="border:none;" ></iframe>';
	setTimeout(function() {
	for (var key in tag) {
		var obj = tag[key];
		str = zedo.replace(/SOURCE/g,obj.source);
		str = str.replace(/NETWORK/g,obj.network);
		str = str.replace(/SIZE/g,obj.size);
		str = str.replace(/WIDTH/g,obj.width);
		str = str.replace(/HEIGHT/g,obj.height);
		if($('#zedo_'+key).length != 0) {
			$('#zedo_'+key).html(str);	
		}
		
	}},10000);
