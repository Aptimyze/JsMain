var ChatPc = ChatPc || {};
(function(){	
	ChatPc.CallPlugin = {
		init : function(){
			$(document).ready(function () {				
				  $('#chatOpenPanel').chatplugin({
                   device: 'PC',
                   Tab1JsonData: {}
               });
			});
		}
	};
	ChatPc.CallPlugin.init();
})();