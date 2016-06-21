var ChatPc = ChatPc || {};
(function(){	
	ChatPc.CallPlugin = {
		init : function(){
			$(document).ready(function () {				
				  $('#chatOpenPanel').chatplugin({
                   device: device,
                   listingJsonData: {}
               });
			});
		}
	};
	ChatPc.CallPlugin.init();
})();