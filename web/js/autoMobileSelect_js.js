
var dependantID="dependantID";


(function ($) {

    $.fn.AutoSug = function (options) {
      z=new AutoSug(this,options);
      //z.AutoSug();
      
      
     
    };
  
 })(jQuery);
 
 
(function() {
  var AutoSug;

  AutoSug = (function() {

    function AutoSug(jqueryID,options) {
			this.selectID=jqueryID;
			this.options=options;
			this.register_observers();
      
    }

     AutoSug.prototype.register_observers=function(){
			
      var _this = this;
			
			$(_this.selectID).change(function(evt) {
				
					var id=_this.options.dependantID;
					//var json=_this.options.dependantJson;
						var json=AllJson[_this.options.dependantJson];
					var depParent=_this.options.depParent;
					
					$("#"+id).html("");
					if(json.hasOwnProperty($(this).val()))
					{
						var indexArr=json[$(this).val()];
						
						$("#"+depParent).css("display","block");
						$.each(indexArr, function(index, itemData) {
							
							if(itemData[3])
							{
								$("#"+id).append("<optgroup label='"+itemData[1]+"'>");
							}
							else							
							$("#"+id).append("<option value="+itemData[0]+">"+itemData[1]+"</option>");
						});
						//UpdateSelectDropDown($("#"+id));
					}
					else
					{
						$("#"+depParent).css("display","none");
					}		
			});

      $(_this).blur(function(evt) {
      });
		};
    return AutoSug;

  })();
  this.AutoSug = AutoSug;

}).call(this);
