var ChatPc = ChatPc || {};
(function(){	
	ChatPc.CallPlugin = {
		init : function(){
			$(document).ready(function () {				
				  $('#chatOpenPanel').chatplugin({
                   device: 'PC',
                   listingJsonData: {}
               });
			});
		}
	};
	ChatPc.CallPlugin.init();
})();